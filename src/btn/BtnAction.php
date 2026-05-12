<?php 
namespace qtemp\btn;
/**
 * 操作按钮，不会携带表单数据
 * @method $this msg(string $msg) 设置提示信息
 * @method $this close(bool|int $close=true) 请求完成后是否关闭弹窗 true=总是关闭 int=匹配返回的code时关闭
 * @method $this refresh(bool $refresh) 请求成功后是否刷新页面，默认为true
 * @see Button
 * @author 莫耶尔
 */
class BtnAction extends Button
{

    /**
     * 提示信息
     * @var string $msg
     */
    protected $msg='';
    
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
        $this->addHtmlclass(['js-ajax-dialog-btn']);
        parent::tempInit();
    }
    protected function temp(){
        ?>
        <a
            class="<?= join(' ', $this->htmlclass);?>"
            type="submit"
            href="<?= url($this->action,$this->param);?>"
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
        </a>
        <?php
    }
}
