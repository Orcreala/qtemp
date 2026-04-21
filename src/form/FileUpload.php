<?php 
namespace qtemp\form;
use qtemp\form\FormValidator;
/**
 * 单文件上传
 * 输入框的id为name，文件预览的id为name-preview，文件名的id为name-filename
 * @method $this filename(string $filename) 设置文件名
 * @method $this hasfilename(bool $hasfilename) 是否显示文件名输入框
 * @method $this filetype(string $filetype) 设置文件类型
 * @see FormControl
 * @author 莫耶尔
 */
class FileUpload extends FormControl
{
    /**
     * 文件样式
     * @var array
     */
    protected $htmlclass=[];
    protected $hasfilename=false;
    protected $filename='';

    protected $filetype='file';
    protected function temp()
    {
        /**
         * 上传设置
         */
        $upload_setting = cmf_get_upload_setting();
        /**
         * 文件类型设置
         */
        $max_size = 0;
        $extensions = '';
        $type_setting = \qtemp\getValue($upload_setting, ['file_types', $this->filetype]);
        if (!empty($type_setting)) {
            $max_size = isset($type_setting['upload_max_filesize']) ? $type_setting['upload_max_filesize'] : 0;
            $extensions = isset($type_setting['extensions']) ? $type_setting['extensions'] : '';
        }
        $fa='file';
        switch($this->filetype){
            case 'video':
                $fa='video-camera';
                break;
        }
        
    ?>
    <div class="<?= $this->name ?>upload-container file-upload-container"
        <?php $this->ConfigData();?>
    >
        <input 
            type="hidden"
            name="<?= $this->name ?>"
            id="<?= $this->name ?>"
            value="<?= (isset($this->value) ? $this->value : ''); ?>"
            <?php if ($this->required):?>required="required" <?php endif;?>
        >
        <div class="file-upload-preview" id="<?= $this->name ?>-preview" style="margin-bottom: 10px;">
            <?php if (isset($this->value) && !empty($this->value)): ?>
                <div class="file-info">
                    <i class="fa fa-<?= $fa?>"></i>
                    <span><?= $this->filename ?></span>
                </div>
            <?php else: ?>
                <div class="file-placeholder">
                    <i class="fa fa-upload"></i>
                    <span>点击上传文件</span>
                </div>
            <?php endif; ?>
        </div>
        <?php 
        if($this->hasfilename):
            (new TextInput)->name($this->name.'-filename')
            ->value($this->filename)
            ->addHtmlclass('margin-top-10')
            ->addConfig(['style'=>'width: 300px;'])
            ->placeholder('文件名')
            ->echo();
            endif;
        ?>
        <?php if (!empty($upload_setting)): ?>
            <p class="help-block">
                <?php if (!empty($max_size)): ?>
                    允许上传大小<?= $max_size ?>KB,1M=1024KB
                <?php endif; ?>
                <br>
                <?php if (!empty($extensions)): ?>
                    允许上传格式为<?= $extensions ?>
                <?php endif; ?>
            </p>
        <?php endif; ?>
        <br>
        <input 
            type="button" 
            class="btn btn-sm btn-cancel btn-danger" 
            value="取消文件" 
            id="<?= $this->name ?>-cancel"
            <?php if($this->required||empty($this->value)):?>style="display: none;"<?php endif;?>
            <?= ($this->required ? 'disabled' : '');?>
        >
    </div>
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            var preview = document.getElementById('<?= $this->name ?>-preview');
            var input = document.getElementById('<?= $this->name ?>');
            <?php if($this->hasfilename):?>
            var filename = document.getElementById('<?= $this->name ?>-filename');
            <?php endif;?>
            var cancel = document.getElementById('<?= $this->name ?>-cancel');
            var required=<?= ($this->required ? 'true' : 'false');?>;
            
            // 点击预览区域触发上传
            preview.addEventListener('click', function() {
                window['controlUploadOneFile_<?= $this->name ?>']();
            });
            
            cancel.addEventListener('click', function() {
                preview.innerHTML = '<div class="file-placeholder"><i class="fa fa-upload"></i><span>点击上传文件</span></div>';
                input.value = '';
                cancel.style.display = 'none';
                
                <?php if($this->hasfilename):?>
                    filename.value = '';
                <?php endif;?>
                // 重新绑定点击事件
                preview.addEventListener('click', function() {
                    window['controlUploadOneFile_<?= $this->name ?>']();
                });
            });
            
            input.addEventListener('change', function() {
                if (this.value === '' || this.value === null||required) {
                    cancel.style.display = 'none';
                } else {
                    cancel.style.display = '';
                }
            });
            
            // 定义特定于当前实例的函数
            window['controlUploadOneFile_<?= $this->name ?>'] = function() {
                openUploadDialog("文件上传", function (dialog, files) {
                    var name=files[0].name.replace(/\.[^.]+$/, '');
                    input.value=files[0].filepath;
                    preview.innerHTML = '<div class="file-info"><i class="fa fa-<?= $fa?>"></i><span>' + (name) + '</span></div>';
                    if (!required) {
                        cancel.style.display = '';
                    }
                    <?php if($this->hasfilename):?>
                        filename.value =  name;
                    <?php endif;?>
                }, [], 0, '<?= $this->filetype ?>');
            };
        });
    </script>
    <?php 
        if ($this->required) {
            FormValidator::getInstance()->registerFileValidator($this->name, $this->title);
        }
    }
    
}
