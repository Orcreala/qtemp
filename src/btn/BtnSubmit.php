<?php 
namespace qtemp\btn;
/**
 * 提交按钮，会携带表单数据，若不设置action则使用表单的action，param必须有action才有效
 * @method $this msg(string $msg) 设置提示信息
 * @method $this subcheck(bool $subcheck) 是否开启选择检查
 * @method $this close(bool|int $close=true) 请求完成后是否关闭弹窗 true=总是关闭 int=匹配返回的code时关闭
 * @method $this refresh(bool $refresh=true) 请求成功后是否刷新页面，默认为true
 * @see Button
 * @author 莫耶尔
 */
class BtnSubmit extends Button
{

    /**
     * 提示信息
     * @var string $msg
     */
    protected $msg='';
    /**
     * 选择检查
     * @var bool $subcheck
     */
    protected $subcheck=false;
    
    /**
     * 请求完成后关闭弹窗
     * true=总是关闭, int=匹配返回的code时关闭
     * @var bool|int
     */
    protected $close = false;
    
    /**
     * 是否刷新页面
     * @var bool
     */
    protected bool $refresh = true;
    
    protected function tempInit():void{
        include_once __DIR__ . '/_btn_script.html';
        $this->addHtmlclass(['js-ajax-submit']);
        parent::tempInit(); 
    }

    protected function temp(){
        ?>
        <button
            id="<?= $this->id; ?>"
            class="<?= join(' ', $this->htmlclass);?>"
            type="submit"
            data-success="__qtemp_submit_success"
            <?php if(!empty($this->action)):?>data-action="<?= url($this->action,$this->param);?>"<?php endif;?>
            <?php if($this->subcheck):?>data-subcheck="true"<?php endif;?>
            <?php if(!empty($this->msg)):?>data-msg="<?= $this->msg;?>"<?php endif;?>
            <?php if($this->close !== false):?>data-close="<?= $this->close;?>"<?php endif;?>
            <?php if(!$this->refresh):?>data-refresh="false"<?php endif;?>
            <?php if($this->disabled):?>
                disabled="disabled"
            <?php endif;?>
             <?php $this->ConfigData();?>
        >
            <?php if(!empty($this->fa)):?>
                <i class="fa fa-<?= $this->fa;?> fa-fw"></i>
            <?php endif;?>
            <?= $this->text;?>
        </button>
        <?php
    }
}
