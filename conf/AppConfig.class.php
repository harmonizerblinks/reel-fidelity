<?php

/*
 *  Appliacation Configuration
 * 0
 */

define('ROOT_PATH', dirname(__DIR__));
date_default_timezone_set("Africa/Accra");
//ini_set('display_errors', 0);

final class AppConfig {

    const ROOT_URL = "/reel-fidelity";
    const SITE_NAME = 'Fidelity_Bank';
    const SITE_TITLE = 'Fidelity_Bank';
    const SITE_COPYRIGHT = '&copy; 2017 Fidelity_Bank';
    const COMPANY = 'Fidelity_Bank';

    const DB_HOST = 'localhost';
    const DB_PORT = '3306';
    const DB_NAME = '';
    const DB_USER = '';
    const DB_PASS = '';
    const DB_FREEZE = false;
    const DB_DEBUG = false;
	const DB_EXCEL_SOURCE = true;
    const MAIL_SMTP_HOST = '';
    const MAIL_SMTP_USERNAME = '';
    const MAIL_SMTP_PASSWORD = '';
    const MAIL_SMTP_PORT = '';
    const MAIL_IS_SMTP = true;
    const MAIL_ENABLE_SMTP_AUTH = true;
    const MAIL_SMTP_SECURITY = '';
    const MAIL_SMTP_DEFAULT_FROM_EMAIL = '';
    const MAIL_SMTP_DEFAULT_FROM_NAME = 'Fidelity Bank';

    const WEBMASTER_EMAIL = '';
    const ADMIN_EMAIL = '';
    const RELATIONSHIP_MANAGER_EMAIL = '';
    const CC_EMAIL = '';

    const ASSETS_SALT = '2015011377467555';
    const DISABLE_ASSETS_CACHING = true;

     //Rates Data Source
    const RATES_DB_DSN = 'localhost:1521/XE';
    const RATES_DB_USER = 'FXRATE';
    const RATES_DB_PWD = 'reel4oracle';
    const RATES_DB_OWNER = 'FXRATE';
    const RATES_DB_TABLE = 'currencyfxrate';
    const INTEREST_RATES_DB_TABLE = 'INTEREST_RATES';

    const RATES_API_REFRESH_INTERVAL = 30; //minutes

    // Socials
    # Twitter
    const TWITTER_ACCESS_TOKEN = '3256255475-v1tCXfvqViKTUbRMCtaY0pZzF76Zlwio1wkUN7P';
    const TWITTER_ACCESS_TOKEN_SECRET = 'cUhbcJiWksMbE2FwyRxFVQWRKHPLoiAb1G1iuo9nsZSnn';
    const TWITTER_CONSUMER_KEY = 'dtp1XFiHHvIw1xiwvCUathpNX';
    const TWITTER_CONSUMER_SECRET = 'qY18baHe2AwQX4AxiKRLFjvmIhUJkISDzKOtoEuM77wcYUcQsd';

    // const TWITTER_URL = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
    // const TWITTER_GET_QUERY = '?screen_name=proxynet_reel&exclude_replies=true&count=10';

    const TWITTER_URL = 'https://api.twitter.com/1.1/search/tweets.json';
    const TWITTER_GET_QUERY = '?q=from:@fidelitybankgh';

    const TWITTER_REQUEST_METHOD = 'GET';

    # Facebook

}
