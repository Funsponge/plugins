<?php
/**
 * The global settings
 *
 * @package Ditty Twitter Ticker
 */




add_action( 'admin_init', 'mtphr_dnt_twitter_initialize_settings' );
/**
 * Setup the custom options for the settings page
 *
 * @since 1.1.0
 */
function mtphr_dnt_twitter_initialize_settings() {

	/**
	 * General options sections
	 */
	$settings = get_option('mtphr_dnt_twitter_settings', array());
	$oauth = get_option('mtphr_dnt_twitter_oath', array());
	$access = get_option('mtphr_dnt_twitter_access', array());

	$pin = isset($settings['pin']) ? $settings['pin'] : '';

	$token = isset($oauth['oauth_token']) ? $oauth['oauth_token'] : '';
	$token_secret = isset($oauth['oauth_token_secret']) ? $oauth['oauth_token_secret'] : '';

	$display = isset($access['oauth_token']) ? true : false;
	$error = false;

	if( !$display ) {
		if( $pin != '' && $token != '' ) {
			$display = mtphr_dnt_twitter_tokens();
			$error = !$display;
		}
	}

	$fields = array();

	$fields['cache_time'] = array(
		'title' => __( 'Cache Time', 'ditty-twitter-ticker' ),
		'type' => 'number',
		'default' => 10,
		'after' => __( 'Minutes', 'ditty-twitter-ticker' ),
		'description' => __( 'Set the amount of time your feeds should stay cached.', 'ditty-twitter-ticker' )
	);

	if( $display ) {

		$fields['token'] = array(
			'title' => '<strong>'.__( 'Access granted!', 'ditty-twitter-ticker' ).'</strong>',
			'type' => 'access_tokens'
		);

		$fields['reset'] = array(
			'title' => '',
			'type' => 'twitter_reset'
		);

	} elseif( $error ) {

		$fields['error'] = array(
			'title' => __( 'Access error', 'ditty-twitter-ticker' ),
			'type' => 'error',
		);

		$fields['reset'] = array(
			'title' => '',
			'type' => 'twitter_reset'
		);

	} else {

		$fields['authorize'] = array(
			'title' => __( 'Generate pin from Twitter', 'ditty-twitter-ticker' ),
			'type' => 'authorize',
		);

		$fields['pin'] = array(
			'title' => __( 'Your pin', 'ditty-twitter-ticker' ),
			'type' => 'text',
			'rows' => 20
		);
	}

	/*
$fields['test'] = array(
		'title' => __( 'Test Info', 'ditty-twitter-ticker' ),
		'type' => 'twitter_test'
	);
*/

	if( false == get_option('mtphr_dnt_twitter_settings') ) {
		add_option( 'mtphr_dnt_twitter_settings' );
	}

	/* Register the general options */
	add_settings_section(
		'mtphr_dnt_twitter_settings_section',				// ID used to identify this section and with which to register options
		'',																					// Title to be displayed on the administration page
		'mtphr_dnt_twitter_settings_callback',			// Callback used to render the description of the section
		'mtphr_dnt_twitter_settings'								// Page on which to add this section of options
	);

	if( is_array($fields) ) {
		foreach( $fields as $id => $setting ) {
			$setting['option'] = 'mtphr_dnt_twitter_settings';
			$setting['option_id'] = $id;
			$setting['id'] = 'mtphr_dnt_twitter_settings['.$id.']';
			add_settings_field( $setting['id'], $setting['title'], 'mtphr_dnt_settings_callback', 'mtphr_dnt_twitter_settings', 'mtphr_dnt_twitter_settings_section', $setting);
		}
	}

	// Register the fields with WordPress
	register_setting( 'mtphr_dnt_twitter_settings', 'mtphr_dnt_twitter_settings' );
}




/**
 * General options section callback
 *
 * @since 1.1.0
 */
function mtphr_dnt_twitter_settings_callback() {
	?>
	<div style="margin-bottom: 20px;">
		<h4 style="margin-top:0;"><?php _e( 'Generate a pin from Twitter to grant access to Ditty Twitter Ticker', 'ditty-twitter-ticker' ); ?></h4>
	</div>
	<?php
}




/**
 * Authorize the app and get temp tokens
 *
 * @since 1.1.0
 */
function mtphr_dnt_metaboxer_authorize( $field, $value='' ) {

	// Get the settings
	$oauth = get_option('mtphr_dnt_twitter_oath', array());

	$tmhOAuth = new tmhOAuth(array(
	  'consumer_key'    => 'UBWUlsY6vzrajWcS4bw',
	  'consumer_secret' => 'YZAcJkFO1SPziucrztbruuJ53emjFaGXpBADlKaJFs8',
	));

	if( !isset($oauth['oauth_token']) ) {

		$callback = 'oob';
	  $params = array(
	    'oauth_callback' => $callback
	  );

	  $code = $tmhOAuth->request('POST', $tmhOAuth->url('oauth/request_token', ''), $params);

	  if ($code == 200) {

	  	// Update the settings with oauth
	  	$response = $tmhOAuth->extract_params($tmhOAuth->response['response']);
	  	update_option( 'mtphr_dnt_twitter_oath', $response );

	    $authurl = $tmhOAuth->url("oauth/authorize", '') .  "?oauth_token={$response['oauth_token']}";
	    echo '<a target="_blank" href="'. $authurl . '">' . $authurl . '</a>';

	  } else {
	    _e('<p><strong>There was an error connecting to Twitter.</strong><br/>Please reset the settings and try again in a couple minutes.</p>', 'ditty-twitter-ticker');
	    mtphr_dnt_metaboxer_twitter_reset();
	  }
  } else {
    echo '<a style="float:left;" class="mtphr-dnt-twitter-reset button" href="#">'.__('Regenerate Link','ditty-twitter-ticker').'</a><span style="float:left;margin-top:4px;" class="spinner"></span>';
  }
}




