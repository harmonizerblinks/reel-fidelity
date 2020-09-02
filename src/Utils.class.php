<?php

/*
 * Utilities
 */

final class Utils {

    public static function formatFileSize($bytes){
      return custom_format_bytes($bytes);
    }
    
    public static function unescape($variable) {
        if (is_array($variable)) {
            foreach ($variable as $key => $val) {
                $variable[$key] = self::unescape($val);
            }
        } else {
            $variable = stripslashes($variable);
            //$variable = html_entity_decode(stripslashes($variable));
        }
        return $variable;
    }

    public static function parseTemplate($template, $valuesMap=array()){

        foreach ($valuesMap as $key=>$value){
            $template = str_replace('{{'.$key.'}}', $value, $template);
        }

        return $template;
    }

    public static function makeSafe($variable) {
        if (is_numeric($variable)) {
            return $variable;
        }
        if (is_array($variable)) {
            foreach ($variable as $key => $val) {
                $variable[$key] = self::makeSafe($val);
            }
        } else {
            $variable = str_replace("\r\n", "<br />", $variable);
            $variable = stripslashes(strip_tags(htmlspecialchars($variable)));
            $variable = addslashes(stripslashes(trim($variable)));
        }
        return $variable;
    }

    public static function getSystemMACAddress() {
        return o(new MACDetector(PHP_OS))->getMACAddress();
    }

    public static function index_array_by($array, $index) {
        if(!is_array($array)){
            throw new Exception('Array expected: in ' . __FUNCTION__);
        }
        if (!$array) {
            return $array;
        }
        $new_array = array();
        foreach ($array as $key => $a) {
            if (isset($a[$index])) {
                $new_array[$a[$index]] = $a;
            } else {
                $new_array[$key] = $a;
            }
        }
        return $new_array;
    }

    /*Joins a one or more properties of a n array of objects to a string.
    *
    * params:
    *   $objects array array of objects to join
    *   $properties array array of properties in each object to join
    *   $glue String joining character(s)
    *
    **/

    public static function oimplode($objects, $properties, $object_glue = ',', $property_glue = ' '){

        if(empty($objects)){
            return '';

        }

        if( !is_array( $objects ) ){
            throw new Exception("Array of object(s) expected.");
        }

        if(empty($properties) || !is_array($properties)){
            throw new Exception("Array of property(s) expected.");

        }

        $string = '';

        foreach($objects as $object){
            $prop_string = '';
            foreach ($properties as $property) {
                $prop_string .= $object->$property . $property_glue;
            }

            $string .= trim($prop_string, $property_glue);
            $string .= $object_glue;
        }

        return trim($string, $object_glue);
    }

    public static function formatCurrency($val, $dp = 2, $sign = false, $currency_symbol = null) {
        global $gConfig;
        if (is_null($dp)) {
            $dp = 2;
        }
        if(is_array($val)){
            foreach ($val as $key => $v) {
                if ($sign) {
                    $val[$key] = (($currency_symbol) ? $currency_symbol : $gConfig->default_currency_symbol) .' '. number_format($v, $dp);
                } else
                $val[$key] = number_format($v, $dp);

            }

            return $val;
        }else{

            if ($sign) {
                return (($currency_symbol) ? $currency_symbol : $gConfig->default_currency_symbol) .' '. number_format($val, $dp);
            } else
            return number_format($val, $dp);

        }

    }

    public static function formatDate($date, $format=null) {
        global $gConfig;
        if(!$format){
            $format = $gConfig->date_friendly_format;
        }
        return date($format, strtotime($date));
    }

    public static function clean_fs_path($path) {
        $search = array(
            "/",
            "\\"
            );

        $replace = array(
            DIRECTORY_SEPARATOR,
            DIRECTORY_SEPARATOR
            );
        $new_path = str_replace($search, $replace, $path);

        return $new_path;
    }

    public static function framework_init() {

        Framework::init();
    }

    public static function framework_end() {
        Framework::end();
    }

    public static function load_post_get() {

        global $gGet, $gPost, $gRequest;

        $gGet = $gPost = $gRequest = array();

        if (!empty($_GET)) {
            foreach ($_GET as $key => $val) {
                if (!is_numeric($val))
                    $gGet[$key] = Validator::escapeCodes($val);
                else
                    $gGet[$key] = Validator::escapeCodes($val);
            }
        }
        if (!empty($_POST)) {
            foreach ($_POST as $key => $val) {
                if (!is_numeric($val))
                    $gPost[$key] = Validator::escapeCodes($val);
                else
                    $gPost[$key] = $val;
            }
        }

        $gRequest = array_merge($gGet, $gPost);
    }

