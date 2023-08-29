<div class="wrap">
	<h1>Cài đặt tổng quan</h1>
	<form method="post" action="options.php" novalidate="novalidate">
		<?php
		settings_fields('tns_general_settings');
		do_settings_sections('tns_general_settings');
		submit_button();
		?>
	</form>
</div>
