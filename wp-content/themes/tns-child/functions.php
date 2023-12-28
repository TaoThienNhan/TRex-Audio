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

function theme_name_register_menus() {
    register_nav_menus(
        array(
            'danh-muc-menu' => 'Menu Danh Mục',
            'chinh-sach-menu' => 'Menu Chính Sách',
            'danh-muc-footer-menu' => 'Menu Danh Mục Footer'
        )
    );
}
add_action('init', 'theme_name_register_menus');

add_filter('loop_shop_per_page', 'new_loop_shop_per_page', 20);

function new_loop_shop_per_page($cols)
{
    $cols = 12;
    return $cols;
}