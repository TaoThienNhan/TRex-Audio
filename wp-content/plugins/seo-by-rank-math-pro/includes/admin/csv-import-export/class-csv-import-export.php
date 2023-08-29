<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @since      1.0
 * @package    RankMathPro
 * @subpackage RankMathPro\Admin
 * @author     Rank Math <support@rankmath.com>
 */

namespace RankMathPro\Admin\CSV_Import_Export;

use RankMath\Helper;
use RankMath\Traits\Hooker;

defined( 'ABSPATH' ) || exit;

/**
 * CSV Import Export class.
 *
 * @codeCoverageIgnore
 */
class CSV_Import_Export {

	use Hooker;

	/**
	 * Register hooks.
	 */
	public function __construct() {
		$this->filter( 'rank_math/admin/import_export_panels', 'add_panel', 15 );
		$this->action( 'admin_enqueue_scripts', 'enqueue' );

		$this->action( 'admin_init', 'maybe_do_import', 99 );
		$this->action( 'admin_init', 'maybe_do_export', 110 );
		$this->action( 'admin_init', 'maybe_cancel_import', 120 );

		$this->action( 'wp_ajax_csv_import_progress', 'csv_import_progress' );

		Import_Background_Process::get();
	}

	/**
	 * Add CSV import/export panel.
	 *
	 * @param array $panels Panels array.
	 * @return array
	 */
	public function add_panel( $panels ) {
		// Insert after "import".
		$position   = array_search( 'import', array_keys( $panels ), true ) + 1;
		$new        = array_slice( $panels, 0, $position );
		$new['csv'] = [
			'view'  => RANK_MATH_PRO_PATH . 'includes/views/csv-import-export-panel.php',
			'class' => 'import-export-csv',
		];
		$end        = array_slice( $panels, $position );
		$result     = array_merge( $new, $end );

		return $result;
	}

	/**
	 * Check if current screen is Status & Tools > Import / Export.
	 *
	 * @return bool
	 */
	public function is_import_export_screen() {
		return is_admin() && ! wp_doing_ajax() && isset( $_GET['page'] ) && 'rank-math-status' === $_GET['page'] && isset( $_GET['view'] ) && 'import_export' === $_GET['view']; // phpcs:ignore
	}

	/**
	 * Enqueue styles.
	 *
	 * @return void
	 */
	public function enqueue() {
		if ( ! $this->is_import_export_screen() ) {
			return;
		}

		Helper::add_json( 'confirmCsvImport', __( 'Are you sure you want to import meta data from this CSV file?', 'rank-math-pro' ) );
		Helper::add_json( 'confirmCsvCancel', __( 'Are you sure you want to stop the import process?', 'rank-math-pro' ) );
		Helper::add_json( 'csvProgressNonce', wp_create_nonce( 'rank_math_csv_progress' ) );

		wp_enqueue_style( 'rank-math-pro-csv-import-export', RANK_MATH_PRO_URL . 'assets/admin/css/import-export.css', [], RANK_MATH_PRO_VERSION );
		wp_enqueue_script( 'rank-math-pro-csv-import-export', RANK_MATH_PRO_URL . 'assets/admin/js/import-export.js', [], RANK_MATH_PRO_VERSION, true );
	}

	/**
	 * Add notice after import is started.
	 *
	 * @return void
	 */
	public function add_notice() {
		if ( ! $this->is_import_export_screen() ) {
			return;
		}

		Helper::add_notification( esc_html__( 'CSV import is in progress...', 'rank-math-pro' ), [ 'type' => 'success' ] );
	}

