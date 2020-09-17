<?php

class Validator {

    const COMPARE_GREATER = 2;
    const COMPARE_LESS = -1;
    const COMPARE_EQUAL = 1;
    const COMPARE_EMPTY = 0;
    const VALIDATE_FAIL = 0;
    const VALIDATE_SUCCESS = 1;

    public static function validateNumber($number, $min_len = null, $max_len = null) {
        $result = self::COMPARE_EQUAL;
        if (!is_numeric($number)) {
            $result = self::COMPARE_EMPTY;
        } elseif ($max_len && $number > $max_len) {
            $result = self::COMPARE_GREATER;
        } elseif ($min_len && $number < $min_len) {
            $result = self::COMPARE_LESS;
        } else {
            return self::COMPARE_EQUAL;
        }
    }

    public static function validateEmail($email) {

        if (!isset($email) || !preg_match("/.+@.+\..+./", $email)/* ||!checkdnsrr(array_pop(explode("@",$field)),"MX") This condition returns true only if the email address exists on the internet. it uses internet connection to do so */) {
            return self::VALIDATE_FAIL;
        } else {
            return self::VALIDATE_SUCCESS;
        }
    }

    public static function validateString($string, $min_len = null, $max_len = null) {
        $result = self::COMPARE_EQUAL;
        if ($min_len && strlen($string) < $min_len) {
            $result = self::COMPARE_LESS;
        } elseif ($max_len && strlen($string) > $max_len) {
            $result = self::COMPARE_GREATER;
        } else {
            return self::COMPARE_EQUAL;
        }
    }

    public static function validateDate($date) {
        $piece = explode("-", $date);
        if (!isset($date) || trim($date) == '' || (!is_numeric($piece[1]) || !checkdate($piece[1], $piece[2], $piece[0]))) {
            return self::VALIDATE_FAIL;
        } else {
            return self::VALIDATE_SUCCESS;
        }
    }

    public static function validateDateTime($datetime) {

        $piece = explode(" ", $datetime);
        $date = $piece[0];
        $time = $piece[1];
        $tp = explode(":", $time);
        if (self::validateDate($date) && is_numeric($tp[0]) && $tp[0] < 24 && is_numeric($tp[1]) && $tp[1] < 60) {
            return true;
        } else {
            return false;
        }
    }

    public static function validateMobile($number) {
        return self::validateNumber($number);
    }

    public static function validateRequired($val) {

        if (!empty($val)) {
            return self::VALIDATE_SUCCESS;
        } else {
            return self::VALIDATE_FAIL;
        }
    }

    public static function escapeCodes($variable) {

        if (is_numeric($variable)) {
            return $variable;
        }
        if (is_array($variable)) {
            foreach ($variable as $key => $val) {
                $variable[$key] = self::escapeCodes($val);
            }
        } else {
            $variable = str_replace("\r\n", "<br />", $variable);
            $variable = stripslashes(strip_tags(htmlspecialchars($variable)));
            
            $variable = addslashes(stripslashes(trim($variable)));
            
        }
        return $variable;
    }
    public static function unescapeCodes($variable) {
        if (is_array($variable)) {
            foreach ($variable as $key => $val) {
                $variable[$key] = self::unescapeCodes($val);
            }
        } else {
            $variable = stripslashes($variable);
        }
        return $variable;
    }
}
