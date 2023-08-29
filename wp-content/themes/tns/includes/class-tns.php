<?php

/**
 * The file that defines the core theme class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 */

class Tns {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this theme.
	 */
	protected $theme_name;

	/**
	 * The current version of the theme.
	 */
	protected $version;

	/**
	 * Define the core functionality of the theme.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 */
	public function __construct(){
		if (defined('TNS_VERSION')){
			$this->version = TNS_VERSION;
		}else{
			$this->version = '1.0.0';
		}
		$this->theme_name = 'tns';

		$this->load_dependencies();
		$this->define_admin_hooks();
		$this->define_public_hooks();

		// Remove default favicon admin and login page
		if (is_admin() || $this->is_login_page()):
			add_filter('get_site_icon_url', '__return_false');
		endif;
	}

	/**
	 * Load the required dependencies for this theme.
	 *
	 * Include the following files that make up the theme:
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 */
	private function load_dependencies(){

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once get_template_directory() . '/includes/class-tns-loader.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once get_template_directory() . '/includes/class-tns-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once get_template_directory() . '/includes/class-tns-public.php';

		/**
		 * Functions and definitions
		 */
		require_once get_template_directory() . '/includes/functions.php';

		/**
		 * The file theme framework
		 */
		require_once get_template_directory() . '/includes/framework.php';

		$this->loader = new Tns_Loader();
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the theme.
	 */
	private function define_admin_hooks(){

		$theme_admin = new Tns_Admin($this->get_theme_name(), $this->get_version());

		$this->loader->add_action('admin_enqueue_scripts', $theme_admin, 'enqueue_styles');
		$this->loader->add_action('admin_menu', $theme_admin, 'disable_dashboard_widgets');
		$this->loader->add_action('wp_before_admin_bar_render', $theme_admin,
			'remove_admin_bar_logo', 0);
		$this->loader->add_action('admin_bar_menu', $theme_admin, 'admin_bar_logo');
		$this->loader->add_action('admin_head', $theme_admin, 'admin_favicon');
		$this->loader->add_action('admin_menu', $theme_admin, 'add_admin_menu', 9);
		$this->loader->add_action('admin_init', $theme_admin, 'register_and_build_smtp_fields');
		$this->loader->add_action('admin_init', $theme_admin,
			'register_and_build_general_settings_fields');
		$this->loader->add_action('phpmailer_init', $theme_admin, 'phpmailer_init', 999);
		$this->loader->add_action('init', $theme_admin, 'create_webmaster_role');
		$this->loader->add_action('admin_menu', $theme_admin, 'change_menus_position');
		$this->loader->add_action('admin_head', $theme_admin, 'hide_webmaster');
		$this->loader->add_action('admin_init', $theme_admin, 'block_user_admin_pages');
		$this->loader->add_filter('admin_footer_text', $theme_admin, 'footer_text', 11);
		$this->loader->add_filter('update_footer', $theme_admin, 'footer_update', 11);
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the theme.
	 */
	private function define_public_hooks(){

		$theme_public = new Tns_Public($this->get_theme_name(), $this->get_version());

		$this->loader->add_action('login_head', $theme_public, 'login_favicon');
		$this->loader->add_action('login_enqueue_scripts', $theme_public, 'login_enqueue_styles');
		$this->loader->add_action('login_headertext', $theme_public, 'login_logo_title');
		$this->loader->add_action('login_headerurl', $theme_public, 'login_logo_url');
		$this->loader->add_action('after_setup_theme', $theme_public, 'theme_supports');
		$this->loader->add_action('after_setup_theme', $theme_public, 'tns_custom_logo');
		$this->loader->add_action('after_setup_theme', $theme_public, 'tns_register_nav_menus');
		$this->loader->add_action('wp_enqueue_scripts', $theme_public, 'theme_styles');
		$this->loader->add_action('wp_head', $theme_public, 'google_analytics');
		$this->loader->add_action('wp_body_open', $theme_public, 'facebook_sdk');
		$this->loader->add_action('wp_body_open', $theme_public, 'messenger_plugin_chat');
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 */
	public function run(){
		$this->loader->run();
	}

	/**
	 * The name of the theme used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 */
	public function get_theme_name(){
		return $this->theme_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the theme.
	 */
	public function get_loader(){
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the theme.
	 */
	public function get_version(){
		return $this->version;
	}

	/**
	 * Check to see if the current page is the login/register page.
	 */
	public function is_login_page(){
		return in_array(
			$GLOBALS['pagenow'],
			['wp-login.php', 'wp-register.php'],
			TRUE
		);
	}
}
