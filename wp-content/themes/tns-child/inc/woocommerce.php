<?php

/**
 * Vars
 */
$div_tag              = 'div';
$container_class      = 'container';
$row_class            = 'row';
$product_gallery_wrap = 'col-lg-6';
$product_detail_wrap  = 'col-lg-6';
$product_tabs_wrap    = 'col-12';

/**
 * Disable woocommerce default stylesheet.
 */
add_filter('woocommerce_enqueue_styles', '__return_empty_array');

/**
 * Sets up theme defaults and registers support for various WooCommerce features.
 */
add_action('after_setup_theme', 'woocommerce_setup');
function woocommerce_setup(){
	add_theme_support('wc-product-gallery-zoom');
	add_theme_support('wc-product-gallery-lightbox');
	add_theme_support('wc-product-gallery-slider');
}

/**
 * Add bootstrap container to woocommerce loop
 */
add_action('woocommerce_before_main_content', function () use ($div_tag, $container_class){
	tns_open_tag($div_tag, $container_class);
}, 15);
add_action('woocommerce_after_main_content', function () use ($div_tag){
	tns_close_tag($div_tag);
}, 5);

/**
 * WooCommerce loop start class
 */
add_filter('tns_wc_loop_start_class', function (){
	return 'row';
});

/**
 * WooCommerce loop item class
 */
add_filter('tns_wc_loop_class', function (){
	return 'col-lg-3';
});

/**
 * Add boostrap grid to single product
 */

// Open boostrap row
add_action('woocommerce_before_single_product_summary', function () use ($div_tag, $row_class){
	tns_open_tag($div_tag, $row_class);
}, 1);

// Close boostrap row
add_action('woocommerce_after_single_product_summary', function () use ($div_tag){
	tns_close_tag($div_tag);
}, 30);

// Open product gallery col
add_action('woocommerce_before_single_product_summary',
	function () use ($div_tag, $product_gallery_wrap){
		tns_open_tag($div_tag, $product_gallery_wrap);
	}, 5);

// Close product gallery col
add_action('woocommerce_before_single_product_summary', function () use ($div_tag){
	tns_close_tag($div_tag);
}, 25);

// Open product detail col
add_action('woocommerce_before_single_product_summary',
	function () use ($div_tag, $product_detail_wrap){
		tns_open_tag($div_tag, $product_detail_wrap);
	}, 30);

// Close product detail col
add_action('woocommerce_after_single_product_summary', function () use ($div_tag){
	tns_close_tag($div_tag);
}, 1);

// Open product tabs col
add_action('woocommerce_after_single_product_summary',
	function () use ($div_tag, $product_tabs_wrap){
		tns_open_tag($div_tag, $product_tabs_wrap);
	}, 5);

// Close product tabs col
add_action('woocommerce_after_single_product_summary', function () use ($div_tag){
	tns_close_tag($div_tag);
}, 25);
