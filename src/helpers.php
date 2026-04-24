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
    /**
     * 安全获取数组的一系列的键的值，若键不存在则返回定义值，须知：若最终的值有效，但值等效于false，依然会返回原值
     * 若中间有单个长度的数组会自动处理无需手动加0,若手动加0则正常进行
     * (加这个自动的动机是thinkcmf不能远程多对一，只能用远程多对多关联，导致关联出的数据只能是数组)
     * @param array|object $item 源数组
     * @param string|array $keys 一系列键，可为逗号分隔的字符串或数组
     * @param mixed $noValue 不存在时返回的值，默认为null
     * @return mixed 
     */
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
    /**
     * 获取变量的一组数据
     * @param array|object $item 变量值
     * @param array $keys_arr 键数组，若提供键值对，则返回键值对数组
     * @param array $noValue_arr 不存在时返回的值数组，若没有对应键则返回null
     * @return array
     * @see getValue
     */
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
    /**
     * 确保数组的键有效
     * @param array $array
     * @param string|array $keys
     * @return void
     */
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
    /**
     * 灰色输出，自带html处理
     * @param string $str
     */
    function greyEcho($str)
    {
        echo '<span style="color:#999">' . htmlspecialchars($str) . '</span>';
    }
}

if (!function_exists('qtemp\getSubstring')) {
    /** 
     * 从字符串获取指定格式的子串
     * @param string $str 源字符串
     * @param string $replace 格式,*表示子串，不建议有多个*
     * @return string 子串，若没找到则返回空字符串
     */
    function getSubstring($str, $replace)
    {
        $parts = explode('*', $replace);
        foreach ($parts as &$part) {
            $part = preg_quote($part, '/');
        }
        unset($part);
        $pattern = '/^' . implode('(.*)', $parts) . '$/';
        if (preg_match($pattern, $str, $matches)) {
            $result = $matches[1];
            if ($result === '') {
                return '';
            }
            return lcfirst($result);
        }
        return '';
    }
}
if (!function_exists('qtemp\kbToString')) {
    /**
     * 转换KB大小为最大单位保留一位小数
     * @param int $kb KB大小
     * @return string
     */
    function kbToString(int $kb)
    {
        $num=$kb;
        $type='KB';
        if($num>1024){
            $num=$num/1024;
            $type='MB';
        }
        if($num>1024){
            $num=$num/1024;
            $type='GB';
        }
        if($num>1024){
            $num=$num/1024;
            $type='TB';
        }
        $num = round($num, 1);
        return $num.$type;
    }
}