<?php
namespace qtemp\form;
use qtemp\control\Control;



/**
 * 表单控件抽象基类
 * @method $this name(string $name) 设置字段名
 * @method $this title(string $title) 设置标题
 * @method $this value(mixed $value) 设置值
 * @method $this required(bool $required = true) 设置是否必填
 * @method $this disabled(bool $disabled = true) 设置是否禁用
 * @method $this read_only(bool $read_only = true) 设置是否只读
 * @method $this group(bool $group = true) 设置分组输出
 * @method $this helpText(string $helpText = '') 设置帮助文本
 * @method string upload_setting(array $upload_setting = []) 上传设置文本
 * @method string upload_setting_text(string $type = 'file') 上传设置帮助文本
 * @see Control
 */
abstract class FormControl extends Control
{
    /**
     * 元素类
     * @var array
     */
    protected $htmlclass = ['form-control'];
    /**
     * 字段名
     * @var string $name
     */
    protected string $name = '';
    /**
     * 标题
     *@var  string $title
     */
    protected string $title = '';
    /**
     * 值
     * @var mixed $value
     */
    protected $value;
    /**
     * 是否必填
     * @var bool $required
     */
    protected bool $required = false;

    /**
     * 输出必填
     */
    protected function required_echo()
    {
        if ($this->required): ?> required="required" <?php endif;
    }
    /**
     * 是否禁用
     * @var bool $disabled
     */
    protected bool $disabled = false;

    /**
     * 输出禁用
     */
    protected function disabled_echo()
    {
        if ($this->disabled): ?> disabled="disabled" <?php endif;
    }
    /**
     * 是否只读
     * @var bool $read_only
     */
    protected bool $read_only = false;

    /**
     * 输出只读
     */
    protected function read_only_echo()
    {
        if ($this->read_only): ?> readonly <?php endif;
    }


    /**
     * 是否分组
     * @var bool $group
     */
    protected $group = false;

    /**
     * 帮助文本
     * @var string $helpText
     */
    public string $helpText = '';


    /**
     * 上传设置
     * @var array
     */
    static protected $upload_setting = [];

    /**
     * 上传设置文本
     * @param string $type
     * @param bool $inHtml 是否在HTML中输出
     * @return string
     */
    static public function uploadSettingText(string $type = 'file',bool $inHtml=false): string
    {
        if (empty(self::$upload_setting)) {
            self::$upload_setting = cmf_get_upload_setting();
        }
        if (empty($type_setting = \qtemp\getValue(self::$upload_setting, ['file_types', $type]))) {
            return '';
        }
        $max_size = isset($type_setting['upload_max_filesize']) ? $type_setting['upload_max_filesize'] : 0;
        $extensions = isset($type_setting['extensions']) ? $type_setting['extensions'] : '';
        if (empty($max_size) || empty($extensions)) {
            return '';
        }
        //大小为最大单位保留一位小数
        $text = '允许上传大小' . \qtemp\kbToString($max_size) . ($inHtml ? '<br>' : '；') . '允许上传格式为' . $extensions;
        return $text;
    }


    /** 
     * 分组输出
     */
    public function groupEcho()
    {
        ?>
        <div class="form-group">
            <?php (new Label)->name($this->name . '-label')->for($this->name)->title($this->title)->required($this->required)->echo(); ?>
            <div class="col-md-6 col-sm-10">
                <?php $this->temp(); ?>
                <?php if (!empty($this->helpText)) { ?>
                <div class="help-block">
                    <?= $this->helpText ?>
                </div>
                <?php } ?>
            </div>
        </div>
        <?php
    }

    /**
     * 输出
     */
    protected function tempInit():void
    {
        include_once __DIR__ . '/_head.html';
        parent::tempInit();
    }
    public function echo(): void
    {
        if ($this->group) {
            $this->tempInit();
            $this->groupEcho();
        } else {
            parent::echo();
            if (!empty($this->helpText)) { ?>
            <p class="help-block">
                <?= $this->helpText ?>
            </p>
            <?php } 
        }
    }

    /**
     * 提交与返回按钮组
     * @param string $submit
     * @param string $backTo
     * @return void
     */
    static public function submitBack(string $submit, string $backTo = 'index'): void
    {
        ?>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-primary js-ajax-submit"><?= lang($submit) ?></button>
                <a class="btn btn-default" href="<?= url($backTo) ?>"><?= lang('BACK') ?></a>
            </div>
        </div>
        <?php
    }
}