    public static function init_global_err_msg() {
        if (!isset($_SESSION['gErrors']))
            $_SESSION['gErrors'] = array();
        if (!isset($_SESSION['gMessages']))
            $_SESSION['gMessages'] = array();
    }

    public static function clear_global_err_msg() {
        if (!empty($_SESSION['gErrors']))
            unset($_SESSION['gErrors']);
        if (!empty($_SESSION['gMessages']))
            unset($_SESSION['gMessages']);
    }

    public static function redirect($url, $header_string = "") {
        $str = $header_string;
        $str .= "Location: $url";
        header($str);
    }

    //returns difference between dates in days
    public static function date_diff($start_date, $end_date, $date_separator = "-") {
        $dtdata = explode($date_separator, $start_date);
        $tmp1 = mktime(0, 0, 0, $dtdata[1], $dtdata[2], $dtdata[0]);
        $dtdata = explode($date_separator, $end_date);
        $tmp2 = mktime(0, 0, 0, $dtdata[1], $dtdata[2], $dtdata[0]);
        $diff = abs($tmp1 - $tmp2);
        return round($diff / 86400);
    }

    public static function cleanup($d) {
        if (!is_dir($d))
            return false;
        for ($s = DIRECTORY_SEPARATOR, $stack = array($d), $emptyDirs = array($d); $d = array_pop($stack);) {
            if (!($handle = @dir($d)))
                continue;
            while (false !== $item = $handle->read())
                $item != '.' && $item != '..' && (is_dir($path = $handle->path . $s . $item) ?
                    array_push($stack, $path) && array_push($emptyDirs, $path) : unlink($path));
            $handle->close();
        }
        for ($i = count($emptyDirs); $i--; rmdir($emptyDirs[$i]))
            ;
    }

    //Validate License
    public static function val() {
        $xevt = new XEVT();
        $return_code = $xevt->validateLicense();

        if ($return_code !== XEVT::LICENSE_VALID) {
            $error_text = a(XEVT::getLicenseErrorTexts(), $return_code);
            die('Invalid license: ( ' . $error_text . ')');
            exit;
        }
    }

    static function getCountryName($id) {
        $country = DB::load('country', $id);
        return $country->name;
    }

    public static function GUID() {
        return self::shortID(rand() * rand() + microtime(true), false, 8);
    }

    public static function GUID2() {
        return md5(uniqid(rand(), true));
    }

    public static function GUID3($length=8) {
        $random = '';
        for ($i = 0; $i < $length; $i++) {
            $random .= chr(rand(ord('a'), ord('z')));
        }
        return $random;
    }

    public static function GUID4(){
        return uniqid (rand());
    }

    /* Translates a number to a short alhanumeric version
     *
     * Translated any number up to 9007199254740992
     * to a shorter version in letters e.g.:
     * 9007199254740989 --> PpQXn7COf
     *
     * specifiying the second argument true, it will
     * translate back e.g.:
     * PpQXn7COf --> 9007199254740989
     *
     * @param mixed $in String or long input to translate
     * @param boolean $to_num Reverses translation when true
     * @param mixed $pad_up Number or boolean padds the result up to a specified length
     * @param string $pass_key Supplying a password makes it harder to calculate the original ID
     *
     * @return mixed string or long
     */