	/**
	 * Start export if requested and allowed.
	 *
	 * @return void
	 */
	public function maybe_do_export() {
		if ( ! is_admin() || empty( $_POST['rank_math_pro_csv_export'] ) ) {
			return;
		}
		if ( empty( $_POST['object_types'] ) || ! is_array( $_POST['object_types'] ) ) {
			wp_die( esc_html__( 'Please select at least one object type to export.', 'rank-math-pro' ) );
		}
		if ( ! wp_verify_nonce( isset( $_REQUEST['_wpnonce'] ) ? $_REQUEST['_wpnonce'] : '', 'rank_math_pro_csv_export' ) ) {
			wp_die( esc_html__( 'Invalid nonce.', 'rank-math-pro' ) );
		}
		if ( ! current_user_can( 'export' ) ) {
			wp_die( esc_html__( 'Sorry, you are not allowed to export the content of this site.', 'rank-math-pro' ) );
		}

		$use_advanced_options = ! empty( $_POST['use_advanced_options'] );
		$advanced_options     = [
			'post_types'       => isset( $_POST['post_types'] ) && is_array( $_POST['post_types'] ) ? array_map( 'sanitize_title', wp_unslash( $_POST['post_types'] ) ) : [],
			'taxonomies'       => isset( $_POST['taxonomies'] ) && is_array( $_POST['taxonomies'] ) ? array_map( 'sanitize_title', wp_unslash( $_POST['taxonomies'] ) ) : [],
			'roles'            => isset( $_POST['roles'] ) && is_array( $_POST['roles'] ) ? array_map( 'sanitize_title', wp_unslash( $_POST['roles'] ) ) : [],
			'readonly_columns' => ! empty( $_POST['readonly_columns'] ),
		];

		$exporter = new Exporter( array_map( 'sanitize_title', wp_unslash( $_POST['object_types'] ) ), $use_advanced_options ? $advanced_options : false );
		$exporter->process_export();
	}

	/**
	 * Start import if requested and allowed.
	 *
	 * @return void
	 */
	public function maybe_do_import() {
		if ( ! is_admin() || empty( $_POST['object_id'] ) || 'csv-import-plz' !== $_POST['object_id'] ) {
			return;
		}
		if ( empty( $_FILES['csv-import-me'] ) || empty( $_FILES['csv-import-me']['name'] ) ) {
			wp_die( esc_html__( 'Please select a file to import.', 'rank-math-pro' ) );
		}
		if ( ! wp_verify_nonce( isset( $_REQUEST['_wpnonce'] ) ? $_REQUEST['_wpnonce'] : '', 'rank_math_pro_csv_import' ) ) {
			wp_die( esc_html__( 'Invalid nonce.', 'rank-math-pro' ) );
		}
		if ( ! current_user_can( 'import' ) ) {
			wp_die( esc_html__( 'Sorry, you are not allowed to import contents to this site.', 'rank-math-pro' ) );
		}

		// Rename file.
		$info                            = pathinfo( $_FILES['csv-import-me']['name'] );
		$_FILES['csv-import-me']['name'] = uniqid( 'rm-csv-' ) . ( ! empty( $info['extension'] ) ? '.' . $info['extension'] : '' );

		// Handle file.
		$this->filter( 'upload_mimes', 'allow_csv_upload' );
		$file = wp_handle_upload( $_FILES['csv-import-me'], [ 'test_form' => false ] );
		$this->remove_filter( 'upload_mimes', 'allow_csv_upload', 10 );
		if ( ! $this->validate_file( $file ) ) {
			return false;
		}

		$settings = [
			'no_overwrite' => ! empty( $_POST['no_overwrite'] ),
		];

		$importer = new Importer();
		$importer->start( $file['file'], $settings );
	}

	/**
	 * Allow CSV file upload.
	 *
	 * @param array $types    Mime types keyed by the file extension regex corresponding to those types.
	 * @return array
	 */
	public function allow_csv_upload( $types ) {
		$types['csv'] = 'text/csv';

		return $types;
	}

