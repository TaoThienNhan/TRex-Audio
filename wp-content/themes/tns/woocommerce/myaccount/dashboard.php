<?php
/**
 * My Account Dashboard
 *
 * Shows the first intro screen on the account dashboard.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/dashboard.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.4.0
 */

if (!defined('ABSPATH')){
	exit; // Exit if accessed directly.
}

$allowed_html = [
	'a' => [
		'href' => [],
	],
];
?>

	<p>
		<?php
		printf(
		/* translators: 1: user display name 2: logout url */
			wp_kses(__('Hello %1$s (not %1$s? <a href="%2$s">Log out</a>)', 'woocommerce'),
				$allowed_html),
			'<strong>' . esc_html($current_user->display_name) . '</strong>',
			esc_url(wc_logout_url())
		);
		?>
	</p>

	<div class="row">
		<div class="col-lg-4">
			<a href="<?= esc_url(wc_get_endpoint_url('orders')) ?>" class="dashboard-link">
				<i class="fa fa-shopping-bag" aria-hidden="true"></i>
				<span>Đơn hàng</span>
			</a>
		</div>
		<div class="col-lg-4">
			<a href="<?= esc_url(wc_get_endpoint_url('edit-address')) ?>" class="dashboard-link">
				<i class="fa fa-map-marker" aria-hidden="true"></i>
				<span>Địa chỉ</span>
			</a>
		</div>
		<div class="col-lg-4">
			<a href="<?= esc_url(wc_get_endpoint_url('edit-account')) ?>" class="dashboard-link">
				<i class="fa fa-user" aria-hidden="true"></i>
				<span>Tài khoản</span>
			</a>
		</div>
	</div>

<?php
/**
 * My Account dashboard.
 *
 * @since 2.6.0
 */
do_action('woocommerce_account_dashboard');

/**
 * Deprecated woocommerce_before_my_account action.
 *
 * @deprecated 2.6.0
 */
do_action('woocommerce_before_my_account');

/**
 * Deprecated woocommerce_after_my_account action.
 *
 * @deprecated 2.6.0
 */
do_action('woocommerce_after_my_account');

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */
