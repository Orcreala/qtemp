<?php 
namespace qtemp\form;
/**
 * 下拉单选
 * 默认option接收字符串数组，value和text都为数组元素本身
 * @see SelectControl
 * @author 莫耶尔
 */
class SelectOne extends SelectControl
{
    /**
     * 已选值，用于初始化已选的选项
     * @var $value
     */
    protected $value=null;
    public function __construct(){
        parent::__construct();
        $this->getSelected(function($item,$key=null){
            if(isset($this->getValue)){
                $itemValue=$this->getItemVar($this->getValue,$item,$key);
            }else if(is_scalar($item)){
                $itemValue=$item;
            }else{
                return false;
            }
            return $itemValue==$this->value;
        });
    }
    protected function temp(){
        ?>
        <select 
            class="<?= join(' ', $this->htmlclass); ?>"
            id="<?= $this->name?>" 
            name="<?= $this->name?>" 
            <?php $this->required_echo(); ?>
            <?php $this->disabled_echo(); ?>
            <?php $this->read_only_echo();?>
            <?php $this->ConfigData();?>
        >
            <option value="">请选择<?= $this->title ?></option>
            <?php 
                if(!empty($this->options)&&is_array($this->options)){
                    foreach ($this->options as $key => $item){
                        //如果未定义getValue，且值是标量，则使用值作为value
                        $itemValue=$this->getItemVar($this->getValue,$item,$key);
                        $itemText=$this->getItemVar($this->getText,$item,$key);
                        if(is_scalar($itemValue)&&is_scalar($itemText)){
                            ?>
                                <option 
                                    value="<?= htmlspecialchars($itemValue); ?>"
                                    class="<?= $this->getItemVar($this->getClass,$item,$key) ?>"
                                    <?php if($this->getItemVar($this->getDisabled,$item,$key)):?>disabled<?php endif;?>
                                    <?php if($this->getItemVar($this->getSelected,$item,$key)):?>selected<?php endif; ?>
                                >
                                    <?= $itemText; ?>
                                </option>
                            <?php
                        }
                    }
            }?>
        </select>
        <?php
        /* if($this->required){
            FormValidator::getInstance()->registerSelectValidator($this->name, $this->title);
        } */
    }
}