	/**
	 * Validate file.
	 *
	 * @param mixed $file File array or object.
	 * @return bool
	 */
	public function validate_file( $file ) {
		if ( is_wp_error( $file ) ) {
			Helper::add_notification( esc_html__( 'CSV could not be imported:', 'rank-math-pro' ) . ' ' . $file->get_error_message(), [ 'type' => 'error' ] );
			return false;
		}

		if ( isset( $file['error'] ) ) {
			Helper::add_notification( esc_html__( 'CSV could not be imported:', 'rank-math-pro' ) . ' ' . $file['error'], [ 'type' => 'error' ] );
			return false;
		}

		if ( ! isset( $file['file'] ) ) {
			Helper::add_notification( esc_html__( 'CSV could not be imported: Upload failed.', 'rank-math-pro' ), [ 'type' => 'error' ] );
			return false;
		}

		if ( ! isset( $file['type'] ) || 'text/csv' !== $file['type'] ) {
			\unlink( $file['file'] );
			Helper::add_notification( esc_html__( 'CSV could not be imported: File type error.', 'rank-math-pro' ), [ 'type' => 'error' ] );
			return false;
		}

		return true;
	}

	/**
	 * Get import/export CSV columns.
	 *
	 * @return array
	 */
	public static function get_columns() {
		$columns = [
			'id',
			'object_type',
			'slug',
			'seo_title',
			'seo_description',
			'is_pillar_content',
			'focus_keyword',
			'seo_score',
			'robots',
			'advanced_robots',
			'canonical_url',
			'primary_term',
			'schema_data',
			'social_facebook_thumbnail',
			'social_facebook_title',
			'social_facebook_description',
			'social_twitter_thumbnail',
			'social_twitter_title',
			'social_twitter_description',
		];

		if ( Helper::is_module_active( 'redirections' ) ) {
			$columns[] = 'redirect_to';
			$columns[] = 'redirect_type';
		}

		/**
		 * Filter columns array.
		 */
		return apply_filters( 'rank_math/admin/csv_export_columns', $columns );
	}

	/**
	 * Get object types.
	 *
	 * @return array
	 */
	public static function get_possible_object_types() {
		$object_types = [
			'post' => __( 'Posts', 'rank-math-pro' ),
			'term' => __( 'Terms', 'rank-math-pro' ),
			'user' => __( 'Users', 'rank-math-pro' ),
		];

		/**
		 * Filter object types array.
		 */
		return apply_filters( 'rank_math/admin/csv_export_object_types', $object_types );
	}

	/**
	 * Check if cancel request is valid.
	 *
	 * @return void
	 */
	public static function maybe_cancel_import() {
		if ( ! is_admin() || empty( $_GET['rank_math_cancel_csv_import'] ) ) {
			return;
		}
		if ( ! wp_verify_nonce( isset( $_REQUEST['_wpnonce'] ) ? $_REQUEST['_wpnonce'] : '', 'rank_math_pro_cancel_csv_import' ) ) {
			Helper::add_notification( esc_html__( 'Import could not be canceled: invalid nonce. Please try again.', 'rank-math-pro' ), [ 'type' => 'error' ] );
			wp_safe_redirect( remove_query_arg( 'rank_math_cancel_csv_import' ) );
			exit;
		}
		if ( ! current_user_can( 'import' ) ) {
			Helper::add_notification( esc_html__( 'Import could not be canceled: you are not allowed to import content to this site.', 'rank-math-pro' ), [ 'type' => 'error' ] );
			wp_safe_redirect( remove_query_arg( 'rank_math_cancel_csv_import' ) );
			exit;
		}

		self::cancel_import();
	}

