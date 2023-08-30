<?php

/**
 * The header for our theme
 */
add_action('tns_header', function (){
	get_template_part('template-parts/header');
});

/**
 * The landing page for our theme
 */
add_action('tns_landing', function (){
    get_template_part('template-parts/category-resolution');
    get_template_part('template-parts/best-seller-products');

});

/**
 * The loop for our theme
 */
add_action('tns_loop', function (){

});

/**
 * The template for displaying all pages
 */
add_action('tns_page', function (){
	if (is_cart() || is_checkout() || is_account_page() || is_wc_endpoint_url()):
		get_template_part('template-parts/content-full-width');
	endif;
});

/**
 * The template for displaying all single posts
 */
add_action('tns_single', function (){
	get_template_part('template-parts/single');
});

/**
 * The template for displaying archive pages
 */
add_action('tns_archive', function (){

});

/**
 * The template for displaying search results pages
 */
add_action('tns_search', function (){

});

/**
 * The footer for our theme
 */
add_action('tns_footer', function (){

});

/**
 * The template for displaying 404 pages (not found)
 */
add_action('tns_404', function (){

});
