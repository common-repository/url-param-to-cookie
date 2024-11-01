=== URL Param To Cookie ===
Contributors: shaheedabdol
Donate link: https://paypal.me/shaheedabdol?locale.x=en_US
Tags: url param, cookie, url, param
Requires at least: 4.6
Tested up to: 4.7
Stable tag: trunk
Requires PHP: 5.2.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

This plugin takes a configured url parameter and stores it in a cookie with parameters you choose.

== Description ==

When configured, this plugin examines the visited url for a configured parameter and stores it in a cookie. The cookie name, domain, path
and expiry can be configured.

== Installation ==

Installation is the same as commonly found with other wordpress plugins:

1. Upload the plugin files to the `/wp-content/plugins/url-param-to-cookie` directory, or install the plugin through the WordPress plugins screen directly.
1. Activate the plugin through the 'Plugins' screen in WordPress
1. Use the Settings->UrlParamToCookie Settings screen to configure the plugin


== Frequently Asked Questions ==

= I fiddled with the path setting, now I can't see the cookie =

If you want the cookie to apply to the entire site, then set the path variable to '/'

= What do I do with validity time? =

The default for the cookie validity time is 0 - this means the cookie is valid until the end of the session, or until the web browser is closed. If you want the cookie to be valid
for a week, you'd enter 604800, this is a week's worth of seconds (7 (days) * 24 (hours) * 60 (minutes) * 60 (seconds)).

== Screenshots ==

1. A view of the available settings on the plugin settings screen.

== Changelog ==

= 1.0 =
* Initial commit.
* Cleaned up formatting significantly.

== Upgrade Notice ==

= 1.0 =
This is the initial version of the plugin, so no real reason to upgrade, since you can't install something lower than this version ;)