	/**
	 * Cancel import.
	 *
	 * @param bool $silent Cancel silently.
	 * @return void
	 */
	public static function cancel_import( $silent = false ) {
		$file_path = get_option( 'rank_math_csv_import' );

		delete_option( 'rank_math_csv_import' );
		delete_option( 'rank_math_csv_import_total' );
		delete_option( 'rank_math_csv_import_status' );
		delete_option( 'rank_math_csv_import_settings' );
		Import_Background_Process::get()->cancel_process();

		if ( ! $file_path ) {
			if ( ! $silent ) {
				Helper::add_notification( esc_html__( 'Import could not be canceled.', 'rank-math-pro' ), [ 'type' => 'error' ] );
			}

			wp_safe_redirect( remove_query_arg( 'rank_math_cancel_csv_import' ) );
			exit;
		}

		unlink( $file_path );
		if ( ! $silent ) {
			Helper::add_notification(
				__( 'CSV import canceled.', 'rank-math-pro' ),
				[
					'type'    => 'success',
					'classes' => 'is-dismissible',
				]
			);
		}
		wp_safe_redirect( remove_query_arg( 'rank_math_cancel_csv_import' ) );
		exit;
	}

	/**
	 * Show import progress via AJAX.
	 *
	 * @return void
	 */
	public function csv_import_progress() {
		check_ajax_referer( 'rank_math_csv_progress' );
		if ( ! current_user_can( 'import' ) ) {
			exit( '0' );
		}

		self::import_progress_details();
		exit;
	}

	/**
	 * Output import progress details.
	 *
	 * @return void
	 */
	public static function import_progress_details() {
		$import_in_progress = (bool) get_option( 'rank_math_csv_import' );
		if ( $import_in_progress ) {
			$total_lines     = (int) get_option( 'rank_math_csv_import_total' );
			$remaining_items = Import_Background_Process::get()->count_remaining_items();
			$progress        = $total_lines ? ( $total_lines - $remaining_items + 1 ) / $total_lines * 100 : 0;
			?>
			<p><?php esc_html_e( 'Import in progress...', 'rank-math-pro' ); ?></p>
			<p class="csv-import-status">
				<?php // Translators: placeholders represent count like 15/36. ?>
				<?php printf( esc_html__( 'Items processed: %1$s/%2$s', 'rank-math-pro' ), absint( min( $total_lines, $total_lines - $remaining_items + 1 ) ), absint( $total_lines ) ); ?>
			</p>
			<div id="csv-import-progress-bar">
				<div class="total">
					<div class="progress-bar" style="width: <?php echo absint( $progress ); ?>%;"></div>
				</div>
				<input type="hidden" id="csv-import-progress-value" value="<?php echo absint( $progress ); ?>">
			</div>
			<?php
		} else {
			$status = (array) get_option( 'rank_math_csv_import_status', [] );

			$classes = 'import-finished';
			if ( ! empty( $status['errors'] ) ) {
				$classes .= ' import-errors';
			}

			$message = self::get_import_complete_message();
			?>
				<p class="<?php echo esc_attr( $classes ); ?>"><?php echo wp_kses_post( $message ); ?></p>
			<?php
		}
	}

	/**
	 * Get status message after import is complete.
	 *
	 * @return string
	 */
	public static function get_import_complete_message() {
		$status  = (array) get_option( 'rank_math_csv_import_status', [] );
		$message = sprintf(
			// Translators: placeholder is the number of rows imported.
			__( 'CSV import completed. Successfully imported %d rows.', 'rank-math-pro' ),
			count( $status['imported_rows'] )
		);

		if ( ! empty( $status['errors'] ) ) {
			$message  = __( 'CSV import completed.', 'rank-math-pro' ) . ' ';
			$message .= sprintf(
				// Translators: placeholder is the number of rows imported.
				__( 'Imported %d rows.', 'rank-math-pro' ) . ' ',
				count( $status['imported_rows'] )
			);

			if ( ! empty( $status['errors'] ) ) {
				$message .= __( 'One or more errors occured while importing: ', 'rank-math-pro' ) . '<br>';
				$message .= join( '<br>', $status['errors'] ) . '<br>';
			}
			if ( ! empty( $status['failed_rows'] ) ) {
				$message .= __( 'The following lines could not be imported: ', 'rank-math-pro' ) . '<br>';
				$message .= join( ', ', $status['failed_rows'] );
			}
		}

		return $message;
	}
}
