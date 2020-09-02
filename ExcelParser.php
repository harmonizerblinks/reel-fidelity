<?php

final class ExcelParser{

  public static function getApplicationRoot(){
    return dirname(__FILE__);
  }

  private static function parseHeaders($raw){
    $tab_pattern = '/[\t]+/';
    $headers = preg_split($tab_pattern, $raw);
    return array_filter($headers);
  }

  private static function parseCell($raw){
    $tab_pattern = '/[\t]+/';
    $cells = preg_split($tab_pattern, $raw);
    return array_filter($cells);
  }

  private static function parseFXInterestRate($marker, $start_index, array $data){
    $known_marker = ':FXINTERESTRATE:';
    if($known_marker !== $marker){
      throw new Exception(
        'FX interest rate is expecting a marker like '.
        $known_marker.' but got '.$marker);
    }

    // start index is the index to start picking contents
    $blocks = array();
    // we loop and read the contents until we hit an empty string then we
    // stop and write this to file
    $has_header = false;
    $headers = array();
    $cells = array();
    for($ii = $start_index; $ii < count($data); $ii++){
      $entry = $data[$ii];

      if(!preg_match('#([a-zA-Z])#', $entry)){
        // we break the loop
        break;
      }

      if(!$has_header){
        $headers = self::parseHeaders($entry);
        $has_header = true;
      }else{
        $cells[] = self::parseCell($entry);
      }
    }

    // if we have the headers and the cells we can build the
    // json file for update
    $blocks = array();
    $blocks['type'] = 'Interest Rates';
    $blocks['rates'] = array();

    foreach($cells as $ckey => $cell){
      $room = array();

      $room[""] = $ckey + 1;
      foreach($headers as $key => $kname){
        $index_name = self::getKeyName($kname);
        $room[$index_name] = isset($cell[$key])?$cell[$key]:null;
      }
      $blocks['rates'][] = $room;
    }

    $root = self::getApplicationRoot();
    $path = $root.'/updates/interest-rates.json';
    file_put_contents($path, json_encode($blocks));
  }

  private static function parseFXFixedDepositRate($marker, $start_index, array $data){
    $known_marker = ':FXFIXEDDEPOSITRATE:';
    if($known_marker !== $marker){
      throw new Exception(
        'FX fixed deposit rate is expecting a marker like '.
        $known_marker.' but got '.$marker);
    }

    // start index is the index to start picking contents
    $blocks = array();
    // we loop and read the contents until we hit an empty string then we
    // stop and write this to file
    $has_header = false;
    $headers = array();
    $cells = array();
    for($ii = $start_index; $ii < count($data); $ii++){
      $entry = $data[$ii];

      if(!preg_match('#([a-zA-Z])#', $entry)){
        // we break the loop
        break;
      }

      if(!$has_header){
        $headers = self::parseHeaders($entry);
        $has_header = true;
      }else{
        $cells[] = self::parseCell($entry);
      }
    }

    // if we have the headers and the cells we can build the
    // json file for update
    $blocks = array();
    $blocks['type'] = 'Fixed Deposit Rates';
    $blocks['rates'] = array();

    foreach($cells as $ckey => $cell){
      $room = array();

      foreach($headers as $key => $kname){
        $index_name = self::getKeyName($kname);
        $room[$index_name] = isset($cell[$key])?$cell[$key]:null;
      }
      $blocks['rates'][] = $room;
    }

    $root = self::getApplicationRoot();
    $path = $root.'/updates/fixed-deposits.json';
    file_put_contents($path, json_encode($blocks));
  }

  private static function parseFXDailyIndicativeRate($marker, $start_index, array $data){
    $known_marker = ':FXDAILYINDICATIVERATE:';
    if($known_marker !== $marker){
      throw new Exception(
        'FX daily indicative rate is expecting a marker like '.
        $known_marker.' but got '.$marker);
    }

    // start index is the index to start picking contents
    $blocks = array();
    // we loop and read the contents until we hit an empty string then we
    // stop and write this to file
    $has_header = false;
    $headers = array();
    $cells = array();
    for($ii = $start_index; $ii < count($data); $ii++){
      $entry = $data[$ii];

      if(!preg_match('#([a-zA-Z])#', $entry)){
        // we break the loop
        break;
      }

      if(!$has_header){
        $headers = self::parseHeaders($entry);
        $has_header = true;
      }else{
        $cells[] = self::parseCell($entry);
      }
    }

    // if we have the headers and the cells we can build the
    // json file for update
    $blocks = array();
    $blocks['type'] = 'Daily Indicative Exchange Rates';
    $blocks['rates'] = array();

    foreach($cells as $ckey => $cell){
      $room = array();

      foreach($headers as $key => $kname){
        $index_name = self::getKeyName($kname);
        $room[$index_name] = isset($cell[$key])?$cell[$key]:null;
      }
      $blocks['rates'][] = $room;
    }

    
    $root = self::getApplicationRoot();
    $path = $root.'/updates/daily-indicative.json';
    file_put_contents($path, json_encode($blocks));
  }

