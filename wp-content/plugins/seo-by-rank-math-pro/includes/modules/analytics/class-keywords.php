<?php
/**
 * The Analytics Module
 *
 * @since      2.0.0
 * @package    RankMathPro
 * @subpackage RankMathPro\modules
 * @author     Rank Math <support@rankmath.com>
 */

namespace RankMathPro\Analytics;

use WP_REST_Request;
use RankMath\Traits\Hooker;
use RankMath\Analytics\Stats;
use RankMath\Helper;
use MyThemeShop\Helpers\Param;

defined( 'ABSPATH' ) || exit;

/**
 * Keywords class.
 */
class Keywords {

	use Hooker;

	/**
	 * Main instance
	 *
	 * Ensure only one instance is loaded or can be loaded.
	 *
	 * @return Keywords
	 */
	public static function get() {
		static $instance;

		if ( is_null( $instance ) && ! ( $instance instanceof Keywords ) ) {
			$instance = new Keywords();
			$instance->setup();
		}

		return $instance;
	}

	/**
	 * Initialize filter.
	 */
	public function setup() {
		$this->filter( 'rank_math/analytics/keywords', 'add_keyword_position_graph' );
		$this->filter( 'rank_math/analytics/keywords_overview', 'add_winning_losing_data' );
		$this->action( 'save_post', 'add_post_focus_keyword' );
		$this->action( 'init', 'get_post_type_list', 99 );
	}

	/**
	 * Get accessible post type lists for auto add focus keywords.
	 */
	public function get_post_type_list() {
		if ( 'rank-math-analytics' !== Param::get( 'page' ) ) {
			return;
		}

		$post_types = array_map(
			function( $post_type ) {
				return 'attachment' === $post_type ? false : Helper::get_post_type_label( $post_type );
			},
			Helper::get_accessible_post_types()
		);
		Helper::add_json( 'postTypes', array_filter( $post_types ) );
		Helper::add_json( 'autoAddFK', Helper::get_settings( 'general.auto_add_focus_keywords', [] ) );
	}

	/**
	 * Get keyword position graph data.
	 *
	 * @param  array $rows Rows.
	 * @return array
	 */
	public function add_keyword_position_graph( $rows ) {
		$history = $this->get_graph_data_for_keywords( \array_keys( $rows ) );
		$rows    = Stats::get()->set_query_position( $rows, $history );

		return $rows;
	}

	/**
	 * Get winning and losing data.
	 *
	 * @param  array $data Data.
	 * @return array
	 */
	public function add_winning_losing_data( $data ) {
		$data['winningKeywords'] = $this->get_winning_keywords();
		$data['losingKeywords']  = $this->get_losing_keywords();

		if ( empty( $data['winningKeywords'] ) ) {
			$data['winningKeywords']['response'] = 'No Data';
		}
		if ( empty( $data['losingKeywords'] ) ) {
			$data['losingKeywords']['response'] = 'No Data';
		}
		return $data;
	}

	/**
	 * Extract addable track keywords.
	 *
	 * @param string $keywords Comma Separated Keyword List.
	 *
	 * @return array Track keywords which can be added.
	 */
	public function extract_addable_track_keyword( $keywords ) {
		global $wpdb;

		// Split keywords.
		$keywords_to_add   = \array_map( 'trim', \explode( ',', $keywords ) );
		$keywords_to_check = \array_map( 'mb_strtolower', \explode( ',', $keywords ) );

		// Check if keywords are already exists.
		$keywords_joined = "'" . join( "', '", \array_map( 'esc_sql', $keywords_to_add ) ) . "'";
		$query           = "SELECT keyword FROM {$wpdb->prefix}rank_math_analytics_keyword_manager as km WHERE km.keyword IN ( $keywords_joined )";
		$data            = $wpdb->get_results( $query ); // phpcs:ignore

		// Only filter out non-existing keywords.
		foreach ( $data as $row ) {
			$key = \array_search( mb_strtolower( $row->keyword ), $keywords_to_check, true );
			if ( false !== $key ) {
				unset( $keywords_to_add[ $key ] );
			}
		}

		return $keywords_to_add;
	}

	/**
	 * Add track keyword.
	 *
	 * @param array $keywords Keyword List.
	 */
	public function add_track_keyword( $keywords ) {
		foreach ( $keywords as $add_keyword ) {
			DB::keywords()->insert(
				[
					'keyword'    => $add_keyword,
					'collection' => 'uncategorized',
					'is_active'  => true,
				],
				[ '%s', '%s', '%d' ]
			);
		}

		delete_transient( Stats::get()->get_cache_key( 'tracked_keywords_summary', Stats::get()->days . 'days' ) );
	}

