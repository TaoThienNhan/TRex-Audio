<?php

/**
 * The public-facing functionality of the theme.
 */

class Tns_Public {

	/**
	 * The ID of this theme.
	 */
	private $theme_name;

	/**
	 * The version of this theme.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 */
	public function __construct($theme_name, $version){

		$this->theme_name = $theme_name;
		$this->version    = $version;
	}

	/**
	 * Display favicon for the login page
	 */
	public function login_favicon(){

		$favicon_32  = get_theme_file_uri('images/cropped-favicon-32x32.png');
		$favicon_180 = get_theme_file_uri('images/cropped-favicon-180x180.png');
		$favicon_192 = get_theme_file_uri('images/cropped-favicon-192x192.png');
		$favicon_270 = get_theme_file_uri('images/cropped-favicon-270x270.png');

		echo '<link rel="icon" href="' . $favicon_32 . '" sizes="32x32"/>' . "\n"
		     . '<link rel="icon" href="' . $favicon_192 . '" sizes="192x192"/>' . "\n"
		     . '<link rel="apple-touch-icon" href="' . $favicon_180 . '"/>' . "\n"
		     . '<meta name="msapplication-TileImage" content="' . $favicon_270 . '"/>' . "\n";
	}

	/**
	 * Register the stylesheets for the login page.
	 */
	public function login_enqueue_styles(){
		wp_enqueue_style($this->theme_name, get_theme_file_uri('css/login.css'), [], $this->version,
			'all');
	}

	/**
	 * Register the stylesheets for the theme.
	 */
	public function theme_styles(){
		wp_enqueue_style($this->theme_name, get_stylesheet_uri(), [], $this->version, 'all');
	}

	/**
	 * Replace WordPress login logo title
	 */
	public function login_logo_title(){
		return 'Tây Nam Solutions';
	}

	/**
	 * Replace WordPress login logo url
	 */
	public function login_logo_url(){
		return 'https://taynamsolution.vn';
	}

	/**
	 * Add theme supports
	 */
	public function theme_supports(){
		add_theme_support('menus');
		add_theme_support('post-thumbnails');
		add_theme_support('title-tag');
		add_theme_support('automatic-feed-links');
		add_theme_support('body-open');
		if (class_exists('WooCommerce')):
			add_theme_support('woocommerce');
		endif;
	}

	/**
	 * Register the custom menu locations, if theme has support for them.
	 */
	function tns_register_nav_menus(){

		if (!current_theme_supports('tns-menus')){
			return;
		}

		$menus = get_theme_support('tns-menus');

		register_nav_menus((array) $menus[0]);

	}

	/**
	 * Add support for the WordPress custom logo feature.
	 */
	function tns_custom_logo(){

		$wp_custom_logo = get_theme_support('custom-logo');
		if ($wp_custom_logo){
			return;
		}

		$tns_custom_logo = get_theme_support('tns-custom-logo');
		if (!$tns_custom_logo){
			return;
		}

		$tns_custom_logo = isset($tns_custom_logo[0]) && is_array($tns_custom_logo[0]) ? $tns_custom_logo[0] : [];

		add_theme_support(
			'custom-logo',
			[
				'height'      => $tns_custom_logo['height'],
				'width'       => $tns_custom_logo['width'],
				'flex-height' => $tns_custom_logo['flex-height'],
				'flex-width'  => $tns_custom_logo['flex-height'],
			]
		);
	}

	/**
	 * Add Google Analytics
	 */
	public function google_analytics(){
		if (empty(get_option('google_analytics_id')) || get_option('google_analytics_id') == ''):
			return;
		endif; ?>
		<!-- Global site tag (gtag.js) - Google Analytics -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=<?= get_option('google_analytics_id') ?>"></script>
		<script>
			window.dataLayer = window.dataLayer || [];

			function gtag() {
				dataLayer.push(arguments);
			}

			gtag('js', new Date());

			gtag('config', '<?= get_option('google_analytics_id') ?>');
		</script>
		<?php
	}

	/**
	 * Add Facebook SDK
	 */
	public function facebook_sdk(){
		if (empty(get_option('facebook_sdk')) || get_option('facebook_sdk') == ''):
			return;
		endif;
		print htmlspecialchars_decode(get_option('facebook_sdk'));
	}

	/**
	 * Add Messenger Plugin chat
	 */
	public function messenger_plugin_chat(){
		if (empty(get_option('messenger_plugin_chat_code')) || get_option('messenger_plugin_chat_code') == ''):
			return;
		endif;
		print htmlspecialchars_decode(get_option('messenger_plugin_chat_code'));
	}
}
