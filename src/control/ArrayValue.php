<?php 
namespace qtemp\control;
/**
 * 对于值为数组的控件，自带最小数量和最大数量的设置
 * @method $this min($min=1) 设置最少数量
 * @method $this max(int $max) 设置最大数量
 * @author 莫耶尔
 */
trait ArrayValue
{
    /**
     * 值数组
     * @param array $value
     * @return $this
     */
    public function value($value){
        $this->value=$value;
        return $this;
    }
    /**
     * 必填等效于设置min(1)
     * @param bool $required
     * @return $this
     */
    final public function required(bool $required = true)
    {
        if ($required) {
            $this->min=1;
            $this->required=true;
        }else{
            $this->min=0;
            $this->required=false;
        }
        return $this;
    }
    /**
     * 最少数量
     * @var int $min
     */
    protected int $min=0;
    /**
     * 最少数量
     * @param int $min
     * @return $this
     */
    public function min(int $min=1)
    {
        if($min<0){
            $min=0;
        }
        $this->min = $min;
        if($min>0){
            $this->required=true;
        }
        return $this;
    }
    /**
     * 最大数量数量
     * @var int $max
     */
    protected int|null $max=null;
    /**
     * 最大数量数量
     * @param int $max
     * @return $this
     */
    public function max(int $max)
    {
        $this->max = $max;
        return $this;
    }
}
