<?php 
extract($variables);

if( !isset($OSmedia_fileurl) || $OSmedia_fileurl == '') $server_no = 1;
elseif( substr( $OSmedia_fileurl, 0, 7 ) === "http://" ) $server_no = 2;
elseif( substr( $OSmedia_fileurl, 0, 10 ) === "https://s3" ) $server_no = 3;
?>

<ul class="server-monitor-list">
	<li><b>OSmedia File Server Monitor:</b></li>
	<li class="<?php echo $monitor_server['path'] ?>">1 - local video path: <span><?php echo $monitor_server['path'] ?></span></li>
	<li class="<?php echo $monitor_server['url'] ?>">2 - video server url: <span><?php echo $monitor_server['url'] ?></span></li>
	<li class="<?php echo $monitor_server['s3'] ?>">3 - S3 server: <span><?php echo $monitor_server['s3'] ?></span></li>
</ul>

<table class="metabox_table">
<thead></thead>
	<th>video identification</th>
	<th>on-fly local upload (MAX upload file-size: <?php echo ini_get('post_max_size') ?>)</th>
<tr>
	<td>

	<?php if ( !empty($video_list) ) : ; // $file_explode = explode('|', $OSmedia_file); ?>

		<select id="OSmedia_file" name="OSmedia_file">

			<option class="default_opt" value="" <?php if( !array_key_exists($OSmedia_file, $video_list)) echo " selected='selected' "; ?>> </option>
			<?php foreach ($video_list as $key => $value) : ?>
			<option value="<?php echo $key ?>" <?php if( $OSmedia_file == $key ) echo " selected='selected' "; ?> data-url="<?php echo $value ?>">
				<?php echo $key ?>
			</option>
			<?php endforeach ?>

		</select>

	<?php else : ?>

	<input id="OSmedia_file" name="OSmedia_file" type="text" size="15" placeholder="file without ext" value="<?php echo $OSmedia_file; ?>" />

	<?php endif ?>

	<laber for="OSmedia_file"><?php _e( 'file', 'OSmedia_player' ) ?> <?php if( isset($server_no) ) echo "(server {$server_no})" ?></label>

	<input id="OSmedia_fileurl" name="OSmedia_fileurl" type="hidden" value="<?php if( isset($OSmedia_fileurl) ) echo $OSmedia_fileurl ?>" />
	<input id="OSmedia_shortcode_name" name="OSmedia_shortcode_name" type="hidden" value="<?php if( isset($OSmedia_shortcode) ) echo $OSmedia_shortcode ?>" />

	</td>
	<td>	
		<input id="OSmedia_mp4" name="OSmedia_mp4" type="text" size="30" placeholder="URL mp4 video" value="<?php echo $OSmedia_mp4; ?>" />
		<input id="upload_button1" class="button" type="button" value="upload mp4" />
	</td>
</tr>
<tr>
	<td>
		<input id="OSmedia_youtube" name="OSmedia_youtube" size="10" type="text" placeholder="YouTube ID" value="<?php echo $OSmedia_youtube; ?>" />
		<label for="OSmedia_youtube"><?php _e('Youtube ID', 'OSmedia_player') ?></label>
	</td>
	<td>	
		<input id="OSmedia_webm" name="OSmedia_webm" type="text" size="30" placeholder="URL webm video" value="<?php echo $OSmedia_webm; ?>" />
		<input id="upload_button2" class="button" type="button" value="upload webm" />
	</td>
</tr>
<tr>
	<td>
		<input type="text" name="OSmedia_vimeo" id="OSmedia_vimeo" size="10" placeholder="Vimeo ID" value="<?php echo $OSmedia_vimeo; ?>" />
		<label for="OSmedia_vimeo"><?php _e('Vimeo ID', 'OSmedia_player') ?></label>
	</td>
	<td>
		<input id="OSmedia_ogg" name="OSmedia_ogg" type="text" size="30" placeholder="URL ogg video" value="<?php echo $OSmedia_ogg; ?>" />
		<input id="upload_button3" class="button" type="button" value="upload ogg" />
	</td>
</tr>
<?php if ( $post_type != 'osmedia_cpt' ) : ?>
<tr>
	<td></td>
	<td>
		<input id="OSmedia_img" name="OSmedia_img" type="text" size="30" placeholder="URL img" value="<?php echo $OSmedia_img; ?>" />
		<input id="upload_button4" class="button" type="button" value="upload img" />
	</td>
</tr>
<?php endif ?>

</table>

