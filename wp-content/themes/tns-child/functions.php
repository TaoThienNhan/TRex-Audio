<?php

/**
 * Include init.php
 */
require_once get_theme_file_path() . '/inc/init.php';

/**
 * WooCommerce functions
 */
if (class_exists('WooCommerce')):
	require_once get_theme_file_path() . '/inc/woocommerce.php';
endif;

/**
 * Include layouts.php
 */
require_once get_theme_file_path() . '/inc/layouts.php';
