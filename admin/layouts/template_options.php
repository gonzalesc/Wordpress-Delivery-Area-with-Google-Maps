<div class="wrap">
	<h2><?php _e('Area Delivery Settings','letsgo'); ?></h2>
	<form method="post" action="options.php">
	<?php
		// This prints out all hidden setting fields
		settings_fields( 'groupareamaps' );
		do_settings_sections( 'settingareamaps' );
		submit_button(); 
	?>
	</form>
</div>