<?php if ( $post_type != 'osmedia_cpt' ) : ?>
	<p>
		<input class="size_field" type="number" id="OSmedia_width" name="OSmedia_width" value="<?php echo $OSmedia_width; ?>" />
		<label for="OSmedia_width"><?php _e('width [px]', 'OSmedia_player') ?></label>
		<input class="size_field_height" type="number" id="OSmedia_height" name="OSmedia_height" value="<?php echo $OSmedia_height; ?>" />
		<label for="OSmedia_height"><?php _e('height [px]', 'OSmedia_player') ?></label>
	 </p>
		
	<p>
		<select id="OSmedia_class" name="OSmedia_class">
			<option value="" <?php if($OSmedia_class == '') echo "selected='selected' "; ?> >none</option>
			<option value="alignleft" <?php if($OSmedia_class == 'alignleft') echo "selected='selected' "; ?> >alignleft</option>
			<option value="alignright" <?php if($OSmedia_class == 'alignright') echo "selected='selected' "; ?> >alignright</option>
		</select>
		<label for="OSmedia_class"> player position</label>	
	</p>

	<p class="metacheck">		
		<input id="OSmedia_responsive" name="OSmedia_responsive"  <?php checked( $OSmedia_responsive, 'on' ); ?> type="checkbox" />
		<label for="OSmedia_responsive"><?php _e('responsive layout', 'OSmedia_player') ?></label>
	</p>
<?php endif ?>

<p>
	<label for="OSmedia_start_m">Start at</label>
	<input style="margin-top:5px; width:50px" type="number" maxlength="2" id="OSmedia_start_m" name="OSmedia_start_m" placeholder="00" value="<?php echo $OSmedia_start_m; ?>" /> min.
	<input style="margin-top:5px; width:50px" type="number" maxlength="2" id="OSmedia_start_s" name="OSmedia_start_s" placeholder="00" value="<?php echo $OSmedia_start_s; ?>" /> sec.
</p>

<p class="metacheck">		
	<input id="OSmedia_autoplay" name="OSmedia_autoplay"  <?php checked( $OSmedia_autoplay, 'on' ); ?> type="checkbox" />
	<label for="OSmedia_autoplay"><?php _e('autoplay', 'OSmedia_player') ?></label>
</p>
<p class="metacheck">		
	<input id="OSmedia_loop" name="OSmedia_loop"  <?php checked( $OSmedia_loop, 'on' ); ?>  type="checkbox" />
	<label for="OSmedia_loop"><?php _e('loop', 'OSmedia_player') ?></label>
</p>
<p class="metacheck">		
	<input id="OSmedia_preload" name="OSmedia_preload"  <?php checked( $OSmedia_preload, 'on' ); ?>  type="checkbox" />
	<label for="OSmedia_preload"><?php _e('preload', 'OSmedia_player') ?></label>
</p>
<p class="metacheck">		
	<input id="OSmedia_controls" name="OSmedia_controls"  <?php checked( $OSmedia_controls, 'on' ); ?>  type="checkbox" />
	<label for="OSmedia_controls"><?php _e('hide player controls', 'OSmedia_player') ?></label>
</p>
<p style="margin-top:20px; border-top:solid silver 1px"></p>

<p class="metacheck">	
	<input id="OSmedia_yt_https" name="OSmedia_yt_https"  <?php checked( $OSmedia_yt_https, 'on' ); ?>  type="checkbox" />
	<label for="OSmedia_yt_https"><?php _e('use Youtube HTTPS protocol', 'OSmedia_player') ?></label>
</p>
<p class="metacheck">		
	<input id="OSmedia_yt_info" name="OSmedia_yt_info"  <?php checked( $OSmedia_yt_info, 'on' ); ?>  type="checkbox" />
	<label for="OSmedia_yt_info"><?php _e('hide Youtube info', 'OSmedia_player') ?></label>
</p>
<p class="metacheck">	
	<input id="OSmedia_yt_related" name="OSmedia_yt_related"  <?php checked( $OSmedia_yt_related, 'on' ); ?>  type="checkbox" />
	<label for="OSmedia_yt_related"><?php _e('hide Youtube related video', 'OSmedia_player') ?></label>
</p>

<?php // passaggio variabili nascoste via post ?> 	
	
<!-- <input id="OSmedia_path" name="OSmedia_path" type="hidden" value="<?php // echo $input['video_path'] ?>" /> -->
	
<div style="margin-top:20px; padding-bottom:20px">
		
<?php if ( $post_type != 'osmedia_cpt' ) : ?>
	<input  id="gen_shortcode" class="button-secondary" value="<?php _e('insert shortcode', 'OSmedia_player') ?>" name="shortcode_btn" />
<?php endif ?>
<input id="reset_btn" class="button-secondary reset_btn" value="<?php _e('reset value', 'OSmedia_player') ?>" name="reset_btn"/>
</div>

