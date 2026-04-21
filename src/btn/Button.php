<?php
namespace qtemp\btn;
/**
 * 按钮
 * @method $this btnclass(string $btnclass) 设置按钮颜色类型
 * @method $this size(string $size) 设置按钮大小
 * @method $this action(string $action) 设置按钮的提交地址
 * @method $this param(array $param) 设置参数
 * @method $this addParam(array $addParam) 添加参数
 * @method $this text(string $text) 设置按钮的文本
 * @method $this fa(string $fa) 设置图标
 * @method $this disabled(bool $disabled) 设置禁用
 * @method $this id(string $id) 设置ID
 * @see Control
 * @author 莫耶尔
 */
abstract class Button extends \qtemp\control\Control
{

    protected $htmlclass = ['btn'];
    /**
     * 按钮颜色类型
     * @var string $btnclass
     */
    protected string $btnclass = 'btn-primary';

    /**
     * 按钮大小
     * @var string $size
     */
    protected string $size = '';
    /**
     * 地址
     * @var string $action
     */
    protected string $action = '';

    /**
     * 参数
     * @return array
     */
    protected array $param = [];
    /**
     * 按钮的文本
     * @var string $text
     */
    public $text = '';

    /**
     * 图标
     * @var string $fa
     */
    public $fa = '';

    /**
     * 禁用
     * @var bool $disabled
     */
    protected bool $disabled = false;

    /**
     * 按钮的id
     * @var string $id
     */
    protected string $id = '';

    public function __construct()
    {
        if ($this->id == '' || empty($this->id)) {
            $this->id = '_' . str_replace('.', '_', uniqid(more_entropy: true));
        }
        return parent::__construct();
    }
    protected function tempInit()
    {
        if ($this->btnclass) {
            $this->addHtmlclass("btn-{$this->btnclass}");
        }
        if ($this->size) {
            $this->addHtmlclass("btn-{$this->size}");
        }
    }
}