/**
 * Get the access tokens
 *
 * @since 1.1.0
 */
function mtphr_dnt_twitter_tokens() {

	// Get the settings
	$settings = get_option('mtphr_dnt_twitter_settings', array());
	$oauth = get_option('mtphr_dnt_twitter_oath', array());

	$tmhOAuth = new tmhOAuth(array(
	  'consumer_key'    => 'UBWUlsY6vzrajWcS4bw',
	  'consumer_secret' => 'YZAcJkFO1SPziucrztbruuJ53emjFaGXpBADlKaJFs8',
	));

  if( isset($oauth['oauth_token']) && isset($settings['pin']) ) {

	  $tmhOAuth->config['user_token']  = $oauth['oauth_token'];
	  $tmhOAuth->config['user_secret'] = $oauth['oauth_token_secret'];

	  $code = $tmhOAuth->request('POST', $tmhOAuth->url('oauth/access_token', ''), array(
	    'oauth_verifier' => $settings['pin']
	  ));

	  if ($code == 200) {

		  $access = $tmhOAuth->extract_params($tmhOAuth->response['response']);
	    update_option( 'mtphr_dnt_twitter_access', $access );

	    // Delete the cached files
	    mtphr_dnt_twitter_delete_cache();
	    return true;

	  } else {
	  	return false;
	  }
  } else {
  	return false;
  }
}




/**
 * Display access data
 *
 * @since 1.1.0
 */
function mtphr_dnt_metaboxer_access_tokens( $field, $value='' ) {

	$access = get_option('mtphr_dnt_twitter_access', array());

	echo '<p>'.__('Screen name:', 'ditty-twitter-ticker').' <strong>'.$access['screen_name'].'</strong></p>';
	echo '<p>'.__('User ID:', 'ditty-twitter-ticker').' <strong>'.$access['user_id'].'</strong></p>';
	echo '<p>'.__('Access token:', 'ditty-twitter-ticker').' <strong>'.$access['oauth_token'].'</strong></p>';
	echo '<p>'.__('Access token secret:', 'ditty-twitter-ticker').' <strong>'.$access['oauth_token_secret'].'</strong></p>';
}




/**
 * Display an error notice
 *
 * @since 1.1.0
 */
function mtphr_dnt_metaboxer_error( $field=false, $value='' ) {
	_e('<strong>There was an error accessing your account.</strong><br/>Please reset the settings and try again in a couple minutes.', 'ditty-twitter-ticker');
}




/**
 * Create a reset link
 *
 * @since 1.1.0
 */
function mtphr_dnt_metaboxer_twitter_reset( $field=false, $value='' ) {
	echo '<a style="float:left;" class="mtphr-dnt-twitter-reset button" href="#">Reset Settings</a><span style="float:left;margin-top:4px;" class="spinner"></span>';
}




/**
 * Print out settings for testing
 *
 * @since 1.1.0
 */
function mtphr_dnt_metaboxer_twitter_test( $field, $value='' ) {

	// Get the settings
	echo '<strong>mtphr_dnt_twitter_settings</strong><br/>';
	$settings = get_option('mtphr_dnt_twitter_settings', array());
	print_r( $settings );

	echo '<p>&nbsp;</p><strong>mtphr_dnt_twitter_oath</strong><br/>';
	$oauth = get_option('mtphr_dnt_twitter_oath', array());
	print_r( $oauth );

	echo '<p>&nbsp;</p><strong>mtphr_dnt_twitter_access</strong><br/>';
	$access = get_option('mtphr_dnt_twitter_access', array());
	print_r( $access );
}




add_action('admin_footer', 'mtphr_dnt_twitter_reset');
/**
 * Add reset jQuery
 *
 * @since 1.1.9
 */
function mtphr_dnt_twitter_reset() {
	?>
	<script>
	jQuery( document ).ready( function($) {
		$('.mtphr-dnt-twitter-reset').click( function(e) {
			e.preventDefault();

			var $spinner = $(this).next();
			$spinner.show();

			// Create the display
			var data = {
				action: 'mtphr_dnt_twitter_ajax_reset',
				security: '<?php echo wp_create_nonce('ditty-twitter-ticker'); ?>'
			};

			// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
			jQuery.post( ajaxurl, data, function( response ) {
				if( response ) {
					location.reload();
				}
			});
			delete_option( 'mtphr_dnt_twitter_settings' );
		});
	});
	</script>
	<?php
}

add_action( 'wp_ajax_mtphr_dnt_twitter_ajax_reset', 'mtphr_dnt_twitter_ajax_reset' );
/**
 * Ajax function used to reset settings
 *
 * @since 1.1.0
 */
function mtphr_dnt_twitter_ajax_reset() {

	// Get access to the database
	global $wpdb;

	// Check the nonce
	check_ajax_referer( 'ditty-twitter-ticker', 'security' );

	delete_option( 'mtphr_dnt_twitter_oath' );
	delete_option( 'mtphr_dnt_twitter_access' );
	delete_option( 'mtphr_dnt_twitter_settings' );

	return true;

	die(); // this is required to return a proper result
}



