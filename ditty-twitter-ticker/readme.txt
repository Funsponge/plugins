=== Ditty Twitter Ticker ===
Contributors: metaphorcreations
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=FUZKZGAJSBAE6
Tags: twitter, ticker, twitter ticker, rotator, twitter rotator, list, twitter list, twitter widgets, ditty news ticker, ditty
Requires at least: 3.2
Tested up to: 3.5.2
Stable tag: /trunk/
License: GPL2

Add a twitter ticker type to your Ditty News Tickers. Display twitter feeds in a ticker, rotator, or list.

== Description ==

Ditty Twitter Ticker is a multi-functional twitter display plugin. Easily add Twitter feeds to your site either through shortcodes, direct functions, or in a custom Ditty News Ticker Widget.

#### There are 3 default ticker modes

* **Scroll Mode** - Scroll the ticker data left, right, up or down
* **Rotate Mode** - Rotate through the ticker data
* **List Mode** - Display your ticker data in a list

[**View samples of each mode.**](http://dittynewsticker.com/ditty-twitter-ticker/)

== Installation ==

1. Upload `ditty-twitter-ticker` directory to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Create tickers by going to **News Tickers > Add New**
4. Insert your tickers by copying and pasting the provided shortcode into another post.
5. Optionally, insert your tickers by copying and pasting the direct function code directly into your theme or plugin.

[**View full help documentation.**](http://dittynewsticker.com/mc/ditty-twitter-ticker-doc/)

== Frequently Asked Questions ==

= Are there any settings I need to configure? =

Each individual Ticker post has multiple settings to customize.

[**View full help documentation.**](http://dittynewsticker.com/mc/ditty-twitter-ticker-doc/)

== Screenshots ==

== Changelog ==

= 1.2.0 =
* Converted to new auto-updater.
* Fixed filename.

= 1.1.9 =
* Fixed php shortcode that was included in the settings.php file.

= 1.1.8 =
* Installed twitteroauth for Twitter search API connection.
* Added a cache time setting.

= 1.1.7 =
* Updated current_time() to return the local time.
* Fixed load_plugin_textdomain() path.
* Set all tweet links to open in new window.
* Added warning for empty Twitter handle.

= 1.1.6 =
* Added a class check for tmhOAuth.

= 1.1.5 =
* Minor CSS update.

= 1.1.4 =
* Fixed twitter cache delete error.

= 1.1.3 =
* Fixed issue with displaying emails in tweets.

= 1.1.2 =
* Fixed 'search' results that broke with the API 1.1 update.

= 1.1.1 =
* Fixed 'search' results that broke with the API 1.1 update.

= 1.1.0 =
* Added "hide_retweets" option.
* Added "hide_replies" option.
* Added option to disburse tweets evenly between feeds.
* Update Twitter API to 1.1.
* Add settings page to authorize Ditty Twitter Ticker.

= 1.0.0 =
* Initial upload of Ditty Twitter Ticker.

== Upgrade Notice ==

Converted to new auto-updater and fixed filename.
