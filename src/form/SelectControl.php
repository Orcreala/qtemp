<?php 
namespace qtemp\form;
use qtemp\control\AnyToGet;

/**
 * 选择器抽象基类
 * @method $this getValue(string|int|callable|null $getValue) 设置获取选项值的方式
 * @method $this getItemValue(mixed $item,mixed $key)从元素获取值
 * @method $this getText(string|int|callable|null $getText) 设置获取选项文本的方式
 * @method $this getItemText(mixed $item,mixed $key)从元素获取文本
 * @method $this getDisabled(string|int|callable|null $getDisabled) 设置获取选项禁用状态的方式
 * @method $this getItemDisabled(mixed $item,mixed $key)从元素获取禁用状态
 * @method $this getSelected(string|int|callable|null $getSelected) 设置获取选项选中状态的方式
 * @method $this getItemSelected(mixed $item,mixed $key)从元素获取选中状态
 * @method $this getClass(string|int|callable|null $getClass) 设置获取选项元素类的方式
 * @method $this getItemClass(mixed $item,mixed $key)从元素获取元素类
 * @method $this options(array $options) 设置选项
 * @see FormControl
 * @author 莫耶尔
 */
abstract class SelectControl extends FormControl
{
    use AnyToGet;
    /**
     * 选项
     * @var array
     */
    protected array $options = [];
    /**
     * 获取选项的值
     * @var string|int|callable|null $getValue
     */
    protected $getValue=VALUE;
    /**
     * 获取选项的文本
     * @var string|int|callable|null $getText
     */
    protected $getText=VALUE;
    /**
     * 获取选项的禁用状态
     * @var string|int|callable|null $getDisabled
     */
    protected $getDisabled=null;
    /**
     * 获取选项的选中状态
     * @var string|int|callable|null $getSelect
     */
    protected $getSelected=null;
    /**
     * 获取选项的元素类
     * @var string|int|callable|null $getClass
     */
    protected $getClass=null;
    


}