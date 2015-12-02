<?php
// Openstream.it: OSmedia Wordpress Plugin -> HTML field list for admin area

/*
 * Basic Section General Configs
*/

$general_config = '<b>general config</b> - not present in single-post settings';
$default_set = 'default setting - single-post settings overwrite this';
?>

<?php // settings_fields( 'prova' ); ?>

<?php ///////////////////////////////////////// Basic Section General Configs //////////////////////////////////////////////////////////////7 ?>
<?php if ( 'OSmedia_path' == $field['label_for'] ) : ?>
	<input id="OSmedia_settings[OSmedia_path]" placeholder="path for video folder" size="50" name="OSmedia_settings[OSmedia_path]" value="<?php echo $settings['OSmedia_path'] ?>" /> 
	<span class="description"> [recommended] default: <b style="background-color: white">"<?php echo ABSPATH. 'wp-content/uploads' ?>"</b></span>
<?php endif ?>

<?php if ( 'OSmedia_url' == $field['label_for'] ) : ?>
	<input id="OSmedia_settings[OSmedia_url]" placeholder="media server URL" size="50" name="OSmedia_settings[OSmedia_url]" value="<?php echo $settings['OSmedia_url'] ?>" /> 
	<span class="description"> example: "http://example.com/wp-content/uploads/video/"</span>
<?php endif ?>

<?php if ( 'OSmedia_shortcode' == $field['label_for'] ) : ?>
	<input id="OSmedia_settings[OSmedia_shortcode]" name="OSmedia_settings[OSmedia_shortcode]" size="10" placeholder="video shortcode" value="<?php echo $settings['OSmedia_shortcode'] ?>" /> 
<?php endif ?>

<?php if ( 'OSmedia_autoplay' == $field['label_for'] ) : // var_dump($settings); ?>
	<input <?php checked( $settings['OSmedia_autoplay'], 'on' ); ?> id="OSmedia_autoplay" name="OSmedia_settings[OSmedia_autoplay]" type="checkbox" />
	<span class="description"> <?php echo $default_set ?></span>
<?php endif; ?>

<?php if ( 'OSmedia_loop' == $field['label_for'] ) : ?>
	<input <?php checked( $settings['OSmedia_loop'], 'on' ); ?> id="OSmedia_loop" name="OSmedia_settings[OSmedia_loop]" type="checkbox" />
	<span class="description"> <?php echo $default_set ?></span>
<?php endif; ?>

<?php if ( 'OSmedia_preload' == $field['label_for'] ) : ?>
	<input <?php checked( $settings['OSmedia_preload'], 'on' ); ?> id="OSmedia_preload" name="OSmedia_settings[OSmedia_preload]" type="checkbox" />
	<span class="description"> <?php echo $default_set ?></span>
<?php endif; ?>

<!--
<?php // if ( 'OSmedia_player' == $field['label_for'] ) : ?>
	<select name="OSmedia_settings[OSmedia_player]">
		<option value="videojs" <?php // if($settings['OSmedia_player'] == 'videojs') echo 'selected="selected "'; ?>>videojs</option>
		<option value="mediaelement" <?php // if($settings['OSmedia_player'] == 'mediaelement') echo 'selected="selected "'; ?>>mediaelement</option>
	</select>
	<span class="description"> [defaul: videojs] NOTE: mediaelement loses responsive property</span>
<?php // endif; ?>
-->

<?php if ( 'OSmedia_fallback1' == $field['label_for'] ) : ?>
	<input <?php checked( $settings['OSmedia_fallback1'], 'on' ); ?> id="OSmedia_fallback1" name="OSmedia_settings[OSmedia_fallback1]" type="checkbox" />
	<span class="example"> Youtube/Vimeo priority over HTML5 <i>[<b>general config</b> not present in single-post settings]</i></span>
<?php endif; ?>

<?php if ( 'OSmedia_fallback2' == $field['label_for'] ) : ?>
	<input <?php checked( $settings['OSmedia_fallback2'], 'on' ); ?> id="OSmedia_fallback2" name="OSmedia_settings[OSmedia_fallback2]" type="checkbox" />
	<span class="example"> Vimeo priority over Youtube <i>[<b>general config</b> not present in single-post settings]</i></span>
