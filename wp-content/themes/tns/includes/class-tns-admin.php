<?php

/**
 * The admin-specific functionality of the theme.
 */

class Tns_Admin {

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

		/**
		 * Remove WordPress welcome panel.
		 */
		remove_action('welcome_panel', 'wp_welcome_panel');

	}

	/**
	 * Register the stylesheets for the admin area.
	 */
	public function enqueue_styles(){
		wp_enqueue_style($this->theme_name, get_theme_file_uri('css/white-label.css'), [],
			$this->version, 'all');
	}

	/**
	 * Replace WordPress thank you
	 */
	public function footer_text(){
		echo '<span id="footer-thankyou">Cảm ơn bạn đã sử dụng dịch vụ của <a href="https://taynamsolution.vn/" target="_blank">Tây Nam Solutions</a></span>';
	}

	/**
	 * Replace WordPress version
	 */
	public function footer_update(){
		echo '<span>Phiên bản ' . $this->version . '</span>';
	}

	/**
	 * Disable dashboard widgets if the user is not an administrator
	 */
	public function disable_dashboard_widgets(){
		if (!current_user_can('administrator')){
			remove_meta_box('dashboard_quick_press', 'dashboard', 'side');
			remove_meta_box('dashboard_primary', 'dashboard', 'core');
			remove_meta_box('dashboard_site_health', 'dashboard', 'normal');
		}
	}

	/**
	 * Remove WordPress default logo
	 */
	public function remove_admin_bar_logo(){
		global $wp_admin_bar;
		$wp_admin_bar->remove_menu('wp-logo');
	}

	/**
	 * Replace the default WordPress logo with Tay Nam Solutions logo
	 *
	 * @param $wp_admin_bar
	 */
	public function admin_bar_logo($wp_admin_bar){

		$admin_menu_bar_url      = 'https://taynamsolution.vn';
		$admin_menu_bar_alt_text = 'Tây Nam Solutions';
		$admin_menu_bar_image    = get_theme_file_uri('images/logo-tay-nam.png');

		if (!$admin_menu_bar_image){
			return;
		}

		/**
		 * Add custom logo to the admin bar menu
		 */
		$args = [
			'id'    => 'tns-admin-logo',
			'href'  => $admin_menu_bar_url,
			'title' => sprintf('<img src="%s" />', $admin_menu_bar_image),
			'meta'  => ['class' => 'tns-admin-logo', 'title' => $admin_menu_bar_alt_text, 'target' => '_blank']
		];
		$wp_admin_bar->add_node($args);

	}

	/**
	 * Display favicon for the admin area
	 */
	public function admin_favicon(){

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
	 * SMTP config
	 */
	function phpmailer_init($phpmailer){

		//check smtp enable
		if (intval(get_option('smtp_enable')) == FALSE):
			return;
		endif;

		$phpmailer->isSMTP();
		$phpmailer->Host       = get_option('smtp_host');
		$phpmailer->Port       = get_option('smtp_port');
		$phpmailer->SMTPSecure = get_option('smtp_secure');
		$phpmailer->SMTPAuth   = intval(get_option('smtp_auth'));
		$phpmailer->Username   = get_option('smtp_username');
		$phpmailer->Password   = get_option('smtp_password');
		$phpmailer->From       = get_option('smtp_from');
		$phpmailer->FromName   = get_option('smtp_from_name');
		$phpmailer->SetFrom($phpmailer->From, $phpmailer->FromName);
		$phpmailer->addReplyTo(get_option('smtp_reply'), get_option('smtp_reply_name'));
		$phpmailer->Timeout = 10;
	}

	/**
	 * Add admin menu
	 */
	public function add_admin_menu(){
		//add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
		add_menu_page('Tây Nam Solutions', 'Tây Nam Solutions', 'manage_options', $this->theme_name,
			[$this, 'general_settings'], get_theme_file_uri('images/icon.png'), 26);

		//add_submenu_page( '$parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function );
		add_submenu_page($this->theme_name, 'Cấu hình SMTP', 'SMTP', 'manage_options',
			$this->theme_name . '-smtp', [$this, 'display_smtp_config']);
	}

	/**
	 * Display general settings
	 */
	public function general_settings(){
		require_once get_template_directory() . '/partials/general-settings.php';
	}

	/**
	 * Register general settings fields
	 */
	public function register_and_build_general_settings_fields(){
		add_settings_section(
			'tns_general_settings_section',
			'',
			[$this, 'display_general_settings'],
			'tns_general_settings'
		);

		unset($args);

		add_settings_field(
			'google_analytics_id',
			'Google Analytics',
			[$this, 'render_settings_field'],
			'tns_general_settings',
			'tns_general_settings_section',
			[
				'type'             => 'input',
				'subtype'          => 'text',
				'id'               => 'google_analytics_id',
				'name'             => 'google_analytics_id',
				'required'         => '',
				'get_options_list' => '',
				'value_type'       => 'normal',
				'wp_data'          => 'option'
			]
		);

		// register Google Analytics
		register_setting(
			'tns_general_settings',
			'google_analytics_id'
		);

		add_settings_field(
			'facebook_sdk',
			'Facebook SDK',
			[$this, 'render_settings_field'],
			'tns_general_settings',
			'tns_general_settings_section',
			[
				'type'             => 'textarea',
				'subtype'          => 'text',
				'id'               => 'facebook_sdk',
				'name'             => 'facebook_sdk',
				'required'         => '',
				'get_options_list' => '',
				'rows'             => '5',
				'cols'             => '75',
				'value_type'       => 'normal',
				'wp_data'          => 'option'
			]
		);

		// register Google Analytics
		register_setting(
			'tns_general_settings',
			'facebook_sdk'
		);

		add_settings_field(
			'messenger_plugin_chat_code',
			'Messenger Plugin chat Code',
			[$this, 'render_settings_field'],
			'tns_general_settings',
			'tns_general_settings_section',
			[
				'type'             => 'textarea',
				'subtype'          => 'text',
				'id'               => 'messenger_plugin_chat_code',
				'name'             => 'messenger_plugin_chat_code',
				'required'         => '',
				'get_options_list' => '',
				'rows'             => '35',
				'cols'             => '75',
				'value_type'       => 'normal',
				'wp_data'          => 'option'
			]
		);

		// register Google Analytics
		register_setting(
			'tns_general_settings',
			'messenger_plugin_chat_code'
		);
	}

	/**
	 * Register general settings
	 */
	public function display_general_settings(){
		echo '<p>Cài đặt logo và favicon <a href="' . admin_url('/customize.php?autofocus[section]=title_tagline') . '" target="_blank">tại đây.</a></p>';
	}

	/**
	 * Display SMTP config
	 */
	public function display_smtp_config(){
		require_once get_template_directory() . '/partials/smtp-config.php';
	}

	/**
	 * Register SMTP config fields
	 */
	public function register_and_build_smtp_fields(){

		add_settings_section(
			'tns_smtp_config_section',
			'',
			'',
			'tns_smtp_config'
		);

		unset($args);

		// add smtp enable field
		add_settings_field(
			'smtp_enable',
			'Enable',
			[$this, 'render_settings_field'],
			'tns_smtp_config',
			'tns_smtp_config_section',
			[
				'type'             => 'input',
				'subtype'          => 'checkbox',
				'id'               => 'smtp_enable',
				'name'             => 'smtp_enable',
				'required'         => '',
				'get_options_list' => '',
				'value_type'       => 'normal',
				'wp_data'          => 'option'
			]
		);

		// register smtp enable
		register_setting(
			'tns_smtp_config',
			'smtp_enable'
		);

		// add smtp host field
		add_settings_field(
			'smtp_host',
			'Host',
			[$this, 'render_settings_field'],
			'tns_smtp_config',
			'tns_smtp_config_section',
			[
				'type'             => 'input',
				'subtype'          => 'text',
				'id'               => 'smtp_host',
				'name'             => 'smtp_host',
				'required'         => '',
				'get_options_list' => '',
				'value_type'       => 'normal',
				'wp_data'          => 'option'
			]
		);

		// register smtp host
		register_setting(
			'tns_smtp_config',
			'smtp_host'
		);

		// add smtp port field
		add_settings_field(
			'smtp_port',
			'Port',
			[$this, 'render_settings_field'],
			'tns_smtp_config',
			'tns_smtp_config_section',
			[
				'type'             => 'input',
				'subtype'          => 'text',
				'id'               => 'smtp_port',
				'name'             => 'smtp_port',
				'required'         => '',
				'get_options_list' => '',
				'value_type'       => 'normal',
				'wp_data'          => 'option'
			]
		);

		// register smtp port
		register_setting(
			'tns_smtp_config',
			'smtp_port'
		);

		// add smtp secure field
		add_settings_field(
			'smtp_secure',
			'Secure',
			[$this, 'render_settings_field'],
			'tns_smtp_config',
			'tns_smtp_config_section',
			[
				'type'             => 'input',
				'subtype'          => 'text',
				'id'               => 'smtp_secure',
				'name'             => 'smtp_secure',
				'required'         => '',
				'get_options_list' => '',
				'value_type'       => 'normal',
				'wp_data'          => 'option'
			]
		);

		// register smtp secure
		register_setting(
			'tns_smtp_config',
			'smtp_secure'
		);

		// add smtp auth field
		add_settings_field(
			'smtp_auth',
			'Auth',
			[$this, 'render_settings_field'],
			'tns_smtp_config',
			'tns_smtp_config_section',
			[
				'type'             => 'input',
				'subtype'          => 'checkbox',
				'id'               => 'smtp_auth',
				'name'             => 'smtp_auth',
				'required'         => '',
				'get_options_list' => '',
				'value_type'       => 'normal',
				'wp_data'          => 'option'
			]
		);

		// register smtp auth
		register_setting(
			'tns_smtp_config',
			'smtp_auth'
		);

		// add smtp username field
		add_settings_field(
			'smtp_username',
			'Username',
			[$this, 'render_settings_field'],
			'tns_smtp_config',
			'tns_smtp_config_section',
			[
				'type'             => 'input',
				'subtype'          => 'text',
				'id'               => 'smtp_username',
				'name'             => 'smtp_username',
				'required'         => '',
				'get_options_list' => '',
				'value_type'       => 'normal',
				'wp_data'          => 'option'
			]
		);

		// register smtp username
		register_setting(
			'tns_smtp_config',
			'smtp_username'
		);

		// add smtp password field
		add_settings_field(
			'smtp_password',
			'Password',
			[$this, 'render_settings_field'],
			'tns_smtp_config',
			'tns_smtp_config_section',
			[
				'type'             => 'input',
				'subtype'          => 'password',
				'id'               => 'smtp_password',
				'name'             => 'smtp_password',
				'required'         => '',
				'get_options_list' => '',
				'value_type'       => 'normal',
				'wp_data'          => 'option'
			]
		);

		// register smtp password
		register_setting(
			'tns_smtp_config',
			'smtp_password'
		);

		// add smtp from field
		add_settings_field(
			'smtp_from',
			'From',
			[$this, 'render_settings_field'],
			'tns_smtp_config',
			'tns_smtp_config_section',
			[
				'type'             => 'input',
				'subtype'          => 'text',
				'id'               => 'smtp_from',
				'name'             => 'smtp_from',
				'required'         => '',
				'get_options_list' => '',
				'value_type'       => 'normal',
				'wp_data'          => 'option'
			]
		);

		// register smtp from
		register_setting(
			'tns_smtp_config',
			'smtp_from'
		);

		// add smtp from name field
		add_settings_field(
			'smtp_from_name',
			'From name',
			[$this, 'render_settings_field'],
			'tns_smtp_config',
			'tns_smtp_config_section',
			[
				'type'             => 'input',
				'subtype'          => 'text',
				'id'               => 'smtp_from_name',
				'name'             => 'smtp_from_name',
				'required'         => '',
				'get_options_list' => '',
				'value_type'       => 'normal',
				'wp_data'          => 'option'
			]
		);

		// register smtp from name
		register_setting(
			'tns_smtp_config',
			'smtp_from_name'
		);

		// add smtp reply field
		add_settings_field(
			'smtp_reply',
			'Reply',
			[$this, 'render_settings_field'],
			'tns_smtp_config',
			'tns_smtp_config_section',
			[
				'type'             => 'input',
				'subtype'          => 'text',
				'id'               => 'smtp_reply',
				'name'             => 'smtp_reply',
				'required'         => '',
				'get_options_list' => '',
				'value_type'       => 'normal',
				'wp_data'          => 'option'
			]
		);

		// register smtp reply
		register_setting(
			'tns_smtp_config',
			'smtp_reply'
		);

		// add smtp reply name field
		add_settings_field(
			'smtp_reply_name',
			'Reply name',
			[$this, 'render_settings_field'],
			'tns_smtp_config',
			'tns_smtp_config_section',
			[
				'type'             => 'input',
				'subtype'          => 'text',
				'id'               => 'smtp_reply_name',
				'name'             => 'smtp_reply_name',
				'required'         => 'true',
				'get_options_list' => '',
				'value_type'       => 'normal',
				'wp_data'          => 'option'
			]
		);

		// register smtp reply name
		register_setting(
			'tns_smtp_config',
			'smtp_reply_name'
		);
	}

	/**
	 * Render settings field
	 */
	public function setting_textarea_fn(){
		$options = get_option('plugin_options');
		echo "<textarea id='plugin_textarea_string' name='plugin_options[text_area]' rows='7' cols='50' type='textarea'>{$options['text_area']}</textarea>";
	}

	/**
	 * Render settings field
	 */
	public function render_settings_field($args){
		if ($args['wp_data'] == 'option'){
			$wp_data_value = get_option($args['name']);
		}elseif ($args['wp_data'] == 'post_meta'){
			$wp_data_value = get_post_meta($args['post_id'], $args['name'], TRUE);
		}
		switch ($args['type']){
			case 'input':
				$value = ($args['value_type'] == 'serialized') ? serialize($wp_data_value) : $wp_data_value;
				if ($args['subtype'] != 'checkbox'){
					$prependStart = (isset($args['prepend_value'])) ? '<div class="input-prepend"> <span class="add-on">' . $args['prepend_value'] . '</span>' : '';
					$prependEnd   = (isset($args['prepend_value'])) ? '</div>' : '';
					$step         = (isset($args['step'])) ? 'step="' . $args['step'] . '"' : '';
					$min          = (isset($args['min'])) ? 'min="' . $args['min'] . '"' : '';
					$max          = (isset($args['max'])) ? 'max="' . $args['max'] . '"' : '';
					if (isset($args['disabled'])){
						echo $prependStart . '<input type="' . $args['subtype'] . '" id="' . $args['id'] . '_disabled" ' . $step . ' ' . $max . ' ' . $min . ' name="' . $args['name'] . '_disabled" size="40" disabled value="' . esc_attr($value) . '" /><input type="hidden" id="' . $args['id'] . '" ' . $step . ' ' . $max . ' ' . $min . ' name="' . $args['name'] . '" size="40" value="' . esc_attr($value) . '" />' . $prependEnd;
					}else{
						echo $prependStart . '<input type="' . $args['subtype'] . '" id="' . $args['id'] . '" "' . $args['required'] . '" ' . $step . ' ' . $max . ' ' . $min . ' name="' . $args['name'] . '" size="40" value="' . esc_attr($value) . '" />' . $prependEnd;
					}
				}else{
					$checked = ($value) ? 'checked' : '';
					echo '<input type="' . $args['subtype'] . '" id="' . $args['id'] . '" "' . $args['required'] . '" name="' . $args['name'] . '" size="40" value="1" ' . $checked . ' />';
				}
				break;
			case 'textarea':
				$value = ($args['value_type'] == 'serialized') ? serialize($wp_data_value) : $wp_data_value;
				echo '<textarea id="' . $args['id'] . '" name="' . $args['name'] . '" rows="' . $args['rows'] . '" cols="' . $args['cols'] . '" type="textarea">' . esc_attr($value) . '</textarea>';
				break;
			default:
				# code...
				break;
		}
	}

	/**
	 * Create roles and capabilities.
	 */
	public function create_webmaster_role(){

		global $wp_roles;

		if (!class_exists('WP_Roles')):
			return;
		endif;

		if (!isset($wp_roles)):
			$wp_roles = new WP_Roles();
		endif;

		$administrator_roles = $wp_roles->get_role('administrator');

		// Webmaster role.
		$wp_roles->add_role('webmaster', 'Webmaster', $administrator_roles->capabilities);

		$capabilities = self::get_woocommerce_capabilities();

		foreach ($capabilities as $cap_group){
			foreach ($cap_group as $cap){
				$wp_roles->add_cap('webmaster', $cap);
			}
		}
	}

	/**
	 * Get capabilities for WooCommerce
	 */
	private static function get_woocommerce_capabilities(){
		$capabilities = [];

		$capabilities['core'] = [
			'manage_woocommerce',
			'view_woocommerce_reports',
		];

		$capability_types = ['product', 'shop_order', 'shop_coupon'];

		foreach ($capability_types as $capability_type){

			$capabilities[$capability_type] = [
				// Post type.
				"edit_{$capability_type}",
				"read_{$capability_type}",
				"delete_{$capability_type}",
				"edit_{$capability_type}s",
				"edit_others_{$capability_type}s",
				"publish_{$capability_type}s",
				"read_private_{$capability_type}s",
				"delete_{$capability_type}s",
				"delete_private_{$capability_type}s",
				"delete_published_{$capability_type}s",
				"delete_others_{$capability_type}s",
				"edit_private_{$capability_type}s",
				"edit_published_{$capability_type}s",

				// Terms.
				"manage_{$capability_type}_terms",
				"edit_{$capability_type}_terms",
				"delete_{$capability_type}_terms",
				"assign_{$capability_type}_terms",
			];
		}

		return $capabilities;
	}

	/**
	 * Change menus position
	 */
	function change_menus_position(){

		// Remove old menu
		remove_submenu_page('themes.php', 'nav-menus.php');

		//Add new menu page
		add_submenu_page(
			'tns',
			'Menu',
			'Menu',
			'edit_theme_options',
			'nav-menus.php',
			'',
			10
		);
	}

	/**
	 * Hide admin menu
	 */
	function hide_webmaster(){
		if (current_user_can('webmaster')):
			remove_menu_page('themes.php');
			remove_menu_page('plugins.php');
			remove_menu_page('tools.php');
			remove_menu_page('edit.php?post_type=acf-field-group');
			remove_submenu_page('index.php', 'update-core.php');
			remove_submenu_page('options-general.php', 'options-permalink.php');
			remove_submenu_page('options-general.php', 'options-writing.php');
			remove_submenu_page('options-general.php', 'options-media.php');
		endif;
	}

	/**
	 * Block admin page
	 */
	function block_user_admin_pages(){
		global $typenow;
		if (!current_user_can('administrator') && $typenow == 'acf-field-group'):
			$message = 'Bạn không có đủ quyền để truy cập trang này.';
			wp_die($message);
		endif;
	}
}
