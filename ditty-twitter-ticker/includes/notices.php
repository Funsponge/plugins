<?php
/**
 * Create admin notices
 *
 * @package Ditty Twitter Ticker
 */




add_action('admin_notices', 'mtphr_dnt_twitter_settings_notice');
/**
 * Create an admin notice to create settings
 *
 * @since 1.0.0
 */
function mtphr_dnt_twitter_settings_notice(){

	if( !mtphr_dnt_twitter_check_access() ) {

		$link = admin_url().'edit.php?post_type=ditty_news_ticker&page=mtphr_dnt_settings&tab=twitter';
		?>
    <div class="updated">
       <p><?php _e('You must authorize <strong>Ditty Twitter Ticker</strong> access through Twitter before you can display any feeds.', 'ditty-twitter-ticker'); ?><br/><?php printf( __('<a href="%s"><strong>Click here</strong></a> to generate a pin and grant acces to <strong>Ditty Twitter Ticker</strong>.', 'ditty-twitter-ticker'), $link ); ?></p>
    </div>
    <?php
  }
}




add_action('admin_notices', 'mmtphr_dnt_twitter_admin_notice');
/**
 * Create an admin notice for Twitter handles
 *
 * @since 1.1.7
 */
function mmtphr_dnt_twitter_admin_notice(){

	global $typenow;

	// Register admin js
	if( $typenow == 'ditty_news_ticker' ) {

		global $post;
		if( $post ) {

			$type = get_post_meta( $post->ID, '_mtphr_dnt_type', true );
			if( $type == 'twitter' ) {

				$error  = true;
				$handles = get_post_meta( $post->ID, '_mtphr_dnt_twitter_handles', true );
				if( is_array($handles) ) {
					foreach( $handles as $handle ) {
						if( $handle['handle'] != '' ) {
							$error = false;
						}
					}
				}
				if( $error ) {
					echo '<div class="error"><p>'.__('Please include at least one Twitter <strong>handle</strong> or <strong>keyword</strong> to display Tweets!','ditty-twitter-ticker').'</p></div>';
				}
			}
	  }
  }
}