	/**
	 * Remove tack keyword.
	 *
	 * @param string $keyword Keyword to remove.
	 */
	public function remove_track_keyword( $keyword ) {
		DB::keywords()->where( 'keyword', $keyword )
			->delete();

		delete_transient( Stats::get()->get_cache_key( 'tracked_keywords_summary', Stats::get()->days . 'days' ) );
	}

	/**
	 * Get track keywords count.
	 *
	 * @return int Total keywords count
	 */
	public function get_tracked_keywords_count() {
		$total = DB::keywords()
			->selectCount( 'DISTINCT(keyword)', 'total' )
			->where( 'is_active', 1 )
			->getVar();

		return (int) $total;
	}

	/**
	 * Get keywords quota.
	 *
	 * @return array Keywords usage info.
	 */
	public function get_tracked_keywords_quota() {
		$quota = (array) get_option(
			'rank_math_keyword_quota',
			[
				'taken'     => 0,
				'available' => 0,
			]
		);

		return $quota;
	}

	/**
	 * Get keywords summary.
	 *
	 * @return array Keywords usage info.
	 */
	public function get_tracked_keywords_summary() {
		$summary          = $this->get_tracked_keywords_quota();
		$summary['total'] = $this->get_tracked_keywords_count();

		return $summary;
	}

	/**
	 * Get winning tracked keywords.
	 *
	 * @return array Top 5 winning tracked keywords data.
	 */
	public function get_tracked_winning_keywords() {
		return $this->get_tracked_keywords(
			[
				'offset'  => 0,
				'perpage' => 5,
				'where'   => 'WHERE COALESCE( ROUND( t1.position - COALESCE( t2.position, 100 ), 0 ), 0 ) < 0',
			]
		);
	}

	/**
	 * Get losing tracked keywords.
	 *
	 * @return array Top 5 losing tracked keywords data.
	 */
	public function get_tracked_losing_keywords() {
		return $this->get_tracked_keywords(
			[
				'order'   => 'DESC',
				'offset'  => 0,
				'perpage' => 5,
				'where'   => 'WHERE COALESCE( ROUND( t1.position - COALESCE( t2.position, 100 ), 0 ), 0 ) > 0',
			]
		);
	}

	/**
	 * Get tracked keywords rows.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 *
	 * @return array Tracked keywords data.
	 */
	public function get_tracked_keywords_rows( WP_REST_Request $request ) {
		$per_page = 25;
		$page     = ! empty( $request->get_param( 'page' ) ) ? $request->get_param( 'page' ) : 1;
		$orderby  = ! empty( $request->get_param( 'orderby' ) ) ? $request->get_param( 'orderby' ) : 'default';
		$order    = ! empty( $request->get_param( 'order' ) ) ? strtoupper( $request->get_param( 'order' ) ) : 'DESC';
		$keyword  = ! empty( $request->get_param( 'search' ) ) ? filter_var( urldecode( $request->get_param( 'search' ) ), FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_BACKTICK ) : '';
		$offset   = ( $page - 1 ) * $per_page;
		$args     = wp_parse_args(
			[

				'dimension' => 'query',
				'limit'     => "LIMIT {$offset}, {$per_page}",
				'keyword'   => $keyword,
			]
		);
		$data     = $this->get_tracked_keywords_data( $args );
		$data     = Stats::get()->set_dimension_as_key( $data );
		$history  = $this->get_graph_data_for_keywords( \array_keys( $data ) );
		$data     = Stats::get()->set_query_position( $data, $history );

		if ( 'default' === $orderby ) {
			uasort(
				$data,
				function( $a, $b ) use ( $orderby ) {
					if ( false === array_key_exists( 'position', $a ) ) {
						$a['position'] = [ 'total' => '0' ];
					}
					if ( false === array_key_exists( 'position', $b ) ) {
						$b['position'] = [ 'total' => '0' ];
					}

					if ( 0 === intval( $b['position']['total'] ) ) {
						return false;
					}

					return $a['position']['total'] > $b['position']['total'];
				}
			);
		}

		if ( 'query' === $orderby ) {

			if ( 'DESC' === $order ) {
				uasort(
					$data,
					function( $a, $b ) use ( $orderby ) {
						return strtolower( $a[ $orderby ] ) < strtolower( $b[ $orderby ] );
					}
				);
			}

			if ( 'ASC' === $order ) {
				uasort(
					$data,
					function( $a, $b ) use ( $orderby ) {
						return strtolower( $a[ $orderby ] ) > strtolower( $b[ $orderby ] );
					}
				);
			}
		}

		if ( 'query' !== $orderby && 'default' !== $orderby ) {
			$data = $this->track_keywords_array_sort( $data, $order, $orderby );
		}
		$result['rowsData'] = $data;
		// get total rows by search.
		$args = wp_parse_args(
			[

				'dimension' => 'query',
				'limit'     => 'LIMIT 10000',
				'keyword'   => $keyword,
			]
		);

		if ( empty( $data ) ) {
			$result['response'] = 'No Data';
		} else {
			$search_data     = $this->get_tracked_keywords_data( $args );
			$result['total'] = count( $search_data );
		}
		return $result;
	}

