<?php

if(!session_id()) {
	session_start();
}

require_once __DIR__ .'/third-party/twitter/TwitterAPIExchange.php';
require_once dirname(__DIR__) . '/conf/AppConfig.class.php';

class Social{

	public static function getTwitterFeeds(){

		# Set tokens
		//return array();
		$settings = array(
			'oauth_access_token' => AppConfig::TWITTER_ACCESS_TOKEN,
			'oauth_access_token_secret' => AppConfig::TWITTER_ACCESS_TOKEN_SECRET,
			'consumer_key' => AppConfig::TWITTER_CONSUMER_KEY,
			'consumer_secret' => AppConfig::TWITTER_CONSUMER_SECRET
			);

		$url = AppConfig::TWITTER_URL;
		$getField = AppConfig::TWITTER_GET_QUERY;
		$requestMethod = AppConfig::TWITTER_REQUEST_METHOD;

		$twitter = new TwitterAPIExchange($settings);
		$result = $twitter->setGetfield($getField)
		->buildOauth($url, $requestMethod)
		->performRequest();

		$tweets= json_decode($result);  

		if(strpos($url, 'search/')){
			$tweets = $tweets->statuses;  
		}

		return $tweets;

	}

	public static function buildTwitterFeedsMarquee($tweets){

		$msg = '';
		foreach ($tweets as $tweet) {    			
			$msg .= sprintf('<!--<img src="%s" style="height:30px"/>--> <strong>@%s</strong>: %s &nbsp;&nbsp;&nbsp; ', $tweet->user->profile_image_url_https, $tweet->user->screen_name, $tweet->text);
		}	

	return sprintf('<span id="feed_type"><i class="icon icon-twitter"></i></span><div id="marquee"><marquee  class="marquee" scrollamount = "2">%s</marquee></div>', $msg);

	}

	public static function buildTwitterFeedsTable($tweets){

		// under construction	
		return ' ';

	}

	public static function getFacebookFeeds(){

	}

	public static function buildFacebookFeedsMarquee($feeds){

		$msg = '';
		foreach ($feeds as $feed) {    				
			$msg .= sprintf('<img src="%s" style="height:30px"/> <strong>%s</strong>: %s &nbsp;&nbsp;&nbsp; ', $feed->user->profile_image_url_https, $feed->user->name, $feed->text);
		}	

	return sprintf('<div id="marquee"><marquee  class="marquee" scrollamount = "2">%s</marquee></div>', $msg);

	}

}

?>