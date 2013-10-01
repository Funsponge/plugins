<?php
/**
 * General functions
 *
 * @package Ditty Twitter Ticker
 */




add_filter( 'mtphr_dnt_types', 'mtphr_dnt_twitter_type' );
/**
 * Add the posts type to the ticker
 *
 * @since 1.0.0
 */
function mtphr_dnt_twitter_type( $types ) {
	$types['twitter'] = array(
		'button' => __('Twitter', 'ditty-twitter-ticker'),
		'metaboxes' => array( 'mtphr_dnt_twitter_data' )
	);
	return $types;
}




add_filter( 'mtphr_dnt_settings', 'mtphr_dnt_twitter_settings' );
/**
 * Add the settings to the ticker
 *
 * @since 1.1.0
 */
function mtphr_dnt_twitter_settings( $settings ) {
	$settings['twitter'] = 'mtphr_dnt_twitter_settings';
	return $settings;
}




add_filter( 'mtphr_dnt_tick_array', 'mtphr_dnt_twitter_ticks', 10, 3 );
/**
 * Modify the ticker ticks
 *
 * @since 1.1.8
 */
function mtphr_dnt_twitter_ticks( $ticks, $id, $meta_data ) {

	// Extract the meta
	extract( $meta_data );

	$type = $_mtphr_dnt_type;

	if( $type == 'twitter' ) {

		// Create a new ticks array
		$new_ticks = array();

		// Check for access
		if( mtphr_dnt_twitter_check_access() ) {

			if( is_array($_mtphr_dnt_twitter_handles) ) {

				$tweets = array();

				$retweet = true;
				if( isset($_mtphr_dnt_twitter_hide_retweets) && $_mtphr_dnt_twitter_hide_retweets ) {
					$retweet = false;;
				}

				$replies = true;
				if( isset($_mtphr_dnt_twitter_hide_replies) && $_mtphr_dnt_twitter_hide_replies ) {
					$replies = false;
				}

				// Set the handle limit
				$handle_count = count($_mtphr_dnt_twitter_handles);
				$handle_limit = $_mtphr_dnt_twitter_limit;
				if( isset($_mtphr_dnt_twitter_disbursement) && $_mtphr_dnt_twitter_disbursement ) {
					$handle_limit = ceil($handle_limit/$handle_count);
				}

				foreach( $_mtphr_dnt_twitter_handles as $i => $handle ) {

					// Add the handle to the string
					if( sanitize_text_field($handle['handle']) != '' ) {
						$handle_tweets = mtphr_dnt_twitter_feed( $_mtphr_dnt_twitter_type, sanitize_text_field($handle['handle']) );
						if( $_mtphr_dnt_twitter_type == 'search' ) {
							$handle_tweets = $handle_tweets['statuses'];
						}

						if( is_array($handle_tweets) ) {

							// Filter out retweets and replies
							$ht_trim = array();
							foreach( $handle_tweets as $ht ) {

								if( !$replies && $ht['in_reply_to_screen_name'] != '' ) {
								} elseif( !$retweet && isset($ht['retweeted_status']) ) {
								} else {
									$ht_trim[] = $ht;
								}
							}

							// Even out the feeds
							if( $handle_limit != $_mtphr_dnt_twitter_limit ) {
								$ht_trim = array_slice( $ht_trim, 0, $handle_limit );
							}
							$tweets = array_merge( $tweets, $ht_trim );
						}
					}
				}

				// Sort the feeds
				usort( $tweets, 'mtphr_dnt_twitter_feed_sort' );

				// Trim the fees to the limit
				$tweets = array_slice( $tweets, 0, $_mtphr_dnt_twitter_limit );

				foreach( $tweets as $tweet ) {

					$avatar = $tweet['user']['profile_image_url'];
					$user = $tweet['user']['screen_name'];
					$user_name = $tweet['user']['name'];

					$avatar_left = false;
					if( isset($_mtphr_dnt_twitter_avatar_left) && isset($_mtphr_dnt_twitter_avatar) ) {
						if( $_mtphr_dnt_twitter_avatar_left && $_mtphr_dnt_twitter_avatar ) {
							$avatar_left = '-avatar-left';
						}
					}

					$tw = '<div class="mtphr-dnt-twitter-tweet'.$avatar_left.' mtphr-dnt-clearfix">';

					$avatar_image = '<img src="'.$avatar.'" width="'.$_mtphr_dnt_twitter_avatar_dimensions.'" height="'.$_mtphr_dnt_twitter_avatar_dimensions.'" />';
					if( isset($_mtphr_dnt_twitter_avatar_link) && $_mtphr_dnt_twitter_avatar_link ) {
						$avatar_image = '<a href="https://twitter.com/intent/user?screen_name='.$user.'" target="_blank">'.$avatar_image.'</a>';
					}

					if( $avatar_left ) {
						$tw .= '<span class="mtphr-dnt-twitter-avatar">'.$avatar_image.'</span>';
						$tw .= '<div class="mtphr-dnt-twitter-content" style="margin-left:'.$_mtphr_dnt_twitter_avatar_dimensions.'px">';
					} else {
						$tw .= '<div class="mtphr-dnt-twitter-content">';
					}

					foreach( $_mtphr_dnt_twitter_display_order as $key ) {

						switch( $key ) {

							case 'avatar':
								if( $avatar_left == false && isset($_mtphr_dnt_twitter_avatar) && $_mtphr_dnt_twitter_avatar ) {
									$tw .= '<span style="display:'.$_mtphr_dnt_twitter_avatar_display.'" class="mtphr-dnt-twitter-avatar">'.$avatar_image.'</span>';
								}
								break;

							case 'name':
								if( isset($_mtphr_dnt_twitter_name) || isset($_mtphr_dnt_twitter_handle) ) {

									$name = '';
									if( isset($_mtphr_dnt_twitter_name) && $_mtphr_dnt_twitter_name) {
										$name .= $user_name;
									}
									if( isset($_mtphr_dnt_twitter_name) && $_mtphr_dnt_twitter_name && isset($_mtphr_dnt_twitter_handle) && $_mtphr_dnt_twitter_handle) {
										$name .= ' ';
									}
									if( isset($_mtphr_dnt_twitter_handle) && $_mtphr_dnt_twitter_handle ) {
										$name = $name.'<span class="mtphr-dnt-twitter-handle">@'.$user.'</span>';
									}
									if( isset($_mtphr_dnt_twitter_name_link) && $_mtphr_dnt_twitter_name_link ) {
										$name = '<a href="https://twitter.com/intent/user?screen_name='.$user.'" target="_blank">'.$name.'</a>';
									}
									$tw .= '<span style="display:'.$_mtphr_dnt_twitter_name_display.'" class="mtphr-dnt-twitter-name">'.$name.'</span>';
								}
								break;

							case 'text':
								$tw .= '<span style="display:'.$_mtphr_dnt_twitter_text_display.'" class="mtphr-dnt-twitter-text">'.mtphr_dnt_twitter_links($tweet['text']).'</span>';
								break;

							case 'time':
								if( isset($_mtphr_dnt_twitter_time) && $_mtphr_dnt_twitter_time ) {
									// Format the time
									$time = preg_replace('/{time}/', human_time_diff(strtotime($tweet['created_at']), current_time('timestamp', 1)), $_mtphr_dnt_twitter_time_format);
									$tw .= '<span style="display:'.$_mtphr_dnt_twitter_time_display.'" class="mtphr-dnt-twitter-time">'.$time.'</span>';
								}
								break;

							case 'links':

								if( isset($_mtphr_dnt_twitter_links['reply']) || isset($_mtphr_dnt_twitter_links['retweet']) || isset($_mtphr_dnt_twitter_links['favorite']) ) {

									$links = '<span style="display:'.$_mtphr_dnt_twitter_links_display.'" class="mtphr-dnt-twitter-links">';
									if( isset($_mtphr_dnt_twitter_links['reply']) ) {
										$links .= '<a href="https://twitter.com/intent/tweet?in_reply_to='.$tweet['id_str'].'" target="_blank"><i class="mtphr-dnt-twitter-icon-reply"></i>'.__('Reply', 'ditty-twitter-ticker').'</a>';
									}
									if( isset($_mtphr_dnt_twitter_links['retweet']) ) {
										$links .= '<a href="https://twitter.com/intent/retweet?tweet_id='.$tweet['id_str'].'" target="_blank"><i class="mtphr-dnt-twitter-icon-retweet"></i>'.__('Retweet', 'ditty-twitter-ticker').'</a>';
									}
									if( isset($_mtphr_dnt_twitter_links['favorite']) ) {
										$links .= '<a href="https://twitter.com/intent/favorite?tweet_id='.$tweet['id_str'].'" target="_blank"><i class="mtphr-dnt-twitter-icon-favorite"></i>'.__('Favorite', 'ditty-twitter-ticker').'</a>';
									}
									$links .= '</span>';

									$tw .= $links;
								}
								break;
						}
					}

					$tw .= '</div></div>';

					$new_ticks[] = $tw;
				}
			}
		} else {
			$new_ticks[] = '<p>'.__('Twitter access not yet configured.', 'ditty-twitter-ticker').'</p>';
		}

		// Return the new ticks
		return $new_ticks;
	}

	return $ticks;
}




