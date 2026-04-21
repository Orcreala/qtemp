<?php
namespace qtemp\control;
/**
 * 控件抽象基类
 * @method $this htmlclass(string|array $htmlclass) 设置样式，覆盖原有的样式
 * @method $this addHtmlclass(string|array $htmlclass) 在原有样式基础上添加样式
 * @method $this config(array $config) 设置额外配置
 * @method $this addConfig(array $config) 添加配置
 * @method array data(array $data) 设置数据
 * @method $this addData(array $data) 添加数据
 * @method void echo() 输出控件
 * @method void temp() 组件输出
 * @method void tempInit() 组件输出前的初始化
 * @method string __toString() 控件的字符串表示
 * @method string str() 控件的字符串表示
 * @author 莫耶尔
 */
abstract class Control extends \qtemp\Qtemp
{
    /**
     * 输出控件
     * @return void
     */
    public function echo(){
        $this->tempInit();
        $this->temp();
    }
    /**
     * 组件输出前的初始化
     */
    protected function tempInit(){
    }
    /**
     * 组件输出
     * @return void
     */
    abstract protected function temp();
    /**
     * 控件的字符串表示
     * @return string
     */
    public function __toString(): string
    {
        ob_start();
        $this->echo();
        return ob_get_clean();
    }

    /**
     * 控件的字符串表示
     * @return string
     */
    public function str(){
        return $this->__toString();
    }
    /**
     * 元素类
     * @var array
     */
    protected $htmlclass = [];
    /**
     * 设置样式，覆盖原有的
     * @param array|string $htmlclass
     * @return $this
     */
    public function htmlclass($htmlclass)
    {
        if (is_array($htmlclass)) {
            $this->htmlclass = $htmlclass;
        }
        if (is_string($htmlclass)) {
            $this->htmlclass = explode(' ', $htmlclass);
        }
        return $this;
    }
    /**
     * 设置样式，在原有样式基础上添加
     * @param array|string $htmlclass
     * @return $this
     */
    public function addHtmlclass($htmlclass)
    {
        $class_arr = [];
        if (is_array($htmlclass)) {
            $class_arr = $htmlclass;
        }
        if (is_string($htmlclass)) {
            $class_arr = explode(' ', $htmlclass);
        }
        $this->htmlclass = array_unique(array_merge($this->htmlclass, $class_arr));
        return $this;
    }

    /**
     * 额外配置
     * @var array
     */
    protected array $config = [];

    /**
     * 构造函数
     * @var array $data 数据
     */
    protected array $data = [];

    /**
     * 输出配置和数据
     * @return void
     */
    protected function ConfigData()
    {
        echo ' ';
        foreach ($this->config as $key => $value) {
            ?>
                <?= $key;?>="<?= $value;?>"
            <?php
        }
        foreach ($this->data as $key => $value) {
            ?>
                data-<?= $key;?>="<?= $value;?>"
            <?php
        }
        echo ' ';
    }
}
