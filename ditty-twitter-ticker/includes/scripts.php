<?php
/**
 * Load CSS & jQuery Scripts
 *
 * @package Ditty Twitter Ticker
 */




add_action( 'wp_enqueue_scripts', 'mtphr_dnt_twitter_scripts' );
/**
 * Load the front end scripts
 *
 * @since 1.0.0
 */
function mtphr_dnt_twitter_scripts() {

	// Load the css
	wp_register_style( 'ditty-twitter-ticker', MTPHR_DNT_TWITTER_URL.'/assets/css/style.css', false, MTPHR_DNT_TWITTER_VERSION );
	wp_enqueue_style( 'ditty-twitter-ticker' );

	// Register twitter widget js
	wp_register_script( 'twitter-widgets', 'http://platform.twitter.com/widgets.js', false, MTPHR_DNT_TWITTER_VERSION, true );
	wp_enqueue_script( 'twitter-widgets' );
}