	/**
	 * Sort array for track keyword by order and orderby
	 *
	 * @param  array    $arr array.
	 *
	 * @param  Variable $arr_order is order direction.
	 *
	 * @param  Variable $arr_orderby is key for sort.
	 *
	 * @return $arr sorted array
	 */
	public function track_keywords_array_sort( $arr, $arr_order, $arr_orderby ) {

		if ( 'DESC' === $arr_order ) {
			uasort(
				$arr,
				function( $a, $b ) use ( $arr_orderby ) {

					if ( false === array_key_exists( $arr_orderby, $a ) ) {
						$a[ $arr_orderby ] = [ 'total' => '0' ];
					}
					if ( false === array_key_exists( $arr_orderby, $b ) ) {
						$b[ $arr_orderby ] = [ 'total' => '0' ];
					}

					return $a[ $arr_orderby ]['total'] < $b[ $arr_orderby ]['total'];
				}
			);
		}

		if ( 'ASC' === $arr_order ) {
			uasort(
				$arr,
				function( $a, $b ) use ( $arr_orderby ) {

					if ( false === array_key_exists( $arr_orderby, $a ) ) {
						$a[ $arr_orderby ] = [ 'total' => '0' ];
					}
					if ( false === array_key_exists( $arr_orderby, $b ) ) {
						$b[ $arr_orderby ] = [ 'total' => '0' ];
					}

					return $a[ $arr_orderby ]['total'] > $b[ $arr_orderby ]['total'];
				}
			);
		}
		return $arr;

	}
	/**
	 * Get keyword rows from keyword manager table.
	 *
	 * @param  array $args Array of arguments.
	 * @return array
	 */
	public function get_tracked_keywords_data( $args = [] ) {
		global $wpdb;

		$args = wp_parse_args(
			$args,
			[
				'dimension' => 'query',
				'order'     => 'ASC',
				'orderBy'   => 'diffPosition1',
				'objects'   => false,
				'where'     => '',
				'sub_where' => '',
				'dates'     => ' AND created BETWEEN %s AND %s',
				'limit'     => 'LIMIT 5',
				'keyword'   => '',
			]
		);

		$where       = $args['where'];
		$limit       = $args['limit'];
		$dimension   = $args['dimension'];
		$sub_where   = $args['sub_where'];
		$dates       = $args['dates'];
		$keyword     = trim( $args['keyword'] );
		$order       = sprintf( 'ORDER BY %s %s', $args['orderBy'], $args['order'] );
		$dates_query = sprintf( " AND created BETWEEN '%s' AND '%s' ", Stats::get()->start_date, Stats::get()->end_date );
		// Step1. Get most recent data row id for each keyword.
		// phpcs:disable
		$where_like_keyword = $wpdb->prepare( ' WHERE keyword LIKE %s', '%' . $wpdb->esc_like( $keyword ) . '%' );
		if ( empty( $keyword ) ) {
			$where_like_keyword = '';
		}

		$query =  "SELECT MAX(id) as id FROM {$wpdb->prefix}rank_math_analytics_gsc WHERE 1 = 1 {$dates_query} AND {$dimension} IN ( SELECT keyword from {$wpdb->prefix}rank_math_analytics_keyword_manager {$where_like_keyword} GROUP BY keyword ) GROUP BY {$dimension}";
		$ids = $wpdb->get_results( $query );
		// phpcs:enable
		// Step2. Get id list from above result.
		$ids       = wp_list_pluck( $ids, 'id' );
		$ids_where = " AND id IN ('" . join( "', '", $ids ) . "')";

		// Step3. Get most recent data row id for each keyword (for comparison).
		// phpcs:disable
		$dates_query = sprintf( " AND created BETWEEN '%s' AND '%s' ", Stats::get()->compare_start_date, Stats::get()->compare_end_date );
		$query = "SELECT MAX(id) as id FROM {$wpdb->prefix}rank_math_analytics_gsc WHERE 1 = 1 {$dates_query} AND {$dimension} IN ( SELECT keyword from {$wpdb->prefix}rank_math_analytics_keyword_manager {$where_like_keyword} GROUP BY keyword ) GROUP BY {$dimension}";
		$old_ids = $wpdb->get_results( $query );
		// phpcs:enable

		// Step4. Get id list from above result.
		$old_ids       = wp_list_pluck( $old_ids, 'id' );
		$old_ids_where = " AND id IN ('" . join( "', '", $old_ids ) . "')";

		// Step5. Get most performing keywords first based on id list from above.
		$where_like_keyword1 = $wpdb->prepare( ' WHERE km.keyword LIKE %s', '%' . $wpdb->esc_like( $keyword ) . '%' );
		if ( empty( $keyword ) ) {
			$where_like_keyword1 = '';
		}

		$positions = $wpdb->get_results(
			"SELECT DISTINCT(km.keyword) as {$dimension}, COALESCE(t.position, 0) as position, COALESCE(t.diffPosition, 0) as diffPosition, COALESCE(t.diffPosition, 100) as diffPosition1
			FROM {$wpdb->prefix}rank_math_analytics_keyword_manager km
			LEFT JOIN (
				SELECT
					t1.{$dimension} as {$dimension}, ROUND( t1.position, 0 ) as position,
					COALESCE( ROUND( t1.position - COALESCE( t2.position, 100 ), 0 ), 0 ) as diffPosition
				FROM
					(SELECT a.{$dimension}, a.position FROM {$wpdb->prefix}rank_math_analytics_gsc AS a
					 WHERE 1 = 1{$ids_where}) AS t1
				LEFT JOIN
					(SELECT a.{$dimension}, a.position FROM {$wpdb->prefix}rank_math_analytics_gsc AS a
					 WHERE 1 = 1{$old_ids_where}) AS t2
				ON t1.{$dimension} = t2.{$dimension}) AS t on t.{$dimension} = km.keyword
				{$where_like_keyword1}
			{$where}
			{$order}
			{$limit}",
			ARRAY_A
		);

		// phpcs:enable

		// Step6. Get keywords list from above results.
		$keywords = array_column( $positions, 'query' );
		$keywords = array_map( 'esc_sql', $keywords );
		$keywords = array_map( 'strtolower', $keywords );
		$keywords = '(\'' . join( '\', \'', $keywords ) . '\')';

		// step7. Get other metrics data.
		// phpcs:disable
		$query = $wpdb->prepare(
			"SELECT t1.{$dimension} as {$dimension}, t1.clicks, t1.impressions, t1.ctr,
				COALESCE( t1.clicks - t2.clicks, 0 ) as diffClicks,
				COALESCE( t1.impressions - t2.impressions, 0 ) as diffImpressions,
				COALESCE( t1.ctr - t2.ctr, 0 ) as diffCtr
			FROM
				( SELECT {$dimension}, SUM( clicks ) as clicks, SUM(impressions) as impressions, AVG(position) as position, AVG(ctr) as ctr FROM {$wpdb->prefix}rank_math_analytics_gsc WHERE 1 = 1{$dates} AND {$dimension} IN {$keywords} GROUP BY {$dimension}) as t1
			LEFT JOIN
				( SELECT {$dimension}, SUM( clicks ) as clicks, SUM(impressions) as impressions, AVG(position) as position, AVG(ctr) as ctr FROM {$wpdb->prefix}rank_math_analytics_gsc WHERE 1 = 1{$dates} AND {$dimension} IN {$keywords} GROUP BY {$dimension}) as t2
			ON t1.query = t2.query",
			Stats::get()->start_date,
			Stats::get()->end_date,
			Stats::get()->compare_start_date,
			Stats::get()->compare_end_date
		);
		$metrics = $wpdb->get_results( $query, ARRAY_A );
		// phpcs:enable

		// Step8. Merge above two results.
		$positions = Stats::get()->set_dimension_as_key( $positions, $dimension );
		$metrics   = Stats::get()->set_dimension_as_key( $metrics, $dimension );
		$data      = Stats::get()->get_merged_metrics( $positions, $metrics );

		// Step9. Construct return data.
		foreach ( $data as $keyword => $row ) {
			$data[ $keyword ]['graph'] = [];

			$data[ $keyword ]['clicks'] = [
				'total'      => (int) $data[ $keyword ]['clicks'],
				'difference' => (int) $data[ $keyword ]['diffClicks'],
			];

			$data[ $keyword ]['impressions'] = [
				'total'      => (int) $data[ $keyword ]['impressions'],
				'difference' => (int) $data[ $keyword ]['diffImpressions'],
			];

			$data[ $keyword ]['position'] = [
				'total'      => (float) $data[ $keyword ]['position'],
				'difference' => (float) $data[ $keyword ]['diffPosition'],
			];

			$data[ $keyword ]['ctr'] = [
				'total'      => (float) $data[ $keyword ]['ctr'],
				'difference' => (float) $data[ $keyword ]['diffCtr'],
			];

			unset(
				$data[ $keyword ]['diffClicks'],
				$data[ $keyword ]['diffImpressions'],
				$data[ $keyword ]['diffPosition'],
				$data[ $keyword ]['diffCtr']
			);
		}

		return $data;
	}