/**
 * Sort the feed arrays
 *
 * @since 1.0.0
 */
function mtphr_dnt_twitter_feed_sort( $a, $b ) {
	$t1 = strtotime($a['created_at']);
  $t2 = strtotime($b['created_at']);
  return $t2 - $t1;
}




/**
 * Display the feed
 *
 * @since 1.1.8
 */
function mtphr_dnt_twitter_feed( $type='user_timeline', $handle='' ) {

	if ( $handle != '' ) {

		// Create variables for the cache file and cache time
		$cachefile = MTPHR_DNT_TWITTER_DIR.'assets/cache/'.urlencode($handle).'-twitter-cache';
		$settings = get_option('mtphr_dnt_twitter_settings');
		$cachetime = isset($settings['cache_time']) ? intval($settings['cache_time'])*60 : 600;
		if( $cachetime < 60 ) {
			$cachetime = 60;
		}

		// if the file exists & the time it was created is less then cache time
		if ( (file_exists($cachefile)) && ( time() - $cachetime < filemtime($cachefile) ) ) {

			// Get the cache file contents & return the tweets
			$feed = file_get_contents( $cachefile );
			return json_decode( $feed, true );

		} else {

			// Save the feed
			$feed = mtphr_dnt_twitter_get_feed( $type, $handle );

			// If errors, use old file
			if ( !$feed ) {

				if ( (file_exists($cachefile)) ) {

					// Get the cached file
					$feed = file_get_contents( $cachefile );

					// Resave the feed to reset the cache time
					$fp = fopen( $cachefile, 'w' );
					fwrite( $fp, $feed );
					fclose( $fp );

					// Return the tweets
					return json_decode( $feed, true );
				}

			} else {

				// Create or open the cache file
				$fp = fopen( $cachefile, 'w' );

				// Write the twitter feed to the cache file
				fwrite( $fp, $feed );

				// Close the file
				fclose( $fp );

				// Return the tweets
				return json_decode( $feed, true );
			}
		}
	}
}

