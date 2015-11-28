=== OS media - HTML5 Featured Video plugin for WordPress ===
Contributors: mariomarino
Requires at least: 3.4
Tested up to: 4.3
Stable tag: 2.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Donate link: 
Tags: autoplay, amazon, s3, cover image, cover video, embed, embedding, embed youtube, embed vimeo, embed videos, videojs, iframe, loop, player, plugin, responsive, seo, shortcode, youtube, youtube embed, youtube player, youtube videos, video, video analytics, video cover, video HTML5, video seo, vimeo, vimeo player, vimeo embed, vimeo videos


== Description ==
This Plugin is designed to make your Wordpress platform a multimedia station for video content delivery and is based on: 
This plugin is based on:
* <a href=”http://videojs.com/”>Video-js</a> video library version 5.2.1
* The <a href=”https://github.com/iandunn/WordPress-Plugin-Skeleton”>skeleton for an object-oriented/MVC WordPress plugin</a> by Ian Dunn.
* The <a href=”http://codesamplez.com/programming/php-html5-video-streaming-tutorial”>VideoStream</a> class by Md Ali Ahsan Rana.

There are two areas in wich can be insert multimedia content: 
1. **Custom Post Type for "Featured Video"**, ideal for video platforms where we have a single "Featured video" for each page (similar to the "Featured Image" of WP). The best way to display video is to use a specific template (like Osmedia-theme, of course, an example her: <a href="http://openstream.tv/sep/">demo online</a>) or insert the function “Osmedia_video” in your theme. This content are also optimized for latests WP theme like Twenty Fifteen or Twenty Fourteen.
2. in normal post or page with the classic **shortcodes** added to the post textarea.

There are 5 possibility to insert and stream on-demand video:

* from self-hosted local WP installation: you must place the PATH of this local video resource (/opt/lampp/htdocs/wp/wp-content/uploads/video)
* from any file server or WP installation: you must place the URL (http://...)
* from Amazon Simple Storage Server
* directly uploading (or selecting) in all three format (mp4, webm, ogg) the files through Wordpress media uploader (very limited size: depends on the configuration of php and WP)
* from the platform Youtube & Vimeo.

Option settings:
There are some configs parameters that are 'general config' not present in single-post settings that are effective for post already created. And default setting that are overwritten by single-post settings parameters.

**Demo online** 
http://openstream.tv
Other info on my personal blog:
* <a href="http://www.mariomarino.eu/category/wordpress/" title="OS media for WordPress">Home page</a>

**Shortcode [video]:** 
shortcode example:

`[video file=”demo” fileurl="https://s3-eu-west-1.amazonaws.com/” youtube="KTRVYDwfDyU" width="640" height="360"]`


== Installation ==

1. plugin admin panel and option 'add new'.
2. search fo `OSmedia`.
3. select 'install'.
4. Activate the plugin from the Wordpress administration panel.


== Screenshots ==

1. General Options: Admin area for HTML5 Player
2. Metabox area for single post/page


== Changelog ==

= Version 1.0 =


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