	/**
	 * Get tracked keywords.
	 *
	 * @param  array $args Array of arguments.
	 * @return array
	 */
	public function get_tracked_keywords( $args = [] ) {
		global $wpdb;

		$args = wp_parse_args(
			$args,
			[
				'dimension' => 'query',
				'order'     => 'ASC',
				'orderBy'   => 'diffPosition',
				'offset'    => 0,
				'perpage'   => 20000,
				'sub_where' => " AND query IN ( SELECT keyword from {$wpdb->prefix}rank_math_analytics_keyword_manager )",
			]
		);

		$data    = Stats::get()->get_analytics_data( $args );
		$history = $this->get_graph_data_for_keywords( \array_keys( $data ) );
		$data    = Stats::get()->set_query_position( $data, $history );

		// Add remaining keywords.
		if ( 5 !== $args['perpage'] ) {
			$rows = DB::keywords()->get();
			foreach ( $rows as $row ) {
				if ( ! isset( $data[ $row->keyword ] ) ) {
					$data[ $row->keyword ] = [
						'query'       => $row->keyword,
						'graph'       => [],
						'clicks'      => [
							'total'      => 0,
							'difference' => 0,
						],
						'impressions' => [
							'total'      => 0,
							'difference' => 0,
						],
						'position'    => [
							'total'      => 0,
							'difference' => 0,
						],
						'ctr'         => [
							'total'      => 0,
							'difference' => 0,
						],
						'pageviews'   => [
							'total'      => 0,
							'difference' => 0,
						],
					];
				}
			}
		}

		return $data;
	}

