=== OS media - HTML5 Featured Video ===
Contributors: mario marino
Plugin Name: OS media - HTML5 Featured Video plugin
Plugin URI: http://www.mariomarino.eu/en/os-media-wordpress-video-plugin/
Tested up to: 4.6.1
Stable tag: 2.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Tags: video, featured, post, page, streaming, custom post type, thumbnail, poster, autoplay, amazon, s3, cover image, cover video, embed, embedding, embed youtube, embed vimeo, embed videos, videojs, iframe, loop, player, plugin, responsive, shortcode, youtube, youtube embed, youtube player, youtube videos, video cover, HTML5, vimeo, vimeo player, vimeo embed, vimeo videos

For Featured video contents based on the latest HTML5 Video-js library (5.2.1). It works with Local Media, Amazon S3, Youtube & Vimeo. 

== Description ==

OS-media allow streaming of mp4/webm/ogv video progressively through PHP. It works with all WP themes, but is designed specifically for **OS-media theme**, a Twenty Fourteen Child Theme that is able to properly handle the layout of Custom Post for Featured Videos.

**DEMO ONLINE:** http://openstream.tv/demo/

**OS-media WP theme:** http://www.mariomarino.eu/os-media-wordpress-theme/

You can insert multimedia content:

* in normal post or page with the classic **shortcodes** added to the post textarea through control panel.
* in **Custom Post Type for "Featured Video"**, a dedicated area where we have a single "Featured video" for each page (like WP Featured Images). 

There are 5 ways to insert video:

1. from local WP installation (after upload video files through FTP): you must place the **PATH** of this local video resource (for example: /opt/lampp/htdocs/wp/wp-content/uploads/video)in general settings and files list appears in **file selector** in each control panel of single post.
2. from any file server or WP installation: you must place the URL (http://...) and files list appears in **file selector**.
3. from **Amazon S3** (Simple Storage Server) [files list appears in **file selector**],
4. directly uploading (or selecting) files through WordPress **media uploader** (limited size: depends on the configuration of php and WP) [dedicated input for each format: mp4, webm, ogg]
5. from the platform **Youtube & Vimeo**. [dedicated input]

>NOTE: **the way 1 is recommended** if you need to hide the video URL and prevents users from easily downloading the source.

> For **OSmedia Featured video (Custom Post Type)**, if you don't use **Os-media theme** you can insert the function **Osmedia_video()** in your theme. This content are also optimized for latest WP theme like Twenty Fifteen or Twenty Fourteen, automatically detected by this plugin, which loads the dedicated layout for CPT content. If your theme is not recognized, is loaded by default the file **layout/osmedia_cpt.php**, that you can edit and customize for display your featured video.

For normal post/page you can place **Poster Image for video** URL in shortcode (img="").<br>
In custom Post Field you can use the WP Featured Image, otherwise the plugin try to load image file from the same directory with the same name and .jpg extension.

Some configs parameters in **Option settings** are **general config**, not present in single-post settings, this one are effective for post already created (for example: "local video path", or "player skin"). And some other config parameters for **default setting** that are overwritten by the same settings parameters present in single-post (for example: "width", or "autoplay").

**List of all parameters of OS-media video:**
http://www.mariomarino.eu/wp-content/uploads/2013/10/OSmedia_vars.pdf

**Shortcode example:**

`[video file=”demo” fileurl="https://s3-eu-west-1.amazonaws.com/” img="http://.." youtube="KTRVYDwfDyU" width="640" height="360"]`

You can customize you own **Video-js skin** player simply generating css file through this tool: http://codepen.io/heff/pen/EarCt.
After that upload this file in the plugin folder: player/videojs/skin. The file name should reflect the name of the main class of the css file (not including the extension .css).

**More Info on my personal blog:** http://www.mariomarino.eu/en/os-media-wordpress-video-plugin/

**Credits:**

* <a href=”http://videojs.com/>Video-js</a> video library version 5.2.1
* The <a href=”https://github.com/iandunn/WordPress-Plugin-Skeleton>skeleton for an object-oriented/MVC WordPress plugin</a> by Ian Dunn.
* The <a href=”http://codesamplez.com/programming/php-html5-video-streaming-tutorial>VideoStream</a> class by Md Ali Ahsan Rana.

**IMPORTANT NOTE about old version (1.0):**
The old **featured video post** create through old version of this plugin MUST be simply manually reloaded in Admin Area and, when appear the video data on the metabox form, click "Generate Shortcode" button and save post. 
This because in the new version in normal post and page, video are displayed only through shortcode.

== Installation ==

1. from plugin admin panel and option select 'add new'.
2. search fo `OSmedia`.
3. select 'install'.
4. Activate the plugin from the Wordpress administration panel.


== Screenshots ==

1. General Options: Admin area for HTML5 Player
2. Metabox area for single post/page
3. Frontpage Twenty Fifteen Wordpress theme

== Changelog ==

= 2.2 =
* dinamically URL select from setting.
* add manual video-file input for extenal server.
* fixed bug in file url external server

= 2.1 =
* Optimized OS-media theme integration.
* add video-js skin.
* fixed bug in settings option area.

= 2.0 =
* interely redesigned interface with new Custom Post Type area dedicated to "Featured Video".
* add new file selector that allow select video from different source server, included Amazon S3.
* add latest version of video-js player 5.2.1.

= 1.1 =
* add insert shortcode for youtube video `[youtube url="url"]`.
* add responsive wrapper for youtube player.
* add responsive wrapper for HTML5 player.
* allow play youtube video through video-js HTML5 player

= 1.0 =
* First release


== Upgrade Notice ==

= 1.1 =
* add insert shortcode for youtube video `[youtube url="url"]`.
* add responsive wrapper for youtube player.
* add responsive wrapper for HTML5 player.
* allow play youtube video through video-js HTML5 player

= 2.0 =
* interely redesign interface with new Custom Post Type area dedicated to featured video content.
* add new file selector that allow select video from different source server, included Amazon S3.
* add latest version of video-js player 5.2.1.

= 2.1 =
* Optimized OS-media theme integration.
* add video-js skin.
* fixed bug in settings option area.

= 2.2 =
* dinamically URL select from setting.
* add manual video-file input for extenal server.
* fixed bug in file url external server
