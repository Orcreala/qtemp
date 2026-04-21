<?php
namespace qtemp\control;
use qtemp\btn\BtnAction;
use qtemp\btn\BtnTarget;
use qtemp\btn\Button;

include_once __DIR__ .'/_table_style.html';
/**
 * 仅有基础功能的表格，相对更适合展示已经为适合展示的形式的数据
 * @method $this list(array $list) 列表
 * @method $this column(array $column) 列,key为表头，value为数据获取方式(value可为一个回调函数，参数为此条数据)
 * @method $this addColumn(array $column) 添加列,key为表头，value为数据获取方式(value可为一个回调函数，参数为此条数据)
 * @method $this actionButton(array $actionButton) 操作栏按钮，显示在操作栏的按钮将自动执行addParam([$this->primary => $item[$this->primary]])
 * @method $this addActionButton(array|actionButton) 添加操作栏按钮，显示在操作栏的按钮将自动执行addParam([$this->primary => $item[$this->primary]])
 * @method $this primary(string $primary) 主键，用于自动生成编辑和删除按钮
 * @method $this check(bool $check=true) 是否有选择
 * @method $this listOrder(string $listOrder) 排序的字段，若为空则不自动生成排序按钮
 * @method $this autoAction(bool $autoAction=true) 是否自动生成操作栏
 * @method $this edit(string $edit) 生成编辑按钮的控制器方法，设置空则不生成，生成需要自动生成操作栏为true
 * @method $this delete(string $delete) 生成删除按钮的控制器方法，设置空则不生成，生成需要自动生成操作栏为true
 * @see Control
 * @see \qtemp\control\AnyToGet
 * @author 莫耶尔
 */
class TableMin extends Control
{
    use \qtemp\control\AnyToGet;
    protected $htmlclass = ['table', 'table-striped', 'table-hover', 'table-bordered', 'margin-top-20'];
    /**
     * 列表
     * @var array
     */
    protected array $list = [];
    /**
     * 列，以及如何获取数据，也可传入一个回调函数
     * @var array[string=>string|int|callable|null]
     */
    protected array $column = [];
    /**
     * 是否自动生成操作栏
     * @var bool $autoAction
     */
    protected bool $autoAction = false;
    /**
     * 自动生成行选中
     * @var bool $check
     */
    protected bool $check = false;

    /**
     * 排序字段，若为空则不自动生成排序按钮
     * @var string $listOrder
     */
    protected string $listOrder = '';
    /**
     * 编辑的控制器方法，若为空则不自动生成编辑按钮
     * @var string $edit
     */
    protected string $edit = 'edit';
    /**
     * 删除的控制器方法，若为空则不自动生成删除按钮
     * @var string $delete
     */
    protected string $delete = 'delete';
    /**
     * 主键，用于自动生成编辑和删除按钮
     * @var string $primary
     */
    protected string $primary = 'id';
    /**
     * 操作栏按钮
     * @var array $actionButton
     */
    protected array $actionButton = [];
    public function addButton(Button|array $button)
    {
        if (is_array($button)) {
            $this->actionButton = array_merge($this->actionButton, $button);
        } else {
            $this->actionButton[] = $button;
        }
        return $this;
    }
    /**
     * 是否有自带的操作栏
     * @return bool
     */
    protected function needAutoAction(): bool
    {
        return ($this->edit || $this->delete || count($this->actionButton) > 0) && $this->autoAction;
    }
    protected function temp()
    {
        if ($this->check) {
            $this->addHtmlclass('js-check-wrap');
        }
        if($this->listOrder){
            $this->addHtmlclass('table-list');
        }
        if ($this->needAutoAction()) {
            if ($this->edit) {
                $this->addButton((new BtnTarget)->btnclass('primary')
                    ->fa('edit')
                    ->size('sm')
                    ->action($this->edit)
                    ->text('编辑'));
            }
            if ($this->delete) {
                $this->addButton(
                    (new BtnAction)->btnclass('danger')
                        ->fa('trash-o')
                        ->size('sm')
                        ->action($this->delete)
                        ->text('删除')
                        ->msg('确定要删除吗？')
                );
            }
        }
        ?>
        <table class="<?= join(' ', $this->htmlclass); ?>"
            <?php $this->ConfigData();?>
        >
            <thead>
                <tr>
                    <?php if ($this->check): ?>
                        <th width="15">
                            <label>
                                <input type="checkbox" class="js-check-all" data-direction="x" data-checklist="js-check-x">
                            </label>
                        </th>
                    <?php endif; ?>
                    <?php if($this->listOrder): ?>
                        <th width="50">排序</th>
                    <?php endif; ?>
                    <?php foreach ($this->column as $key => $column): 
                        $keyarr = explode(':', $key);
                        $title=reset($keyarr);
                        $width=null;
                        if(count($keyarr)==2){
                            $width = end($keyarr);
                        }
                    ?>
                        <th <?php if($width):?>width="<?= $width ?>"<?php endif;?>><?= $title ?></th>
                    <?php endforeach; ?>
                    <?php if ($this->needAutoAction()): ?>
                        <th width="<?php
                            $width=8;
                            foreach ($this->actionButton as $button){
                                $width+=strlen($button->text)*6+8;
                                if($button->fa){
                                    $width+=10;
                                }
                            }
                            echo $width;?>">操作</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($this->list as $key => $item):
                    ?>
                    <tr>
                        <?php if ($this->check): ?>
                            <td>
                                <input type="checkbox" class="js-check" data-yid="js-check-y" data-xid="js-check-x"
                                    name="<?= $this->primary ?>s[]" value="<?= $item[$this->primary] ?>"
                                    title="ID:<?= $item[$this->primary] ?>">
                            </td>
                        <?php endif; ?>
                        <?php if($this->listOrder): ?>
                            <td>
                                <input class="input-order" name="<?= 
                                $this->listOrder ?>s[<?= $item[$this->primary] ?>]" 
                                value="<?= $item[$this->listOrder]??1000 ?>">
                            </td>
                        <?php endif; ?>
                        <?php foreach ($this->column as $key => $column): ?>
                            <td><?= $this->getItemVar($column, $item, $key); ?></td>
                        <?php endforeach; ?>
                        <?php if ($this->needAutoAction()): ?>
                            <td>
                                <?php foreach ($this->actionButton as $button):
                                    $button = clone $button;
                                    $button->addParam([$this->primary => $item[$this->primary]])->echo();
                                    ?>
                                <?php endforeach; ?>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <?php if ($this->check): ?>
                        <th width="15">
                            <label>
                                <input type="checkbox" class="js-check-all" data-direction="x" data-checklist="js-check-x">
                            </label>
                        </th>
                    <?php endif; ?>
                    <?php if($this->listOrder): ?>
                        <th width="50">排序</th>
                    <?php endif; ?>
                    <?php foreach ($this->column as $key => $column): 
                        $keyarr = explode(':', $key);
                        $title=reset($keyarr);
                    ?>
                        <th><?= $title ?></th>
                    <?php endforeach; ?>
                    <?php if ($this->needAutoAction()): ?>
                        <th>操作</th>
                    <?php endif; ?>
                </tr>
            </tfoot>
        </table>
        <?php
    }
}