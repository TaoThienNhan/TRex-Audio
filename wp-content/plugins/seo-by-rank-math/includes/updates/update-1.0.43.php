<?php
/**
 * The Updates routine for version 1.0.43
 *
 * @since      1.0.43
 * @package    RankMath
 * @subpackage RankMath\Updates
 * @author     Rank Math <support@rankmath.com>
 */

use RankMath\Helper;

defined( 'ABSPATH' ) || exit;

/**
 * Enable the new Image SEO module if either the "add_img_alt" or the
 * "add_img_title" is being used.
 */
function rank_math_1_0_43_maybe_enable_image_seo_module() {
	if ( Helper::get_settings( 'general.add_img_alt' ) || Helper::get_settings( 'general.add_img_title' ) ) {
		Helper::update_modules( [ 'image-seo' => 'on' ] );
	}
}
rank_math_1_0_43_maybe_enable_image_seo_module();

/**
 * Update setup mode on existing sites.
 */
function rank_math_1_0_43_update_setup_mode() {
	$all_opts              = rank_math()->settings->all_raw();
	$general               = $all_opts['general'];
	$general['setup_mode'] = 'advanced';

	Helper::update_all_settings( $general, null, null );
	rank_math()->settings->reset();
}
rank_math_1_0_43_update_setup_mode();
