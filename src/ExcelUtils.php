<?php

final class ExcelUtils{

  public static function getApplicationRoot(){
    return dirname(dirname(__FILE__));
  }

  public static function writeJSONFile(array$maps){
    $root =self::getApplicationRoot();
    $pathway = $root.'/updates/fxrate_generated.json';

    try{
      file_put_contents($pathway, json_encode($maps));
    }catch(Exception $ex){
      // ignore
    }
  }

  public static function parseBaseRateFile(){
    // parse the government treasury rates
    $root = self::getApplicationRoot();
    $pathway = $root.'/updates/fxbase.txt';
    if(!is_file($pathway)){
      return array();
    }

    $tab_pattern = '/[\t]+/';
    try{

      $content = file($pathway, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
      if(!$content || !count($content)){
        throw new Exception('Invalid content');
      }

      // the first entry is the columns
      $field_str = array_shift($content);
      $columns = preg_split($tab_pattern, $field_str);
      $columns = array_filter($columns);
      // using the columns as key we need to build the entire maps
      $maps = array();
      $len = count($content);
      $start = 0;
      while($len){
        $row_str = $content[$start];
        if($row_str && strlen($row_str)){
          $row = preg_split($tab_pattern, $row_str);
          $row = array_filter($row);
          if(count($columns) === count($row)){
            $maps[] = $row;
          }
          $len--;
          $start++;
        }
      }
    }catch(Exception $ex){
      // ignore this
    }

    return $maps;
  }

  public static function parseUpdateGovTreasuryRateFile(){
    // parse the government treasury rates
    $root = self::getApplicationRoot();
    $pathway = $root.'/updates/fxgovtreasury.txt';
    if(!is_file($pathway)){
      return array();
    }

    $tab_pattern = '/[\t]+/';
    try{

      $content = file($pathway, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
      if(!$content || !count($content)){
        throw new Exception('Invalid content');
      }

      // the first entry is the columns
      $field_str = array_shift($content);
      $columns = preg_split($tab_pattern, $field_str);
      $columns = array_filter($columns);
      // using the columns as key we need to build the entire maps
      $maps = array();
      $len = count($content);
      $start = 0;
      while($len){
        $row_str = $content[$start];
        if($row_str && strlen($row_str)){
          $row = preg_split($tab_pattern, $row_str);
          $row = array_filter($row);
          if(count($columns) === count($row)){
            $maps[] = $row;
          }
          $len--;
          $start++;
        }
      }
    }catch(Exception $ex){
      // ignore this
    }

    return $maps;
  }
  public static function parseUpdateDepositRateFile(){
    // Parses the deposit rates
    $root = self::getApplicationRoot();
    $pathway = $root.'/updates/fxdeposit.txt';
    if(!is_file($pathway)){
      return array();
    }

    $tab_pattern = '/[\t]+/';
    try{

      $content = file($pathway, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
      if(!$content || !count($content)){
        throw new Exception('Invalid content');
      }

      // the first entry is the columns
      $field_str = array_shift($content);
      $columns = preg_split($tab_pattern, $field_str);
      $columns = array_filter($columns);
      // using the columns as key we need to build the entire maps
      $maps = array();
      $len = count($content);
      $start = 0;
      while($len){
        $row_str = $content[$start];
        if($row_str && strlen($row_str)){
          $row = preg_split($tab_pattern, $row_str);
          $row = array_filter($row);
          if(count($columns) === count($row)){
            $maps[] = $row;
          }
          $len--;
          $start++;
        }
      }
    }catch(Exception $ex){
      // ignore this
    }

    return $maps;
  }

  public static function parseUpdateTransferRateFile(){
    // This scripts parses the transfer rates
    $root = self::getApplicationRoot();
    $pathway = $root.'/updates/fxtransfer.txt';
    if(!is_file($pathway)){
      return array();
    }

    $tab_pattern = '/[\t]+/';
    try{

      $content = file($pathway, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
      if(!$content || !count($content)){
        throw new Exception('Invalid content');
      }

      // the first entry is the columns
      $field_str = array_shift($content);
      $columns = preg_split($tab_pattern, $field_str);
      $columns = array_filter($columns);
      // using the columns as key we need to build the entire maps
      $maps = array();
      $len = count($content);
      $start = 0;
      while($len){
        $row_str = $content[$start];
        if($row_str && strlen($row_str)){
          $row = preg_split($tab_pattern, $row_str);
          $row = array_filter($row);
          if(count($columns) === count($row)){
            $maps[] = $row;
          }
          $len--;
          $start++;
        }
      }
    }catch(Exception $ex){
      // ignore this
    }

    return $maps;
  }

  public static function parseUpdateFile(){
    $root = self::getApplicationRoot();
    $pathway = $root.'/updates/fxrate.txt';
    if(!is_file($pathway)){
      return array();
    }

    $tab_pattern = '/[\t]+/';
    try{

      $content = file($pathway, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
      if(!$content || !count($content)){
        throw new Exception('Invalid content');
      }

      // parse this to generate the fxrate_generated.json file
      // the first entry is the columns
      $field_str = array_shift($content);
      $columns = preg_split($tab_pattern, $field_str);
      $columns = array_filter($columns);
      // using the columns as key we need to build the entire maps
      $maps = array();
      $len = count($content);
      $start = 0;
      while($len){
        $row_str = $content[$start];
        if($row_str && strlen($row_str)){
          $row = preg_split($tab_pattern, $row_str);
          $row = array_filter($row);
          if(count($columns) === count($row)){
            $maps[] = $row;
          }
          $len--;
          $start++;
        }
      }
    }catch(Exception $ex){
      // ignore this
    }

    return $maps;
  }
}
