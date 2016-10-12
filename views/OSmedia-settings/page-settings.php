<div class="wrap">

	<div id="icon-options-general" class="icon32"><br /></div>
	<h1><?php esc_html_e( OSmedia_NAME ); ?> default settings</h1>
	
<!--
	<h2 class="nav-tab-wrapper">
      <a class="nav-tab nav-tab-active" href="<?php echo admin_url() ?>/index.php?page=welcome-screen-about">Quick Guide</a>
      <a class="nav-tab" href="<?php echo admin_url() ?>/index.php?page=welcome-screen-credits">Credits</a>
    </h2>

    <div id='sections'>
    <section>This is the main welcome screen content</section>
    <section>The credit screen content should go here</section>
    </div>
-->

    <?php extract($monitor) ?>

    	<h3>Source Settings Monitor:</h3>
        <table id="server-monitor">
	        <tr>
	        	<th style="width:10px"></th>
	        	<th style="width:100px">server</th>
	        	<th style="width:340px">status</th>
	        </tr>
	        <tr>
	        	<td>1</td>
	        	<td>local (path)</td>
	        	<td class="server-monitor_<?php echo $path_color ?> last"><?php echo $path ?></span></td>
	        </tr>
	        <tr>
	        	<td>2</td>
	        	<td>remote (url)</td>
	        	<td class="server-monitor_<?php echo $url_color ?>"><?php echo $url ?></span></td>
	        </tr>
	        <tr>
	        	<td>3</td>
	        	<td>Amazon S3</td>
	        	<td class="server-monitor_<?php echo $s3_color ?>"><?php echo $s3 ?></span></td>
	        </tr>
        </table>

	<form id="options_form" method="post" action="options.php">



		<?php settings_fields('OSmedia_settings'); ?>
		<?php do_settings_sections('OSmedia_settings'); ?>

		<p class="submit">
			<input id="save_options" name="save_options" type="submit" class="button-primary" value="<?php esc_attr_e( 'Save Changes' ); ?>" />
		</p>

	</form>
<!--
	<form id="reset_options" class="reset_form" method="POST">
		<input id="reset_options_btn"  name="reset_options_btn" style="margin:0 20px 0 0" class="button-secondary reset_btn" type="submit" value="Reset to Default Value" />
	</form>
-->

</div>
<!-- .wrap -->