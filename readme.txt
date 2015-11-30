=== OS media video - HTML5 Featured Video
Contributors: mariomarino
Requires at least: 3.4
Tested up to: 4.3
Stable tag: 2.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Donate link: 
Tags: autoplay, amazon, s3, cover image, cover video, embed, embedding, embed youtube, embed vimeo, embed videos, videojs, iframe, loop, player, plugin, responsive, seo, shortcode, youtube, youtube embed, youtube player, youtube videos, video, video analytics, video cover, video HTML5, video seo, vimeo, vimeo player, vimeo embed, vimeo videos

This Plugin is designed to make your Wordpress platform a multimedia station for video content on-demand

== Description ==

OS-media video plugin is based on:

* <a href=”http://videojs.com/>Video-js</a> video library version 5.2.1
* The <a href=”https://github.com/iandunn/WordPress-Plugin-Skeleton>skeleton for an object-oriented/MVC WordPress plugin</a> by Ian Dunn.
* The <a href=”http://codesamplez.com/programming/php-html5-video-streaming-tutorial>VideoStream</a> class by Md Ali Ahsan Rana.

There are two areas where you can insert multimedia content:

* in normal post or page with the classic **shortcodes** added to the post textarea.
* in specific **Custom Post Type for "Featured Video"**, a dedicated area to make video platforms where we have a single "Featured video" for each page (like WP Featured Images). 

And there are 5 possibility to insert video:

* from **self-hosted local** WP installation: you must place the PATH of this local video resource (/opt/lampp/htdocs/wp/wp-content/uploads/video) **[main file selector]**
* from any **file server** or WP installation: you must place the URL (http://...) **[main file selector]**
* from **Amazon S3** (Simple Storage Server) **[main file selector]**
* directly uploading (or selecting) files through Wordpress media uploader (very limited size: depends on the configuration of php and WP) **[dedicated input for each format: mp4, webm, ogg]**
* from the platform **Youtube & Vimeo**. **[dedicated input]**

**OSmedia Featured video - Custom Post Type:**

For this type of page, the best way to display video is to use a specific template (like **Os-media theme**, specifically designed for this plugin) or you can insert the function **Osmedia_video()** in your theme. This content are also optimized for latest WP theme like Twenty Fifteen or Twenty Fourteen, automatically detected by this plugin, which loads the dedicated layout for CPT content. If your theme is not recognized, is loaded by default the file **layout/osmedia_cpt.php**, that you can edit and customize for display your featured video.

**Image poster for video:**

* For normal post&page you can place poster URL in shortcode.
* In custom Post Field you can use the WP Featured Image, otherwise the plugin try to load image file from the same directory with the same name and .jpg extension.
 

**Option settings:**

Some configs parameters are **general config**, not present in single-post settings, this one are effective for post already created (for example: "local video path", or "player skin"). And some other config parameters for **default setting** that are overwritten by the same settings parameters present in single-post (for example: "width", or "autoplay").

**More Info on my personal blog:**

http://www.mariomarino.eu/os-media/

**Shortcode example:**

`[video file=”demo” fileurl="https://s3-eu-west-1.amazonaws.com/” img="http://.." youtube="KTRVYDwfDyU" width="640" height="360"]`

**List of all parameters of OS-media video:**
http://www.mariomarino.eu/wp-content/uploads/2013/10/OSmedia_vars.pdf

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


= Version 2.0 =
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
