<?php 
namespace qtemp\form;
include_once __DIR__ .'/_baidu.html';
/**
 * 百度编辑器
 * @author 莫耶尔
 * @see FormControl
 */
class BaiduEditor extends FormControl{



    protected function temp(){
        ?>
        <script type="text/plain" 
            id="<?= $this->name ?>" 
            name="<?= $this->name ?>"
        >
            <?= $this->value ?>
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                editor_<?= $this->name ?> = new baidu.editor.ui.Editor();
                editor_<?= $this->name ?>.render('<?= $this->name ?>');
                try {
                    editor_<?= $this->name ?>.sync();
                } catch (err) {
                }
                document.getElementById('<?= $this->name ?>').value = '<?= $this->value ?>';
            });
        </script>
        <?php
    }
}