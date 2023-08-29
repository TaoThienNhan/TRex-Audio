<div class="wrap">
	<h1>Cấu hình SMTP</h1>
	<form method="POST" action="options.php" novalidate="novalidate">
		<?php
		settings_fields('tns_smtp_config');
		do_settings_sections('tns_smtp_config');
		submit_button();
		?>
	</form>
</div>
