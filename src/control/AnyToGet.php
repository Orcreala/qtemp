<?php
namespace qtemp\control;
// 助手函数通过 composer files 自动加载

/**
 * 提供 getXXX 与 getItemXXX 方法的魔术方法实现
 * @method $this getVariableName(string|int|callable $get) 设置获取值的方法
 * @method $this getItemVar(string|int|callable $get,array $item,string|int $key=null) 从item中获取变量
 * @method $this getItemVariableName(array $item,string|int $key=null) 从item中获取变量名
 * @author 莫耶尔
 */
trait AnyToGet
{
    /**
     * 魔术方法，处理 getXXX 方法
     * @param string $name 方法名
     * @param array $arguments 方法参数
     * @return $this
     */
    public function __call($name, $arguments)
    {
        if (strpos($name, 'getItem') === 0 && count($arguments) === 2) {
            $property = lcfirst($name);
            $property = lcfirst(substr($name, 7)); // 去掉前缀 getItem
            if (!property_exists($this, $property)) {
                throw new \Exception(class_basename($this) . "Call to undefined method {$name}()");
            }
            return $this->getItemVar($this->$property, $arguments[0], $arguments[1]);
        } else if (strpos($name, 'get') === 0 && count($arguments) === 1) {
            $property = lcfirst($name);
            if (!property_exists($this, $property)) {
                throw new \Exception(class_basename($this) . "Call to undefined method {$name}()");
            }
            $this->$property = $arguments[0];
            return $this;
        }
        return parent::__call($name, $arguments);
    }
    /**
     * 获取对象的变量
     * @param string|int|callable $get
     * @param array $item
     * @param string|int $key
     * @return mixed
     */
    protected function getItemVar($get, $item, $key = null)
    {
        if (is_null($get)) {
            return null;
        }
        if (is_object($item)) {
            $item = $item->toArray();
        }
        $var = null;
        if (isset($get)) {
            if (is_scalar($get)) {
                if ($get === VALUE) {
                    //当$get为VALUE时，返回$item本身
                    $var = $item;
                } else if ($get === KEY && $key !== null) {
                    //当$get为KEY时，返回键
                    $var = $key;
                } else if (is_array($item)) {
                    //当$get为字符串时，先尝试将$get按.分割，再获取$item的一系列值
                    if (is_string($get)){
                        $get = explode('.', $get);
                        $var = \qtemp\getValue($item, $get, null);
                    }
                }
            } else if (is_array($get)) {
                $var = \qtemp\getValue($item, $get, null);
            } else if (is_callable($get)) {
                $var = call_user_func_array($get, [$item, $key]);
            }
        }
        return $var;
    }
}
