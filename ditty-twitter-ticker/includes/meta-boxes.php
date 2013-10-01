<?php
/**
 * Create the meta boxes
 *
 * @package Ditty Twitter Ticker
 */




add_action( 'admin_init', 'mtphr_dnt_twitter_metaboxes' ); 
/**
 * Create the post data metabox.
 *
 * @since 1.1.0
 */
function mtphr_dnt_twitter_metaboxes() {

	// Create an array to store the fields
	$twitter_fields = array();
	
	if( mtphr_dnt_twitter_check_access() ) {
		
		// Add the twitter handles field
		$twitter_fields['type'] = array(
			'id' => '_mtphr_dnt_twitter_type',
			'type' => 'select',
			'name' => __('Feed type', 'ditty-twitter-ticker'),
			'options' => array(
				'user_timeline' => __('User timeline', 'ditty-twitter-ticker'),
				'search' => __('Keyword search', 'ditty-twitter-ticker')
			),
			'description' => __('Select the type of feed to display.', 'ditty-twitter-ticker')
		);
	
		// Add the twitter handles field
		$twitter_fields['handles'] = array(
			'id' => '_mtphr_dnt_twitter_handles',
			'type' => 'list',
			'name' => __('Handles/keywords', 'ditty-twitter-ticker'),
			'structure' => array(
				'handle' => array(
					'type' => 'text'
				)
			),
			'description' => __('Add an unlimited number of terms to display.', 'ditty-twitter-ticker')
		);
		
		// Add the twitter limit field
		$twitter_fields['limit'] = array(
			'id' => '_mtphr_dnt_twitter_limit',
			'type' => 'number',
			'name' => __('Feed options', 'ditty-twitter-ticker'),
			'default' => 10,
			'after' => __('Limit', 'ditty-twitter-ticker'),
			'description' => __('Limit the number of tweets to show and set additional options.', 'ditty-twitter-ticker'),
			'append' => array(
				'_mtphr_dnt_twitter_hide_retweets' => array(
					'type' => 'checkbox',
					'label' => __('Hide retweets', 'ditty-twitter-ticker')
				),
				'_mtphr_dnt_twitter_hide_replies' => array(
					'type' => 'checkbox',
					'label' => __('Hide replies', 'ditty-twitter-ticker'),
				),
				'_mtphr_dnt_twitter_disbursement' => array(
					'type' => 'checkbox',
					'label' => __('Even disbursement', 'ditty-twitter-ticker'),
				)
			)
		);
		
		// Create an array to store the post display
		$twitter_display = array();
		
		// Add the twitter avatar field
		$twitter_display['avatar'] = array(
			'id' => '_mtphr_dnt_twitter_avatar',
			'type' => 'checkbox',
			'name' => __('User avatar', 'ditty-twitter-ticker'),
			'description' => __('Display the user\'s avatar.', 'ditty-twitter-ticker'),
			'append' => array(
				'_mtphr_dnt_twitter_avatar_dimensions' => array(
					'type' => 'number',
					'before' => __('Dimensions', 'ditty-twitter-ticker'),
					'default' => 40
				),
				'_mtphr_dnt_twitter_avatar_left' => array(
					'type' => 'checkbox',
					'label' => __('Lock Left', 'ditty-twitter-ticker')
				),
				'_mtphr_dnt_twitter_avatar_link' => array(
					'type' => 'checkbox',
					'label' => __('Link', 'ditty-twitter-ticker')
				),
				'_mtphr_dnt_twitter_avatar_display' => array(
					'type' => 'radio',
					'options' => array(
						'inline' => __('Inline', 'ditty-twitter-ticker'),
						'block' => __('Block', 'ditty-twitter-ticker')
					),
					'display' => 'inline',
					'default' => 'inline'
				)
			)
		);
		
		// Add the twitter user id field
		$twitter_display['name'] = array(
			'id' => '_mtphr_dnt_twitter_name',
			'type' => 'checkbox',
			'name' => __('User name', 'ditty-twitter-ticker'),
			'label' => __('Name', 'ditty-twitter-ticker'),
			'description' => __('Display the user\'s name.', 'ditty-twitter-ticker'),
			'append' => array(
				'_mtphr_dnt_twitter_handle' => array(
					'type' => 'checkbox',
					'label' => __('Handle', 'ditty-twitter-ticker')
				),
				'_mtphr_dnt_twitter_name_link' => array(
					'type' => 'checkbox',
					'label' => __('Link', 'ditty-twitter-ticker')
				),
				'_mtphr_dnt_twitter_name_display' => array(
					'type' => 'radio',
					'options' => array(
						'inline' => __('Inline', 'ditty-twitter-ticker'),
						'block' => __('Block', 'ditty-twitter-ticker')
					),
					'display' => 'inline',
					'default' => 'inline'
				)
			)
		);
		
		// Add the twitter text field
		$twitter_display['text'] = array(
			'id' => '_mtphr_dnt_twitter_text_display',
			'type' => 'radio',
			'name' => __('Tweet text', 'ditty-twitter-ticker'),
			'description' => __('The main data of the tweet.', 'ditty-twitter-ticker'),
			'options' => array(
				'inline' => __('Inline', 'ditty-twitter-ticker'),
				'block' => __('Block', 'ditty-twitter-ticker')
			),
			'display' => 'inline',
			'default' => 'inline'
		);
		
		// Add the twitter time field
		$twitter_display['time'] = array(
			'id' => '_mtphr_dnt_twitter_time',
			'type' => 'checkbox',
			'name' => __('Tweet time', 'ditty-twitter-ticker'),
			'description' => __('Show the tweet date & set the format.', 'ditty-twitter-ticker'),
			'append' => array(
				'_mtphr_dnt_twitter_time_format' => array(
					'type' => 'text',
					'size' => 8,
					'before' => __('Format', 'ditty-twitter-ticker'),
					'default' => '{time} '.__('ago', 'ditty-twitter-ticker')
				),
				'_mtphr_dnt_twitter_time_display' => array(
					'type' => 'radio',
					'options' => array(
						'inline' => __('Inline', 'ditty-twitter-ticker'),
						'block' => __('Block', 'ditty-twitter-ticker')
					),
					'display' => 'inline',
					'default' => 'inline'
				)
			)
		);
		
		// Add the twitter links field
		$twitter_display['links'] = array(
			'id' => '_mtphr_dnt_twitter_links',
			'type' => 'checkbox',
			'label' => __('Reply', 'ditty-twitter-ticker'),
			'name' => __('Tweet links', 'ditty-twitter-ticker'),
			'options' => array(
				'reply' => __('Reply', 'ditty-twitter-ticker'),
				'retweet' => __('Retweet', 'ditty-twitter-ticker'),
				'favorite' => __('Favorite', 'ditty-twitter-ticker'),
			),
			'description' => __('Select links to show along with each tweet.', 'ditty-twitter-ticker'),
			'display' => 'inline',
			'append' => array(
				'_mtphr_dnt_twitter_links_display' => array(
					'type' => 'radio',
					'options' => array(
						'inline' => __('Inline', 'ditty-twitter-ticker'),
						'block' => __('Block', 'ditty-twitter-ticker')
					),
					'display' => 'inline',
					'default' => 'inline'
				)
			)
		);
		
		// Add the post title field
		$twitter_fields['twitter_display'] = array(
			'id' => '_mtphr_dnt_twitter_display_order',
			'type' => 'sort',
			'name' => __('Tweet arrangement', 'ditty-twitter-ticker'),
			'description' => __('Set the tweet assets and order.', 'ditty-twitter-ticker'),
			'rows' => apply_filters('mtphr_dnt_twitter_display_rows', $twitter_display)
		);
	
	} else {
	
		// Display an error field
		$link = admin_url().'edit.php?post_type=ditty_news_ticker&page=mtphr_dnt_settings&tab=twitter';
		$twitter_fields['twitter_display'] = array(
			'id' => '_mtphr_dnt_twitter_error',
			'type' => 'html',
			'default' => sprintf( __('<a href="%s">Click here</a> to generate a pin and grant acces to Ditty Twitter Ticker.', 'ditty-twitter-ticker'), $link )
		);
	}
 
	// Create the metabox
	$twitter_fields = array(
		'id' => 'mtphr_dnt_twitter_data',
		'title' => __('Twitter Ticker Data', 'ditty-twitter-ticker'),
		'page' => array( 'ditty_news_ticker' ),
		'context' => 'normal',
		'priority' => 'high',
		'fields' => apply_filters('mtphr_dnt_twitter_data_fields', $twitter_fields)
	);
	new MTPHR_DNT_MetaBoxer( $twitter_fields );	
}