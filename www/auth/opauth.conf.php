<?php

$config = array(

	'path' => AUTHPATH,

	'callback_url' => '{path}callback.php',

	'security_salt' => AUTHSALT,

	'Strategy' => array(
		
		'Facebook' => array(
			'app_id' => FACEBOOK_APP_ID,
			'app_secret' => FACEBOOK_APP_SECRET,
			'scope' => 'email'
		),
		
		'Twitter' => array(
			'key' => TWITTER_CONSUMER_KEY,
			'secret' => TWITTER_CONSUMER_SECRET
		),
    
    'SinaWeibo' => array(
      'key' => '3459570354',
      'secret' => '15877d118b2271aa55a68aa27dcc292b'
    ),
		
	'QQWeibo' => array(
      'key' => '801323480',
      'secret' => 'dce74a7f3b300ad63e582de70d7176ad'
    ),

    'OAuth' => array(
      'consumer_key' => '801323480',
      'consumer_secret' => 'dce74a7f3b300ad63e582de70d7176ad',
      'request_token_url' => 'https://open.t.qq.com/api/request_token',
      'access_token_url' => 'https://open.t.qq.com/cgi-bin/oauth2/access_token'
    ),
    
	),
);