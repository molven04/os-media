<!-- dinamyc style video-js player -->
<?php require_once "style_videoplayer.php" ?>

<?php $flash_player_url = plugins_url( OSmedia_NAME ) . '/player/videojs/OSplayer.swf'  ; ?>

<object type="application/x-shockwave-flash" data="<?php echo $flash_player_url ?>" width="<?php echo $width ?>" height="<?php echo $height ?>">
	<param name="movie" value="<?php echo $flash_player_url ?>" />
	<param name="allowFullScreen" value="true" />
	<param name="wmode" value="transparent" />
	<param name="flashVars" value="config={}" />
	<img alt="Big Buck Bunny" src="<?php echo $poster ?>" width="<?php echo $width ?>" height="<?php echo $height ?>" title="No video playback capabilities, please download the video below" />
</object>

<!--
<p>
	<strong>Download video:</strong> <a href="<?php // echo $mp4_source ?>">MP4 format</a> | <a href="<?php // echo $ogg_source ?>">Ogg format</a> | <a href="<?php // echo $webm_source ?>">WebM format</a>
</p>
-->