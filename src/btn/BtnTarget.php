<?php
namespace qtemp\btn;
/**
 * 跳转按钮
 * @method $this layer(string $title='',string $width='80%',string $height='80%') 是否开启弹窗
 * @method $this height(string $height) 设置弹窗高度(仅弹窗时有效)
 * @method $this width(string $width) 设置弹窗宽度(仅弹窗时有效)
 * @method $this title(string $title) 设置弹窗标题(仅弹窗时有效)
 * @method $this target(string $target) 设置跳转形式(仅不为弹窗时有效)
 * @see Button
 * @author 莫耶尔
 */
class BtnTarget extends Button
{
    protected bool $layer = false;
    public function layer($title = '', $width = '80%', $height = '80%')
    {
        $this->layer = true;
        if (empty($title)) {
            $title = $this->text;
        } else {
            $this->title = $title;
        }
        $this->width = $width;
        $this->height = $height;
        return $this;
    }
    protected string $height = '';
    protected string $width = '';
    protected string $title = '';
    protected string $target = '_self';

    protected function tempInit():void{
        include_once __DIR__ . '/_openLayer.html';
        parent::tempInit();
    }

    protected function temp()
    {
        $url = url($this->action, $this->param);
        ?>
        <a 
            id="<?= $this->id; ?>"
            class="<?= join(' ', $this->htmlclass); ?>" <?php if ($this->layer): ?>
            onclick="openLayer('<?= $this->title; ?>','<?= $this->width; ?>','<?= $this->height; ?>','<?= $url; ?>')" <?php else: ?>
            href="<?= $url; ?>" target="<?= $this->target; ?>" <?php endif; ?>         <?php if ($this->disabled): ?> disabled="disabled"
            <?php endif; ?>         <?php $this->ConfigData(); ?>>
            <?php if (!empty($this->fa)): ?>
                <i class="fa fa-<?= $this->fa; ?> fa-fw"></i>
            <?php endif; ?>
            <?= $this->text; ?>
        </a>
        <?php
    }
}