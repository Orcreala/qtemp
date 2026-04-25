<?php 
namespace qtemp\form;
use qtemp\form\FormControl;
use qtemp\form\FormValidator;
/**
 * 单图片上传
 * 输入框的id为name，图片预览的id为name-preview，文件名的id为name-filename,name为name_filename
 * @method $this default_src(string $default_src) 设置默认图片
 * @method $this filename(string $filename) 设置图片名
 * @method $this hasfilename(bool $hasfilename) 是否有文件名
 * @method $this help_text(string $help_text) 设置帮助文本，默认'允许上传格式为'
 * @see FormControl
 * @author 莫耶尔
 */
class ImageUpload extends FormControl
{
    /**
     * 图片样式
     * @var array
     */
    protected $htmlclass=[];
    /**
     * 默认图片
     * @var string 默认 cmf_get_root() . '/themes/' . cmf_get_current_admin_theme() . '/public/assets/images/default-thumbnail.png'
     */
    protected $default_src;
    protected $hasfilename=false;
    protected $filename="";
    /**
     * 构造函数
     */
    public function __construct()
    {
        parent::__construct();
        $this->default_src = cmf_get_root() . '/themes/' . cmf_get_current_admin_theme() . '/public/assets/images/default-thumbnail.png';
    }
    protected function temp()
    {
        $upload_img_src = $this->default_src;//thinkcmf默认图片
        if (isset($this->value) && !empty($this->value)) {
            $upload_img_src = cmf_get_image_preview_url($this->value);//thinkcmf图片路径
        }
        if(empty($this->help_text)){
            $this->help_text = self::uploadSettingText('image',true);
        }
    ?>
    <div class="<?= $this->name ?>upload-container image-upload-container"
        <?php $this->ConfigData();?>
    >
        <input 
            type="hidden"
            name="<?= $this->name ?>"
            id="<?= $this->name ?>"
            value="<?= (isset($this->value) ? $this->value : ''); ?>"
            <?php if ($this->required):?>required="required" <?php endif;?>
        >
        <a href="javascript:controlUploadOneImage_<?= $this->name ?>();">
            <img 
                src="<?= $upload_img_src ?>" 
                id="<?= $this->name ?>-preview" 
                class="<?= join(' ', $this->htmlclass); ?>"
                width="150"
                style="cursor: pointer;"
                alt="<?= $this->filename ?>"
            >
        </a>
        <?php if($this->hasfilename){(new TextInput)
            ->name($this->name.'_filename')
            ->value($this->filename)
            ->addHtmlclass('margin-top-10')
            ->addConfig(['style'=>'width: 150px;'])
            ->placeholder('图片名')
            ->echo();}?>
        <input 
            type="button" 
            class="btn btn-sm btn-cancel btn-danger" 
            value="取消图片" 
            id="<?= $this->name ?>-cancel"
            <?php if($this->required||empty($this->value)):?>style="display: none;"<?php endif;?>
            <?= ($this->required ? 'disabled' : '');?>
        >
    </div>
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            var image = document.getElementById('<?= $this->name ?>-preview');
            var input = document.getElementById('<?= $this->name ?>');
            var filename = document.getElementById('<?= $this->name ?>_filename');
            var cancel = document.getElementById('<?= $this->name ?>-cancel');
            var required=<?= ($this->required ? 'true' : 'false');?>;
            cancel.addEventListener('click', function() {
                image.src = '<?= $this->default_src ?>';
                image.alt = '';
                input.value = '';
                cancel.style.display = 'none';
                if(filename){
                    filename.value = '';
                }
            });
            input.addEventListener('change', function() {
                if (this.value === '' || this.value === null||required) {
                    cancel.style.display = 'none';
                } else {
                    cancel.style.display = '';
                }
            });
            // 定义特定于当前实例的函数
            window['controlUploadOneImage_<?= $this->name ?>'] = function() {
                openUploadDialog("图片上传", function (dialog, files) {
                    var name=files[0].name.replace(/\.[^.]+$/, '')
                    input.value=files[0].filepath;
                    image.src=files[0].preview_url;
                    image.alt = name;
                    if (!required) {
                        cancel.style.display = '';
                    }
                    if(filename){
                        filename.value =  name;
                    }
                }, [], 0, 'image');
            };
        });
    </script>
    <?php 
        if ($this->required) {
            FormValidator::getInstance()->registerImageValidator($this->name, $this->title);
        }
    }
    
}
