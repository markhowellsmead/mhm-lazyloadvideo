=== Lazy load video players ===
Contributors: markhowellsmead
Donate link: https://paypal.me/mhmli
Tags: video, video player, lazy load, performance
Requires at least: 4.6.0
Tested up to: 4.7.2
Stable tag: trunk

Any video player which is included on the page will only be loaded if/when it is visible within the current browser window. Requires JavaScript and PHP 5.3.

== Description ==

Modifies the HTML output of any video players which have been embedded in the content area or in the site using the oEmbed technique. Any video player which is included on the page will only be loaded if/when it is/becomes visible within the current browser window. 

Requires JavaScript. The original player will be displayed if JavaScript is inactive on the page.

Please note that this plugin will not work in server environments running PHP versions older than 5.3.

== Installation ==

1. Upload the folder `mhm-lazyloadvideo` to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.

== Changelog ==

= 1.3.2 =
* Run script on `DOMContentLoaded` as well, in case `window.addEventListener('load')` is delayed by slow assets.
* Confirm functionality in WordPress 4.7.2.

= 1.3.1 =
* Load the script in the header. It's loaded asynchronously, so there are no performance issues.

= 1.3.0 =
* Load the script in the footer, not in the header.
* Add console logger command for debugging.
* Add version number to script-embedding URL.

= 1.2.0 =
* JavaScript syntax improvements.
* Call initialization on load, to cover a case where direct initialization happens before the page is loaded.

= 1.1.0 =
* Revises code to additionally hook the WordPress ``oembed_result`` filter, so that videos pulled in via ``wp_oembed_get`` also get parsed.
* Spellcheck secondary namespace.
* This README is now .txt, for compatability with the WordPress Plugin Directory.

= 1.0.2 =
* Add a proper README to the plugin.

= 1.0.1 =
* Fix a JavaScript syntax bug which ocurred in Internet Explorer.

= 1.0 =
* Initial version of the plugin.

= 0.0.1 =
* Bare bones of an idea.
