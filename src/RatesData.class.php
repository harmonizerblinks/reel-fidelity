<?php

//Data source for rates

class RatesData {

	private $dns;
	private $user;
	private $pwd;
	private $schema_owner;
	private $rates_tbl;
	private $interest_rates_tbl;


	public function __construct($dns='', $user='', $pwd='', $schema_owner='', $rates_tbl='', $interest_rates_tbl=''){

		$this->dns = ($dns) ? $dns : AppConfig::RATES_DB_DSN;
		$this->user = ($user) ? $user : AppConfig::RATES_DB_USER;
		$this->pwd = ($pwd) ? $pwd : AppConfig::RATES_DB_PWD;
		$this->schema_owner = ($schema_owner) ? $schema_owner : AppConfig::RATES_DB_OWNER;
		$this->rates_tbl = ($rates_tbl) ? $rates_tbl : AppConfig::RATES_DB_TABLE;
		$this->interest_rates_tbl = ($interest_rates_tbl) ? $interest_rates_tbl : AppConfig::INTEREST_RATES_DB_TABLE;
	}

	public static function getApplicationRoot(){
    return dirname(dirname(__FILE__));
  }

	public function getExcelDBRates(){
		// This is only used when using the excel templates for rates
		require_once __DIR__.DIRECTORY_SEPARATOR.'ExcelUtils.php';
		$rates = array();
		// parse the rates ad return them
		try{
			$maps = ExcelUtils::parseUpdateFile();
			if($maps && count($maps)){
				foreach($maps as $entry){
					// the first is the currency name
					$rates[$entry[3]] = array(
						'currency_code' => $entry[3],
						'currency_name' => $entry[0],
						'buy_rate' => $entry[1],
						'sell_rate' => $entry[2],
					);
				}
			}
		}catch(Exception $ex){
			// ignore
		}

		return $rates;
	}

	public function getInterestRates(){
		$file_name = 'interest-rates.json';
		$data = self::readFile($file_name);
		return $data;
	}

	public function getFixedDepositRates(){
		$file_name = 'fixed-deposits.json';
		$data = self::readFile($file_name);
		return $data;
	}

	public function getDailyIndicativeRates(){
		$file_name = 'daily-indicative.json';
		$data = self::readFile($file_name);
		return $data;
	}

	public function getFXRates(){
		$file_name = 'forex-rates.json';
		$data = self::readFile($file_name);
		return $data;
	}

	public static function readFile($file_path){
		$root = self::getApplicationRoot();
		$file_path = $root.DIRECTORY_SEPARATOR.
		'updates'.DIRECTORY_SEPARATOR.$file_path;

		if(!$file_path || !is_file($file_path)){
			return array();
		}

		$data = array();
		try{
			$content = file_get_contents($file_path);
			if($content && strlen($content)){
				$data = json_decode($content, true);
				if(!$data){
					$data = array();
				}
			}
		}catch(Exception $ex){
			// ignore this for now
		}

		return $data;
	}

	public function getTransferRatesData(){
		// This is only used when using the excel templates for rates
		require_once __DIR__.DIRECTORY_SEPARATOR.'ExcelUtils.php';
		$rates = array();
		// parse the rates ad return them
		try{
			$maps = ExcelUtils::parseUpdateTransferRateFile();
			if($maps && count($maps)){
				foreach($maps as $entry){
					// the first is the currency name
					$rates[strtolower($entry[0])] = array(
						'currency_code' => $entry[0],
						'buy_rate' => $entry[1],
						'sell_rate' => $entry[2],
					);
				}
			}
		}catch(Exception $ex){
			// ignore
		}

		return $rates;
	}


	public function getDBRates(){

		require_once __DIR__ . DIRECTORY_SEPARATOR. 'DB.class.php';
		// if we have configured this application to use the excel format then
		// we need to read from excel rather than from db
		if(AppConfig::DB_EXCEL_SOURCE){
			// We are reading from excel source
			return $this->getExcelDBRates();
		}

		$dsn 		  = $this->dns;
		$user		  =	$this->user;
		$pwd 		  = $this->pwd;
		$schema_owner = $this->schema_owner;
		$table 		  = $this->rates_tbl;


		$db = new DB($dsn, $user, $pwd);
		$db->DBConnect();
		//$sql = sprintf('SELECT DISTINCT CURRENCY_CODE, "%2$s".* FROM "%1$s"."%2$s" WHERE RATE_DATE IN (SELECT MAX(RATE_DATE) FROM "%1$s"."%2$s")', $schema_owner, $table);
		$sql = sprintf('SELECT * FROM "%1$s"."%2$s"', $schema_owner, $table);
		$db->DBQuery($sql);

		$rates = array();

		while($row = $db->DBFetchRow()){
			$f_row = array();
			$f_row['currency_code'] = $row['ALT_CUR_CODE'];
			$f_row['currency_name'] = $row['DES_ENG'];
			$f_row['buy_rate'] = $row['BUY_RATE'];
			$f_row['sell_rate'] = $row['SELL_RATE'];
			$rates[$f_row['currency_code']] = $f_row;
		}


		$db->DBClose();

		return $rates;

	}

	public function getOnlineRates($baseCurrency, $otherCurrencies=array()){

		require_once __DIR__ . DIRECTORY_SEPARATOR. 'CurlUtil.class.php';

		//Using Yahoo (Faster)
		$url = "http://download.finance.yahoo.com/d/quotes.csv?e=.csv&f=c4l1&s=";
		$query = '';
		foreach($otherCurrencies as $currency){
			//Format: USDNGN=X,GBPNGN=X,JPYNGN=X etc;
			$query .= sprintf('%s%s=X,', trim($currency), $baseCurrency);
		}

		$query = rtrim($query, ',');
		$url .= $query;

		$res = CurlUtil::doGet($url);

		//Replace newlines with * for easier explode to array since \n doesn't work with explode.
		$res = str_replace(array("\n", "\r"), array("*",''), $res);

		if(empty($res)){
			throw new Exception('Rates download failed. Please ensure Internet connectivity on the server is active.');
		}

		//Convert CSV to array
		$rs = explode("*", $res);
		$rates = array();
		foreach ($rs as $key => $r) {
			$temp = array();
			$temp = explode(',', $r);
			$index = trim(Utils::a($temp,0), '"');
			//There is a bug in Yahoo's result that replaces USD with base currency
			if($index == $baseCurrency){
				$index = 'USD';
			}

			$rates[$index] = Utils::a($temp, 1) ;
		}

		return $rates;

	}

}