	/**
	 * Get most recent day's keywords.
	 *
	 * @return array
	 */
	public function get_recent_keywords() {
		global $wpdb;

		$query = $wpdb->prepare(
			"SELECT query
			FROM {$wpdb->prefix}rank_math_analytics_gsc
			WHERE DATE(created) = (SELECT MAX(DATE(created)) FROM {$wpdb->prefix}rank_math_analytics_gsc WHERE created BETWEEN %s AND %s)
			GROUP BY query",
			Stats::get()->start_date,
			Stats::get()->end_date
		);
		$data = $wpdb->get_results( $query ); // phpcs:ignore

		return $data;
	}

	/**
	 * Get top 5 winning keywords.
	 *
	 * @return array
	 */
	public function get_winning_keywords() {
		$cache_key = Stats::get()->get_cache_key( 'winning_keywords', Stats::get()->days . 'days' );
		$cache     = get_transient( $cache_key );

		if ( false !== $cache ) {
			return $cache;
		}

		// Get most recent day's keywords only.
		$keywords = $this->get_recent_keywords();
		$keywords = wp_list_pluck( $keywords, 'query' );
		$keywords = array_map( 'strtolower', $keywords );
		$data     = Stats::get()->get_analytics_data(
			[
				'order'     => 'ASC',
				'dimension' => 'query',
				'where'     => 'WHERE COALESCE( ROUND( t1.position - COALESCE( t2.position, 100 ), 0 ), 0 ) < 0',
			]
		);
		$history  = $this->get_graph_data_for_keywords( \array_keys( $data ) );
		$data     = Stats::get()->set_query_position( $data, $history );

		set_transient( $cache_key, $data, DAY_IN_SECONDS );

		return $data;
	}