    public static function shortID($in, $to_num = false, $pad_up = false, $pass_key = '1ia43dg') {
        $out = '';
        $index = 'abcdefghijkmnpqrstuvwxyz123456789ABCDEFGHIJKLMNPQRSTUVWXYZ';
        $base = strlen($index);

        if ($pass_key !== null) {
            // Although this function's purpose is to just make the
            // ID short - and not so much secure,
            // with this patch
            // you can optionally supply a password to make it harder
            // to calculate the corresponding numeric ID

            for ($n = 0; $n < strlen($index); $n++) {
                $i[] = substr($index, $n, 1);
            }

            $pass_hash = hash('sha256', $pass_key);
            $pass_hash = (strlen($pass_hash) < strlen($index) ? hash('sha512', $pass_key) : $pass_hash);

            for ($n = 0; $n < strlen($index); $n++) {
                $p[] = substr($pass_hash, $n, 1);
            }

            array_multisort($p, SORT_DESC, $i);
            $index = implode($i);
        }

        if ($to_num) {
            // Digital number  <<--  alphabet letter code
            $len = strlen($in) - 1;

            for ($t = $len; $t >= 0; $t--) {
                $bcp = bcpow($base, $len - $t);
                $out = $out + strpos($index, substr($in, $t, 1)) * $bcp;
            }

            if (is_numeric($pad_up)) {
                $pad_up--;

                if ($pad_up > 0) {
                    $out -= pow($base, $pad_up);
                }
            }
        } else {
            // Digital number  -->>  alphabet letter code
            if (is_numeric($pad_up)) {
                $pad_up--;

                if ($pad_up > 0) {
                    $in += pow($base, $pad_up);
                }
            }

            for ($t = ($in != 0 ? floor(log($in, $base)) : 0); $t >= 0; $t--) {
                $bcp = bcpow($base, $t);
                $a = floor($in / $bcp) % $base;
                $out = $out . substr($index, $a, 1);
                $in = $in - ($a * $bcp);
            }
        }

        return $out;
    }

    public static function intelligentDateFormat($dt) {
        if (empty($dt))
            return ' ';

        $unix_date = strtotime($dt);
        if (empty($unix_date))
            return ' ';

        $now = time();

        $periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
        $lengths = array("60", "60", "24", "7", "4.35", "12", "10");

        if ($now > $unix_date) {
            $difference = $now - $unix_date;
            $tense = "ago";
        } else {
            $difference = $unix_date - $now;
            $tense = "from now";
        }

        for ($j = 0; $difference >= $lengths[$j] && $j < count($lengths) - 1; $j++)
            $difference /= $lengths[$j];

        $difference = round($difference);
        if ($difference != 1)
            $periods[$j] .= 's';
        $text = "$difference $periods[$j] $tense";
        return $text;
    }

    public static function getPlaylistForIP($ip, $rates_playlist=false){

        if(!$rates_playlist){
            $playlist_root = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'playlists';
        }else{
            $playlist_root = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'rates' . DIRECTORY_SEPARATOR . 'playlists';
        }

        $playlist_file = $playlist_root . DIRECTORY_SEPARATOR . $ip .'.json';

        if(!file_exists($playlist_file)){
            $playlist_file = $playlist_root . DIRECTORY_SEPARATOR . 'default.json';
        }

        if(!file_exists($playlist_file)){
            throw new Exception('Default playlist not found.');
        }

        return file_get_contents($playlist_file);

    }

    public static function getAssetURL($url, $type=null){

        if(!$type){
            $type = pathinfo($url, PATHINFO_EXTENSION);
        }

        return sprintf('assets/asset.php?t=%s&salt=%s&f=%s', $type, AppConfig::ASSETS_SALT, $url);
    }

    public static function truncateString($str, $max_len = 50) {
        $ns = substr($str, 0, $max_len);
        $ns .= (strlen($str) > $max_len) ? '...' : '';
        return $ns;
    }

    public static function GetCountryByIP($ip) {
        if (empty($ip)) {
            return null;
        }

        //convert ip address like 100.100.100.100 to an unsigned integer ip
        //number like 192837490
        $IP_Number = sprintf("%u", ip2long($ip));

        $c = DB::findOne('ip_to_country', 'ip_number_from <= ? AND ip_number_to >= ? ', array($IP_Number, $IP_Number));

        if ($c->id) {
            return $c->Country_Name;
        } else {
            return null;
        }
    }

