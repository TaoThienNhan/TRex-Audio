<?php

/**
 * The file that defines the theme framework
 */

/**
 * The main template file
 */
function tns_loop(){
	get_header(); ?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main">
			<?php do_action('tns_loop'); ?>
		</main>
	</div>
	<?php get_footer();
}

/**
 * The landing page for our theme
 */
function tns_landing(){
	get_header(); ?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main">
			<?php do_action('tns_landing'); ?>
		</main>
	</div>
	<?php get_footer();
}

/**
 * The template for displaying archive pages
 */
function tns_archive(){
	get_header(); ?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main">
			<?php do_action('tns_archive'); ?>
		</main>
	</div>
	<?php get_footer();
}

/**
 * The template for displaying search results pages
 */
function tns_search(){
	get_header(); ?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main">
			<?php do_action('tns_search'); ?>
		</main>
	</div>
	<?php get_footer();
}

/**
 * The template for displaying all pages
 */
function tns_page(){
	get_header(); ?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main">
			<?php do_action('tns_page'); ?>
		</main>
	</div>
	<?php get_footer();
}

/**
 * The template for displaying all single posts
 */
function tns_single(){
	get_header(); ?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main">
			<?php do_action('tns_single'); ?>
		</main>
	</div>
	<?php get_footer();
}

/**
 * The template for displaying 404 pages (not found)
 */
function tns_404(){
	get_header(); ?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main">
			<?php do_action('tns_404'); ?>
		</main>
	</div>
	<?php get_footer();
}