	/**
	 * Get top 5 losing keywords.
	 *
	 * @return array
	 */
	public function get_losing_keywords() {
		$cache_key = Stats::get()->get_cache_key( 'losing_keywords', Stats::get()->days . 'days' );
		$cache     = get_transient( $cache_key );

		if ( false !== $cache ) {
			return $cache;
		}

		// Get most recent day's keywords only.
		$keywords = $this->get_recent_keywords();
		$keywords = wp_list_pluck( $keywords, 'query' );
		$keywords = array_map( 'strtolower', $keywords );

		$data    = Stats::get()->get_analytics_data(
			[
				'dimension' => 'query',
				'where'     => 'WHERE COALESCE( ROUND( t1.position - COALESCE( t2.position, 100 ), 0 ), 0 ) > 0',
			]
		);
		$history = $this->get_graph_data_for_keywords( \array_keys( $data ) );
		$data    = Stats::get()->set_query_position( $data, $history );

		set_transient( $cache_key, $data, DAY_IN_SECONDS );

		return $data;
	}

	/**
	 * Get keywords graph data.
	 *
	 * @param array $keywords Keywords to get data for.
	 *
	 * @return array
	 */
	public function get_graph_data_for_keywords( $keywords ) {
		global $wpdb;

		$intervals     = Stats::get()->get_intervals();
		$sql_daterange = Stats::get()->get_sql_date_intervals( $intervals );
		$keywords      = \array_map( 'esc_sql', $keywords );
		$keywords      = '(\'' . join( '\', \'', $keywords ) . '\')';

		// phpcs:disable
		$query = $wpdb->prepare(
			"SELECT a.query, a.position, t.date, t.range_group
			FROM {$wpdb->prefix}rank_math_analytics_gsc AS a
			INNER JOIN
				(SELECT query, DATE_FORMAT(created, '%%Y-%%m-%%d') as date, MAX(id) as id, {$sql_daterange}
				FROM {$wpdb->prefix}rank_math_analytics_gsc
				WHERE created BETWEEN %s AND %s
					AND query IN {$keywords}
				GROUP BY query, range_group
				ORDER BY created ASC) AS t ON a.id = t.id
			",
			Stats::get()->start_date,
			Stats::get()->end_date
		);
		$data  = $wpdb->get_results( $query );
		// phpcs:enable

		$data = Stats::get()->filter_graph_rows( $data );

		return array_map( [ Stats::get(), 'normalize_graph_rows' ], $data );
	}

	/**
	 * Get pages by keyword.
	 *
	 * @param  WP_REST_Request $request Full details about the request.
	 * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
	 */
	public function get_keyword_pages( WP_REST_Request $request ) {
		global $wpdb;

		$query = $wpdb->prepare(
			"SELECT DISTINCT g.page
			FROM {$wpdb->prefix}rank_math_analytics_gsc as g
			WHERE g.query = %s AND g.created BETWEEN %s AND %s
			ORDER BY g.created DESC
			LIMIT 5",
			$request->get_param( 'query' ),
			Stats::get()->start_date,
			Stats::get()->end_date
		);

		$data    = $wpdb->get_results( $query ); // phpcs:ignore
		$pages   = wp_list_pluck( $data, 'page' );
		$console = Stats::get()->get_analytics_data(
			[
				'objects'   => true,
				'pageview'  => true,
				'sub_where' => " AND page IN ('" . join( "', '", $pages ) . "')",
			]
		);
		return $console;

	}

	/**
	 * Save post.
	 *
	 * @param  int $post_id Post ID.
	 * @return mixed
	 */
	public function add_post_focus_keyword( $post_id ) {
		if ( wp_is_post_revision( $post_id ) ) {
			return;
		}

		$auto_add_fks = Helper::get_settings( 'general.auto_add_focus_keywords', [] );
		if (
			empty( $auto_add_fks['enable_auto_import'] ) ||
			empty( $auto_add_fks['post_types'] ) ||
			! in_array( get_post_type( $post_id ), $auto_add_fks['post_types'], true )
		) {
			return;
		}

		$focus_keyword = Helper::get_post_meta( 'focus_keyword', $post_id );
		if ( empty( $focus_keyword ) ) {
			return;
		}

		$keywords_data = [];
		$keywords      = explode( ',', $focus_keyword );
		if ( ! empty( $auto_add_fks['secondary_keyword'] ) ) {
			$keywords_data = $keywords;
		} else {
			$keywords_data[] = current( $keywords );
		}

		DB::bulk_insert_query_focus_keyword_data( $keywords_data );
	}
}