    public static function createTextFromTemplate($template_file_path, $fields, $values) {

        $template = new Template();
        $template->FilePath = $template_file_path;
        $template->Fields = $fields;
        $template->Values = $values;
        try {
            return $template->parse();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public static function getUploadedFiles($dest_path = "") {
        global $gConfig;
        $uploaddir = (empty($dest_path)) ? $gConfig->uploads_path : $dest_path;
        $file = "";
        $files = array();

        foreach ($_FILES as $key => $f) {
            if ($f['error'] == UPLOAD_ERR_OK) {
                $file->tmp_name = $f["tmp_name"];
                $file->name = $f["name"];
                $file->type = $f["type"];
                $file->size = $f["size"];
                $file->fullpath = "$uploaddir" . DIRECTORY_SEPARATOR . "$file->name";
                move_uploaded_file($file->tmp_name, $file->fullpath);
                $files[$key] = $file;
            }
        }
        return $files;
    }

    public static function calculateDistance($lat1, $lon1, $lat2, $lon2, $unit = 'KM') {
        if ($lat2 == 0 && $lon2 == 0) {
            return false;
        }
        $miles = (3958 * 3.1415926 * sqrt(($lat2 - $lat1) * ($lat2 - $lat1) + cos($lat2 / 57.29578) * cos($lat1 / 57.29578) * ($lon2 - $lon1) * ($lon2 - $lon1)) / 180);
        $unit = strtoupper($unit);
        if ($unit == 'KM') {
            return ($miles * 1.609344);
        } else if ($unit == 'N') {
            return ($miles * 0.8684);
        } else {
            return $miles;
        }
    }

    public static function convertWeight($weight, $toUnit, $fromUnit = 'KG') {
        $fromUnit = strtoupper($fromUnit);
        $toUnit = strtoupper($toUnit);

        $weight_kg = null;
        $newWeight = null;
        //convert weight to kg (base unit)
        switch ($fromUnit) {
            case 'KG':
            $weight_kg = $weight;
            break;
            case 'LB':
            case 'POUND':
            $weight_kg = $weight / 2.2046;
            break;
            case 'TON':
            $weight_kg = $weight / 0.001;
            break;
            default:
            die("Unit '$fromUnit' not recognized.");
            break;
        }
        switch ($toUnit) {
            case 'KG':
            $newWeight = $weight_kg;
            break;
            case 'LB':
            case 'POUND':
            $newWeight = $weight_kg * 2.2046;
            break;
            case 'TON':
            $newWeight = $weight_kg * 0.001;
            break;
            default:
            die("Unit '$toUnit' not recognized.");
            break;
        }

        return $newWeight;
    }

    public static function o($obj) {
        return $obj;
    }

    public static function a($array, $index, $default = null) {
        if (!$array) {
            return $default;
        }
        if (array_key_exists($index, $array))
            return $array[$index];
        else
            return $default;
    }
    public static function isJson($string) {
       json_decode($string);
       return (json_last_error() == JSON_ERROR_NONE);
   }

   public static function now($format = null) {
    if (!$format) {
        $format = "Y-m-d H:i:s";
    }
    return date($format);
}

public static function time($format = null) {
    if (!$format) {
        $format = "H:i:s";
    }
    return date($format, time());
}

public static function date($format = null) {
    if (!$format) {
        $format = "Y-m-d";
    }
    return date($format);
}

public static function datef() {
    global $gConfig;
    return date($gConfig->date_friendly_format, time($gConfig->date));
}

public static function e($str) {
    echo $str;
}

public static function currency_format($number, $decomals = 2) {
        //global $gConfig;
        //setlocale(LC_MONETARY, $gConfig->locale);
    return number_format($number, $decimals);
}

public static function startsWith($haystack, $needle) {
    $length = strlen($needle);
    return (substr($haystack, 0, $length) === $needle);
}

public static function endsWith($haystack, $needle) {
    $length = strlen($needle);
    if ($length == 0) {
        return true;
    }

    $start = $length * -1;
    return (substr($haystack, $start) === $needle);
}

}

function custom_format_bytes($bytes) {
  return custom_format_units_generic(
    $bytes,
    // NOTE: Using the SI version of these units rather than the 1024 version.
    array(1000, 1000, 1000, 1000, 1000),
    array('B', 'KB', 'MB', 'GB', 'TB', 'PB'),
    $precision = 0);
}

function custom_format_units_generic(
  $n,
  array $scales,
  array $labels,
  $precision  = 0,
  &$remainder = null) {

  $is_negative = false;
  if ($n < 0) {
    $is_negative = true;
    $n = abs($n);
  }

  $remainder = 0;
  $accum = 1;

  $scale = array_shift($scales);
  $label = array_shift($labels);
  while ($n >= $scale && count($labels)) {
    $remainder += ($n % $scale) * $accum;
    $n /= $scale;
    $accum *= $scale;
    $label = array_shift($labels);
    if (!count($scales)) {
      break;
    }
    $scale = array_shift($scales);
  }

  if ($is_negative) {
    $n = -$n;
    $remainder = -$remainder;
  }

  if ($precision) {
    $num_string = number_format($n, $precision);
  } else {
    $num_string = (int)floor($n);
  }

  if ($label) {
    $num_string .= ' '.$label;
  }

  return $num_string;
}
