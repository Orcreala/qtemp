<?php 
namespace qtemp\control;
use qtemp\control\AnyToGet;
/**
 * 导航栏
 * 默认导航数组的key为url，value为文本，无参数，自动判断当前的方法名和url是否相等
 * @method $this tab(array $tabs) 导航数组
 * @method $this getText(string|int|callable $getText) 设置获取文本
 * @method $this getActive(string|int|callable $getActive) 设置获取激活状态
 * @method $this getUrl(string|int|callable $getUrl) 设置获取URL
 * @see Control 
 * @author 莫耶尔
 */
class NavTab extends Control
{
    use AnyToGet;
    protected $htmlclass = ['nav', 'nav-tabs'];
    protected $tab = [];
    public function addTab(array $tabs)
    {
        $this->tab=array_merge($this->tab,$tabs);
        return $this;
    }
    protected $getText=VALUE;
    protected $getActive=null;
    protected $getUrl=KEY;
    public function __construct(){
        $this->getActive = function ($tab,$key=null): bool {
            $controller=request()->controller();
            $action=request()->action();
            $url=$this->getItemVar($this->getUrl,$tab,$key);
            if(count(explode('/',$url))> 1){
                $size=count(explode('/',$url));
                $url=explode('/',$url)[$size-1];
                $control=explode('/',$controller)[$size-1];
                return $url === $action && $control === $controller;
            }else{
                return $url === $action;
            }
        };
    }
    protected function temp(){
        ?>
        <ul class="<?= join(' ',$this->htmlclass); ?>"
            <?php $this->ConfigData();?>
        >
            <?php foreach ($this->tab as $key => $tab) : 
                $active=$this->getItemVar($this->getActive,$tab,$key);
                $url=$this->getItemVar($this->getUrl,$tab,$key);
                ?>
                <li <?php if ($active) : ?> class="active"<?php endif; ?>>
                    <a  <?php if (!$active) : ?>href="<?= $url; ?>" <?php endif; ?>
                >
                        <?= $this->getItemVar($this->getText,$tab,$key);  ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
        <?php
    }
}
