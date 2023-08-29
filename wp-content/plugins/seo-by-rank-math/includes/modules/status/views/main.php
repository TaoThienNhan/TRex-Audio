<?php
/**
 * Status & Tools main view file.
 *
 * @package    RankMath
 * @subpackage RankMath\Status
 * @author     Rank Math <support@rankmath.com>
 * @license    GPL-2.0+
 * @link       https://rankmath.com/wordpress/plugin/seo-suite/
 * @copyright  2019 Rank Math
 */

use RankMath\Helper;
use MyThemeShop\Helpers\Param;

defined( 'ABSPATH' ) || exit;

$default_tab = apply_filters( 'rank_math/tools/default_tab', 'status' );
$module      = Helper::get_module( 'status' );
$current     = Param::get( 'view', $default_tab );

if ( ! in_array( $current, array_keys( apply_filters( 'rank_math/tools/pages', [] ) ), true ) ) {
	Helper::redirect( Helper::get_admin_url( 'status' ) );
	exit;
}

// Header.
rank_math()->admin->display_admin_header();
?>
<div class="wrap rank-math-wrap rank-math-tools-wrap dashboard">

	<span class='wp-header-end'></span>

	<?php $module->display_nav(); ?>

	<div class="rank-math-ui dashboard-wrapper container <?php echo esc_attr( $current ); ?>">
		<?php $module->display_body( $current ); ?>
	</div>

</div>
