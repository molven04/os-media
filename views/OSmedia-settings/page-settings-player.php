<?php // var_dump ($arr_skin); ?>

<div class="wrap">

	<div id="icon-options-general" class="icon32"><br /></div>
	<h1><?php esc_html_e( OSmedia_NAME ); ?> default settings</h1>

	<form id="options_form" method="post" action="options.php">

		<?php settings_fields('OSmedia_settings_player'); ?>
		<?php do_settings_sections('OSmedia_settings_player'); ?>

		<p class="submit">
			<input id="save_options" name="save_options" type="submit" class="button-primary" value="<?php esc_attr_e( 'Save Changes' ); ?>" />
		</p>

	</form>
<!--
	<form  id="reset_options_player" class="reset_form" method="POST">
		<input id="reset_options_player_btn"  name="reset_options_player_btn" style="margin:0 20px 0 0" class="button-secondary reset_btn" type="submit" value="Reset to Default Value" />
	</form>
-->

</div>
<!-- .wrap -->
