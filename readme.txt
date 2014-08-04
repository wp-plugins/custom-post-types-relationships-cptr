=== Custom Post Types Relationships (CPTR) ===
Contributors: tsiger, anastis
Plugin Name: Custom Post Types Relationships (CPTR)
Plugin URI: http://www.cssigniter.com/ignite/custom-post-types-relationships/
Author URI: http://www.cssigniter.com/
Author: The CSSIgniter Team
Tags: related posts, custom related posts, custom relations, post relationships, related, post
Requires at least: 3.8
Tested up to: 3.9.1
Stable tag: 2.4

This plugin will let you create custom post relationships among posts, pages and custom post types.

== Description ==

Most of the "related posts" plugins out there while they provide some kind of control on how to create related posts, they all rely on an algorithm 
and the results are automatic. With CPTR you get total control as you can manually select the posts that you want to relate.

== Installation ==

1. Upload the folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. In the Posts page you will see the main screen of the plugin.

== Frequently Asked Questions ==

= How do I display the related posts then? =

Check http://www.cssigniter.com/ignite/custom-post-relationships/ for info on how to display your related posts.

== Screenshots ==

1. The Custom related posts (CPTR) screen. Search, select, relate, done.
2. The default settings panel.

== Changelog ==

= 2.4.1 =
* Added a "Settings" link into the plugins' listing page.
* The plugin's Upgrade Notice, if available, is now shown into the plugins listing page.

= 2.4 =
* Fixed a bug where the normal post object was not restored after the execution of cptr_category_selector(), when selecting related posts. (Thanks Pascal Rosin).

= 2.3 = 
* Fixed a bug where relationships were inappropriately outputted when using the shortcode.

= 2.2 = 
* All functions/pages/settings etc that included the acronym CPT have been renamed to CPTR. Functions have been deprecated and will be available until v3.0.
* Implemented automatic upgrade of settings etc for earlier version users.

= 2.1 = 
* Version number changed to match the plugin repo.
* Fixed a bug where the last related post was the current for $post after the plugin run.
* Thumbnail now links to the post too.
* The cpr_populate() function now always returns an array, even if empty.

= 1.0 = 
* Initial Release

== Upgrade Notice ==

= 2.4 =
This version corrects a major bug from v2.3.