<?php endif; ?>


<?php ///////////////////////////////////////// Advanced Section Youtube //////////////////////////////////////////////////////////////7 ?>
<?php if ( 'OSmedia_yt_vjs' == $field['label_for'] ) : ?>
	<input <?php checked( $settings['OSmedia_yt_vjs'], 'on' ); ?> id="OSmedia_yt_vjs" name="OSmedia_settings[OSmedia_yt_vjs]" type="checkbox" />
	<span class="description"> <?php echo $general_config ?></span>
<?php endif; ?>
<?php if ( 'OSmedia_yt_html5' == $field['label_for'] ) : ?>
	<input <?php checked( $settings['OSmedia_yt_html5'], 'on' ); ?> id="OSmedia_yt_html5" name="OSmedia_settings[OSmedia_yt_html5]" type="checkbox" />
	<span class="description"> <?php echo $general_config ?></span>
<?php endif; ?>
<?php if ( 'OSmedia_yt_logo' == $field['label_for'] ) : ?>
	<input <?php checked( $settings['OSmedia_yt_logo'], 'on' ); ?> id="OSmedia_yt_logo" name="OSmedia_settings[OSmedia_yt_logo]" type="checkbox" />
	<span class="description"> <?php echo $general_config ?></span>
<?php endif; ?>
<?php if ( 'OSmedia_yt_https' == $field['label_for'] ) : ?>
	<input <?php checked( $settings['OSmedia_yt_https'], 'on' ); ?> id="OSmedia_yt_https" name="OSmedia_settings[OSmedia_yt_https]" type="checkbox" />
	<span class="description"> <?php echo $default_set ?></span>
<?php endif; ?>
<?php if ( 'OSmedia_yt_info' == $field['label_for'] ) : ?>
	<input <?php checked( $settings['OSmedia_yt_info'], 'on' ); ?> id="OSmedia_yt_info" name="OSmedia_settings[OSmedia_yt_info]" type="checkbox" />
	<span class="description"> <?php echo $default_set ?></span>
<?php endif; ?>
<?php if ( 'OSmedia_yt_related' == $field['label_for'] ) : ?>
	<input <?php checked( $settings['OSmedia_yt_related'], 'on' ); ?> id="OSmedia_yt_related" name="OSmedia_settings[OSmedia_yt_related]" type="checkbox" />
	<span class="description"> <?php echo $default_set ?></span>
<?php endif; ?>

<?php ///////////////////////////////////////// Advanced Section S3 /////////////////////////////////////////////////////////////// ?>
<?php if ( 'OSmedia_s3enable' == $field['label_for'] ) : ?>
	<input <?php checked( $settings['OSmedia_s3enable'], 'on' ); ?> id="OSmedia_s3enable" name="OSmedia_settings[OSmedia_s3enable]" type="checkbox" />
<?php endif; ?>
<?php if ( 'OSmedia_s3server' == $field['label_for'] ) : ?>
	<input id="OSmedia_settings[OSmedia_s3server]" size="50" name="OSmedia_settings[OSmedia_s3server]" value="<?php echo $settings['OSmedia_s3server'] ?>" /> 
	<span class="description"></span>
<?php endif ?>
<?php if ( 'OSmedia_s3access' == $field['label_for'] ) : ?>
	<input id="OSmedia_settings[OSmedia_s3access]" size="50" name="OSmedia_settings[OSmedia_s3access]" value="<?php echo $settings['OSmedia_s3access'] ?>" /> 
	<span class="description"></span>
<?php endif ?>
<?php if ( 'OSmedia_s3secret' == $field['label_for'] ) : ?>
	<input id="OSmedia_settings[OSmedia_s3secret]" size="50" name="OSmedia_settings[OSmedia_s3secret]" value="<?php echo $settings['OSmedia_s3secret'] ?>" /> 
	<span class="description"></span>
<?php endif ?>
<?php if ( 'OSmedia_s3bucket' == $field['label_for'] ) : ?>
	<input id="OSmedia_settings[OSmedia_s3bucket]" size="50" name="OSmedia_settings[OSmedia_s3bucket]" value="<?php echo $settings['OSmedia_s3bucket'] ?>" /> 
	<span class="description"><b></b></span>
