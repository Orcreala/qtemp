<?php 
namespace qtemp\form;
/**
 * 文本输入框
 * @method $this type(string $type) 设置输入框类型
 * @method $this config(array $config) 设置配置
 * @method $this addConfig(array $config) 添加配置
 * @method $this placeholder(string $placeholder) 添加提示信息
 * @author 莫耶尔
 */
class TextInput extends FormControl{
    protected string $type = 'text';
    public function placeholder(string $placeholder)
    {
        $this->addConfig(['placeholder'=>$placeholder]);
        return $this;
    }
    protected function temp(){
        
        if(!isset($this->config['placeholder'])){
            $this->addConfig(['placeholder'=>"请填写".$this->title.""]);
        }
        switch ($this->type) {
            /* case 'date':
                $this->addHtmlclass('js-bootstrap-date');
                $this->type='text';
                break; */
            case 'datetime':
                $this->addHtmlclass('js-bootstrap-datetime');
                $this->type='text';
                break;
        }
        ?>
        <input 
            <?php if($this->type&&$this->type!='text'){?>
                type="<?= $this->type?>"
            <?php }?>
            class="<?= join(' ', $this->htmlclass); ?>"
            id="<?= $this->name?>" 
            name="<?= $this->name?>"
            <?php if ($this->value!==null&&$this->value!=='') { ?> value="<?= $this->value?>" <?php } ?>
            <?php $this->required_echo();?>
            <?php $this->disabled_echo();?>
            <?php $this->read_only_echo();?>
            <?php $this->ConfigData();?>
        >
    <?php
    }
}
