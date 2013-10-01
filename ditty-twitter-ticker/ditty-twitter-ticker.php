<?php
/*
Plugin Name: Ditty Twitter Ticker
Plugin URI: http://dittynewsticker.com/ditty-twitter-ticker/
Description: Add a twitter ticker type to your <a href="http://wordpress.org/extend/plugins/ditty-news-ticker/">Ditty News Tickers</a>. Display twitter feeds in a ticker, rotator, or list.
Version: 1.2.0
Author: Metaphor Creations
Author URI: http://www.metaphorcreations.com
License: GPL2
*/

/*
Copyright 2012 Metaphor Creations  (email : joe@metaphorcreations.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/




/**
 * Define constants
 *
 * @since 1.0.0
 */
if ( WP_DEBUG ) {
	define ( 'MTPHR_DNT_TWITTER_VERSION', '1.2.0-'.time() );
} else {
	define ( 'MTPHR_DNT_TWITTER_VERSION', '1.2.0' );
}
define ( 'MTPHR_DNT_TWITTER_DIR', plugin_dir_path(__FILE__) );
define ( 'MTPHR_DNT_TWITTER_URL', plugins_url().'/ditty-twitter-ticker' );





/* --------------------------------------------------------- */
/* !Include files - 1.2.0 */
/* --------------------------------------------------------- */

require_once( MTPHR_DNT_TWITTER_DIR.'includes/update.php' );
require_once( MTPHR_DNT_TWITTER_DIR.'includes/scripts.php' );
require_once( MTPHR_DNT_TWITTER_DIR.'includes/functions.php' );

if( is_admin() ) {

	if( !class_exists('tmhOAuth') ) {
		require_once( MTPHR_DNT_TWITTER_DIR.'includes/tmhOAuth.php' );
		require_once( MTPHR_DNT_TWITTER_DIR.'includes/tmhUtilities.php' );
	}
	if( function_exists('mtphr_dnt_metaboxer_container') ) {
		require_once( MTPHR_DNT_TWITTER_DIR.'includes/meta-boxes.php' );
	}
	require_once( MTPHR_DNT_TWITTER_DIR.'includes/activate.php' );
	require_once( MTPHR_DNT_TWITTER_DIR.'includes/notices.php' );
	require_once( MTPHR_DNT_TWITTER_DIR.'includes/settings.php' );
} else {
	if( !class_exists('TwitterOAuth') ) {
		require_once( MTPHR_DNT_TWITTER_DIR.'twitteroauth/twitteroauth.php' );
	}
}