<?php endif ?>
<?php if ( 'OSmedia_s3dir' == $field['label_for'] ) : ?>
	<input id="OSmedia_settings[OSmedia_s3dir]" size="50" name="OSmedia_settings[OSmedia_s3dir]" value="<?php echo $settings['OSmedia_s3dir'] ?>" /> 
	<span class="description"></span>
<?php endif ?>


<?php 
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
////////////////////////////////////////////////////// Basic Section Player Configs //////////////////////////////////////////////////
?>

<?php if ( 'OSmedia_width' == $field['label_for'] ) : ?>
	<input id="OSmedia_settings[OSmedia_width]" style="width:40px" name="OSmedia_settings[OSmedia_width]" value="<?php echo $settings['OSmedia_width'] ?>" /> 
	<span class="description"> <?php echo $default_set ?></span>
<?php endif ?>
<?php if ( 'OSmedia_height' == $field['label_for'] ) : ?>
	<input id="OSmedia_settings[OSmedia_height]" style="width:40px" name="OSmedia_settings[OSmedia_height]" value="<?php echo $settings['OSmedia_height'] ?>" /> 
	<span class="description"> <?php echo $default_set ?></span>
<?php endif ?>

<?php if ( 'OSmedia_responsive' == $field['label_for'] ) : ?>
	<input <?php checked( $settings['OSmedia_responsive'], 'on' ); ?> id="OSmedia_responsive" value="on" name="OSmedia_settings[OSmedia_responsive]" type="checkbox" />
	<span class="description"> <?php echo $default_set ?></span>
<?php endif ?>

<?php if ( 'OSmedia_ratio' == $field['label_for'] ) : ?>	
	<select name="OSmedia_settings[OSmedia_ratio]">
		<option value="vjs-16-9" <?php if($settings['OSmedia_ratio'] == 'vjs-16-9') echo "selected='selected' "; ?>>16:9</option>
		<option value="vjs-4-3" <?php if($settings['OSmedia_ratio'] == 'vjs-4-3') echo "selected='selected' "; ?>>4:3</option>
	</select>
	<span class="description"> <?php echo $general_config ?> responsive aspect ratio</span>
<?php endif ?>
<!--
<?php if ( 'OSmedia_barpos' == $field['label_for'] ) : ?>
	<input <?php // checked( $settings['OSmedia_barpos'], 'on' ); ?> id="OSmedia_barpos" name="OSmedia_settings[OSmedia_barpos]" type="checkbox" />
<?php endif ?>
-->
<?php if ( 'OSmedia_skin' == $field['label_for'] ) : ?>
	<select name="OSmedia_settings[OSmedia_skin]">
	<?php foreach ($arr_skin as $value) : ?>
		<option value="<?php echo $value ?>"
		<?php if($settings['OSmedia_skin'] == $value) echo "selected='selected' "; ?>
		><?php echo $value ?></option>
	<?php endforeach ?>
	</select>
	<span class="description"> <?php echo $general_config ?></span>
<?php endif ?>

<?php if ( 'OSmedia_color1' == $field['label_for'] ) : ?>
	<input id="OSmedia_color1" name="OSmedia_settings[OSmedia_color1]" size='40' type="text" value="<?php echo $settings['OSmedia_color1'] ?>" data-default-color="#ccc" class="OSmedia_color_field" />
	<span class="description"><?php echo $general_config ?></span>
<?php endif ?>

<?php if ( 'OSmedia_color2' == $field['label_for'] ) : ?>
	<input id="OSmedia_color2" name="OSmedia_settings[OSmedia_color2]" size='40' type="text" value="<?php echo $settings['OSmedia_color2'] ?>" data-default-color="#ccc" class="OSmedia_color_field" />
	<span class="description"><?php echo $general_config ?></span>
<?php endif ?>

<?php if ( 'OSmedia_color3' == $field['label_for'] ) : ?>
	<input id="OSmedia_color1" name="OSmedia_settings[OSmedia_color3]" size='40' type="text" value="<?php echo $settings['OSmedia_color3'] ?>" data-default-color="#ccc" class="OSmedia_color_field" />
	<span class="description"><?php echo $general_config ?></span>
<?php endif ?>

