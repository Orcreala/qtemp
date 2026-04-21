<?php 
namespace qtemp\form;
/**
 * 标签
 * @method $this for(string $for) 设置for属性
 * @see FormControl
 * @author 莫耶尔
 */
class Label extends FormControl
{
    protected $htmlclass = ['col-sm-2','control-label'];
    protected $for='';
    protected function temp()
    {
        ?>
        <label 
            for="<?= $this->for ?>" class="<?= join(' ', $this->htmlclass); ?>"
            id="<?= $this->name ?>"
            for="<?= $this->for ?>"
            <?php $this->ConfigData();?>
        >
            <?php if ($this->required):?><span class="form-required">*</span><?php endif;?>
            <?= $this->title ?>
        </label>
        <?php
    }
}
