<?php 
namespace qtemp\form;
use qtemp\control\ArrayValue;
use qtemp\form\FormValidator;
/**
 * 下拉多选
 * @see SelectControl
 * @see ArrayValue
 * @author 莫耶尔
 */
class SelectMany extends SelectControl
{
    use ArrayValue;
    public function __construct(){
        parent::__construct();
        if(!is_array($this->value)){
            $this->value=[];
        }
        $this->getSelected(function($item,$key=null){
            if(isset($this->getValue)){
                $itemValue=$this->getItemVar($this->getValue,$item,$key);
            }else if(is_scalar($item)){
                $itemValue=$item;
            }else{
                return false;
            }
            return in_array($itemValue,$this->value);
        });
    }
    protected function temp(){
        if(is_scalar($this->value)){
            $this->value=[$this->value];
        }
        ?>
        <select 
            class="<?= join(' ', $this->htmlclass); ?>"
            id="<?= $this->name?>" 
            name="<?= $this->name?>[]"
            multiple="multiple"
            data-max-options="<?= $this->max?>"
            data-min-options="<?= $this->min?>"
            <?php if(!empty($this->min)):?> required="required" <?php endif;?>
            <?php $this->disabled_echo(); ?>
            <?php $this->ConfigData();?>
        >
            <option value="" disabled>请选择<?= $this->title ?></option>
            <?php 
                if(!empty($this->options)&&is_array($this->options)){
                    foreach ($this->options as $key => $item){
                        $itemValue=$this->getItemVar($this->getValue,$item,$key);
                        $itemText=$this->getItemVar($this->getText,$item,$key);
                        if(is_scalar($itemValue)&&is_scalar($itemText)){
                            ?>
                                <option 
                                    value="<?= htmlspecialchars($itemValue) ?>"
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
        <p class="help-block">按住control以多选；<?php if(!empty($this->max)):?>最多选择<?= $this->max?>个；<?php endif;
            if(!empty($this->min)):?>最少选择<?= $this->min?>个<?php endif;?></p>
        <?php if($this->max > 0 || $this->min > 0){
            FormValidator::getInstance()->registerMultiSelectValidator($this->name, $this->title, $this->min, $this->max);
        }
    }
}
