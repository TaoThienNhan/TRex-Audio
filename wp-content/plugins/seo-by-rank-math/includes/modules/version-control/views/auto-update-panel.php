<?php
/**
 * Auto Updater view in Version Control Tab.
 *
 * @package    RankMath
 * @subpackage RankMath\Version_Control
 */

defined( 'ABSPATH' ) || exit;

?>
<form id="cmb2-metabox-rank-math-auto-update" class="rank-math-auto-update-form cmb2-form rank-math-box" action="" method="post">

	<header>
		<h3><?php esc_html_e( 'Auto Update', 'rank-math' ); ?></h3>
	</header>

	<p><?php esc_html_e( 'Turn on auto-updates to automatically update to stable versions of Rank Math as soon as they are released. The beta versions will never install automatically.', 'rank-math' ); ?></p>

	<table class="form-table">
		<tbody>
			<tr class="cmb-row cmb-type-switch">
				<th scope="row"><label><?php esc_html_e( 'Auto Update Plugin', 'rank-math' ); ?></label></th>
				<td>
					<ul class="cmb2-radio-list cmb2-list">
						<li>
							<input type="radio" class="cmb2-option" name="enable_auto_update" id="enable_auto_update1" value="off" <?php checked( ! $auto_update ); ?>>
							<label for="enable_auto_update1"><?php esc_html_e( 'Off', 'rank-math' ); ?></label>
						</li>
						<li>
							<input type="radio" class="cmb2-option" name="enable_auto_update" id="enable_auto_update2" value="on" <?php checked( $auto_update ); ?>>
							<label for="enable_auto_update2"><?php esc_html_e( 'On', 'rank-math' ); ?></label>
						</li>
					</ul>
				</td>
			</tr>
		</tbody>
	</table>

	<div id="control_update_notification_email">
		<p><?php esc_html_e( 'When auto-updates are turned off, you can enable update notifications, to send an email to the site administrator when an update is available for Rank Math.', 'rank-math' ); ?></p>

		<table class="form-table">
			<tbody>
				<tr class="cmb-row cmb-type-switch">
					<th scope="row"><label><?php esc_html_e( 'Update Notification Email', 'rank-math' ); ?></label></th>
					<td>
						<ul class="cmb2-radio-list cmb2-list">
							<li>
								<input type="radio" class="cmb2-option" name="enable_update_notification_email" id="enable_update_notification_email1" value="off" <?php checked( ! $update_notification ); ?>>
								<label for="enable_update_notification_email1"><?php esc_html_e( 'Off', 'rank-math' ); ?></label>
							</li>
							<li>
								<input type="radio" class="cmb2-option" name="enable_update_notification_email" id="enable_update_notification_email2" value="on" <?php checked( $update_notification ); ?>>
								<label for="enable_update_notification_email2"><?php esc_html_e( 'On', 'rank-math' ); ?></label>
							</li>
						</ul>
					</td>
				</tr>
			</tbody>
		</table>
	</div>

	<?php if ( get_option( 'rank_math_rollback_version', false ) ) { ?>
		<div class="notice notice-alt notice-warning info inline" style="border: none;">
			<p>
				<?php esc_html_e( 'Rank Math will not auto-update because you have rolled back to a previous version. Update to the latest version manually to make this option work again.', 'rank-math' ); ?>
			</p>
		</div>
	<?php } ?>

	<footer>
		<?php wp_nonce_field( 'rank-math-auto-update' ); ?>
		<button type="submit" class="button button-primary button-xlarge"><?php esc_html_e( 'Save Changes', 'rank-math' ); ?></button>
	</footer>

</form>
