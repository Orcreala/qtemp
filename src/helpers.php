<?php
namespace qtemp;
/**
 * qtemp 助手函数
 * 此文件通过 composer files 自动加载
 */

if (!defined('KEY')) {
    define('KEY', '@key');
}
if (!defined('VALUE')) {
    define('VALUE', '@value');
}

if (!function_exists('qtemp\vEcho')) {
    function vEcho(bool $v = false, $str = '')
    {
        if ($v) {
            echo $str;
        }
    }
}

if (!function_exists('qtemp\getValue')) {
    function getValue($item, $keys = [], $noValue = null)
    {
        if (empty($item) || (!is_array($item) && !is_object($item)) || empty($keys)) {
            return $noValue;
        }
        if (is_string($keys)) {
            $keys = explode('.', $keys);
        }
        if (is_array($keys) || is_object($keys)) {
            $value = $item;
            foreach ($keys as $key) {
                if (is_object($value)) {
                    $value = $value->toArray();
                }
                if (is_array($value) && count($value) == 1 && $key != 0) {
                    $value = reset($value);
                }
                if ((is_array($value) && array_key_exists($key, $value)) || isset($value[$key])) {
                    $value = $value[$key];
                } else {
                    return $noValue;
                }
            }
            return $value;
        }
        return $noValue;
    }
}

if (!function_exists('qtemp\getValues')) {
    function getValues($item, $keys_arr = [], $noValue_arr = [])
    {
        if (is_scalar($keys_arr)) {
            $keys_arr = [$keys_arr];
        }
        $values = [];
        foreach ($keys_arr as $i => $keys) {
            $noValue = isset($noValue_arr[$i]) ? $noValue_arr[$i] : null;
            $values[$i] = \qtemp\getValue($item, $keys, $noValue);
        }
        return $values;
    }
}

if (!function_exists('qtemp\arrayHasKey')) {
    function arrayHasKey(&$array, $keys = [])
    {
        foreach ($keys as $key) {
            if (!isset($array[$key])) {
                $array[$key] = '';
            }
        }
    }
}

if (!function_exists('qtemp\greyEcho')) {
    function greyEcho($str)
    {
        if (is_string($str) && strlen($str) > 0) {
            echo '<span style="color: #999;">', htmlspecialchars($str), '</span>';
        }
    }
}

if (!function_exists('qtemp\getSubstring')) {
    function getSubstring($string, $pattern = '*end', $noValue = false)
    {
        if ($pattern[0] === '*') {
            $end = substr($pattern, 1);
            $len = strlen($end);
            if (substr($string, -$len) === $end) {
                return substr($string, 0, -$len);
            }
        }
        if (substr($pattern, -1) === '*') {
            $start = substr($pattern, 0, -1);
            $len = strlen($start);
            if (substr($string, 0, $len) === $start) {
                return substr($string, $len);
            }
        }
        return $noValue;
    }
}