/**
 * Use curl to get the feed
 *
 * @since 1.1.8
 */
function mtphr_dnt_twitter_get_feed( $type, $handle ) {

	$access = get_option('mtphr_dnt_twitter_access', array());

	$connection = new TwitterOAuth('UBWUlsY6vzrajWcS4bw', 'YZAcJkFO1SPziucrztbruuJ53emjFaGXpBADlKaJFs8', $access['oauth_token'], $access['oauth_token_secret']);

	if( $type == 'search' ) {
		$args = array(
			'q' => $handle,
		  'count' => 100,
		  'include_rts' => true
		);
		$code = $connection->get('https://api.twitter.com/1.1/search/tweets.json', $args);
	} else {
		$args = array(
			'screen_name' => $handle,
		  'count' => 200,
		  'include_rts' => true
		);
		$code = $connection->get('https://api.twitter.com/1.1/statuses/user_timeline.json', $args);
	}

	if ( isset($code->errors) ) {
		return false;
	} else {
		return json_encode($code);
	}
}



/**
 * Check for Twitter access
 *
 * @since 1.1.0
 */
function mtphr_dnt_twitter_check_access() {

	$access = get_option('mtphr_dnt_twitter_access', array());
	return isset($access['oauth_token']);
}




/**
 * Delete the cached feeds
 *
 * @since 1.1.4
 */
function mtphr_dnt_twitter_delete_cache() {

	$directory = MTPHR_DNT_TWITTER_DIR.'assets/cache/';
	$files = scandir($directory);
	$files = array_slice( $files, 2 );
	if( is_array($files) && count($files) > 0 ) {
		foreach ( $files as $file ) {
			unlink($directory.$file);
		}
	}
}




/**
 * Return a modified string with twitter links
 *
 * @since 1.1.3
 */
function mtphr_dnt_twitter_links( $string ) {

	$string = make_clickable( $string );
	$string = preg_replace('/<a /','<a target="_blank" ', $string );
	$string = preg_replace("/ [@]+([A-Za-z0-9-_]+)/", "<a href=\"http://twitter.com/\\1\" target=\"_blank\">\\0</a>", $string );
	$string = preg_replace("/^[@]+([A-Za-z0-9-_]+)/", "<a href=\"http://twitter.com/\\1\" target=\"_blank\">\\0</a>", $string );
	$string = preg_replace("/ [#]+([A-Za-z0-9-_]+)/", "<a href=\"http://twitter.com/search?q=%23\\1\" target=\"_blank\">\\0</a>", $string );
	$string = preg_replace("/^[#]+([A-Za-z0-9-_]+)/", "<a href=\"http://twitter.com/search?q=%23\\1\" target=\"_blank\">\\0</a>", $string );

  return $string;
}




add_action( 'plugins_loaded', 'mtphr_dnt_twitter_localization' );
/**
 * Setup localization
 *
 * @since 1.1.7
 */
function mtphr_dnt_twitter_localization() {
	load_plugin_textdomain( 'ditty-twitter-ticker', false, 'ditty-twitter-ticker/languages/' );
}