  private static function parseFXRate($marker, $start_index, array $data){
    $known_marker = ':FXRATE:';
    if($known_marker !== $marker){
      throw new Exception(
        'FX rate is expecting a marker like '.
        $known_marker.' but got '.$marker);
    }

    // start index is the index to start picking contents
    $blocks = array();
    // we loop and read the contents until we hit an empty string then we
    // stop and write this to file
    $has_header = false;
    $headers = array();
    $cells = array();
    for($ii = $start_index; $ii < count($data); $ii++){
      $entry = $data[$ii];

      if(!preg_match('#([a-zA-Z])#', $entry)){
        // we break the loop
        break;
      }

      if(!$has_header){
        $headers = self::parseHeaders($entry);
        $has_header = true;
      }else{
        $cells[] = self::parseCell($entry);
      }
    }

    // if we have the headers and the cells we can build the
    // json file for update
    $blocks = array();
    $blocks['type'] = 'Forex Rates';
    $blocks['rates'] = array();
    $blocks['rates']['forex-rates'] = array();

    foreach($cells as $cell){
      $room = array();
      foreach($headers as $key => $kname){
        $index_name = self::getKeyName($kname);
        $room[$index_name] = isset($cell[$key])?$cell[$key]:null;
      }
      $blocks['rates']['forex-rates'][] = $room;
    }

    $root = self::getApplicationRoot();
    $path = $root.'/updates/forex-rates.json';
    file_put_contents($path, json_encode($blocks));
  }

  private static function getKeyName($name){
    switch(strtolower($name)){
      case 'currency name':
      return 'description';
      case 'buying':
      return 'buying';
      case 'selling':
      return 'selling';
      case 'currency code':
      return 'currency';
      case 'description':
      return 'description';
      case 'effective date':
      return 'effective date';
      case 'interest ( %pa)':
      return 'interest (%pa)';
      case 'tier':
      return 'tier(ghs)';
      case 'remittance buy':
      return 'remittance_bank_buy(ghs)';
      case 'remittance sell':
      return 'remittance_bank_sell(ghs)';
      case 'cash buy':
      return 'cash transaction_bank buy(ghs)';
      case 'cash sell':
      return 'cash transaction_bank sell(ghs)';
      default:
      return strtolower($name);
    }
  }

  public static function parseContent($raw){

    $tab_pattern = '/[\t]+/';
    $root = self::getApplicationRoot();
    $maps = array();

    try{

      $pathway = tempnam($root.'/updates/', 'txt');
      file_put_contents($pathway, $raw);
      $content = file($pathway, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

      if(!$content || !count($content)){
        throw new Exception('Invalid combined excelseet content');
      }

      // Loop the contents and target lines that starts with :
      for($jj = 0; $jj <count($content); $jj++){
        $entry = $content[$jj];
        if(substr($entry, 0, 1) === ':'){
          // we have a marker
          switch(trim($entry)){
            case ':FXRATE:':
            // This is the base rate
            self::parseFXRate(trim($entry), $jj+1, $content);
            break;
			      case ':FXINTERESTRATE:':
            // This is the interest rate
            self::parseFXInterestRate(trim($entry), $jj+1, $content);
            break;
            case ':FXFIXEDDEPOSITRATE:':
            self::parseFXFixedDepositRate(trim($entry), $jj+1, $content);
            break;
            case ':FXDAILYINDICATIVERATE:':
            self::parseFXDailyIndicativeRate(trim($entry), $jj+1, $content);
            break;
          }
        }else{
          continue;
        }
      }

      unlink($pathway);

    }catch(Exception $ex){
      // ignore this
      throw $ex;
    }
  }
}
