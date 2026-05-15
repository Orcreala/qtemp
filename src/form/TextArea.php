<?php 
namespace qtemp\form;
/**
 * 多行文本输入框
 * @method $this minlength(int $minlength) 设置最小长度
 * @method $this maxlength(int $maxlength) 设置最大长度
 * @method $this placeholder(string $placeholder) 设置提示文本
 * @see FormControl
 * @author 莫耶尔
 */
class TextArea extends FormControl
{
    /**
     * 最小长度
     */
    protected $minlength=0;
    /**
     * 最大长度
     */
    protected $maxlength=0;
    protected $placeholder='';
    protected array $config = [];
    protected function temp()
    {
        if(empty($this->placeholder)){
            $this->placeholder = "请填写{$this->title}";
        }
        ?>
        <textarea class="<?= join(' ', $this->htmlclass); ?>"
            id="<?= $this->name?>" name="<?= $this->name?>"
            <?php $this->required_echo();?>
            <?php $this->disabled_echo();?>
            <?php $this->read_only_echo();?>
            <?php if($this->minlength>0):?>minlength="<?= $this->minlength?>"<?php endif;?>
            <?php if($this->maxlength>0):?>maxlength="<?= $this->maxlength?>"<?php endif;?>
            <?php $this->ConfigData();?>
        ><?= htmlspecialchars(isset($this->value)?$this->value:'')?></textarea>
        <?php
    }
}
