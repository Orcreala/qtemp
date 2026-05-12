<?php
namespace qtemp;
// 助手函数通过 composer files 自动加载
/**
 * qtemp工具类基类
 */
abstract class Qtemp
{
    /*
     * 构造函数，目前作占位符防止子类调用parent::__construct()出错
     */
    public function __construct()
    {
    }
    /**
     * 动态调用方法
     * @method $this addAny(array|mixed $value)为属性添加单个或多个元素
     * @method $this any(mixed $value)为属性设置值
     * @method $this any()将bool属性设置为true
     * @method $this noAny()将bool属性设置为false
     */
    public function __call($name, $arguments)
    {
        //add开头，为数组添加元素
        if (!empty($property = \qtemp\getSubstring($name,'add*'))) {
            if (property_exists($this, $property) && is_array($this->$property)) {
                if (is_array($arguments[0])) {
                    $this->$property = array_merge($this->$property, $arguments[0]);
                    return $this;
                } else {
                    array_push($this->$property, $arguments[0]);
                    return $this;
                }
            } else {
                throw new \Exception(class_basename($this) . ' Not implemented property: ' . $property);
            }
        }
        //无参数且属性为bool类型，设置为true
        if (property_exists($this, $name)&&is_bool($this->$name)&&count($arguments) === 0) {
            $this->$name = true;
            return $this;
        }
        //no开头且无参数且属性为bool类型，设置为false
        if(!empty($property=\qtemp\getSubstring($name,'no*'))){
            if(property_exists($this, $property)&&is_bool($this->$property)&&count($arguments) === 0){
                $this->$property = false;
                return $this;
            }
        }
        //其他情况，设置为参数值
        if (property_exists($this, $name) && count($arguments) === 1) {
            if (is_array($this->$name) && is_object($arguments[0])) {
                $arguments[0] = $arguments[0]->toArray();
            }
            $this->$name = $arguments[0];
            return $this;
        }
        throw new \Exception(class_basename($this) . ' Not implemented method: ' . $name);
    }
}