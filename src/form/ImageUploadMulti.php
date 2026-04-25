<?php
namespace qtemp\form;
use qtemp\control\ArrayValue;
use qtemp\control\AnyToGet;
use qtemp\form\FormValidator;
include_once __DIR__ . '/_upload_multi_style.html';
/**
 * 多图片上传
 * 默认value接收字符串数组，filepath为数组元素值
 * @method $this getFilepath(string|int|callable|array|null $getFilepath) 获取图片路径的变量名的方式
 * @method $this getItemFilepath(mixed $item,mixed $key)从元素获取图片路径
 * @method $this getFilename(string|int|callable|array|null $getFilename) 获取图片文件名的变量名的方式
 * @method $this getItemFilename(mixed $item,mixed $key)从元素获取图片文件名
 * @method $this hasfilename(bool $hasfilename=true) 是否有文件名
 * @method $this helpText(string $helpText) 设置帮助文本，默认'最多上传max张图片；最少上传min张图片；允许上传格式为'
 * @see FormControl
 * @see ArrayValue
 * @see AnyToGet
 * @author 莫耶尔
 */
class ImageUploadMulti extends FormControl
{

    use ArrayValue;
    use AnyToGet;
    /**
     * 图片样式
     * @var array
     */
    protected $htmlclass = [];

    protected $hasfilename = false;
    protected $getFilepath = VALUE;
    protected $getFilename = null;
    /**
     * 构造函数
     */
    public function __construct()
    {
        parent::__construct();

    }
    protected function tempInit()
    {
        if (empty($this->helpText)) {
            if (!empty($this->max)) {
                $this->helpText .= '最多上传' . $this->max . '张图片；';
            }
            if (!empty($this->min)) {
                $this->helpText .= '最少上传' . $this->min . '张图片';
            }
            $this->helpText .= '<br>';
            $this->helpText .= self::uploadSettingText('image', true);
        }
    }
    protected function temp()
    {
        if (!$this->hasfilename) {
            $this->getFilename = null;
        }
        $name = $this->name;
        $title = $this->title;
        $min = $this->min;
        $max = $this->max;
        $required = $this->required;
        $value = $this->value;
        ?>
        <div class="<?= $this->name ?>upload-container multi-image-upload-container upload-container" <?php $this->ConfigData(); ?>>
            <ul id="<?= $name ?>-photos" class="pic-list list-unstyled form-inline">
                <?php if (!empty($value) && is_array($value)): ?>
                    <?php foreach ($value as $key => $item): ?>
                        <?php
                        $img_path = $this->getItemVar($this->getFilepath, $item, $key);
                        if (empty($img_path)) {
                            // 跳过无效项
                            continue;
                        }
                        $img_url = cmf_get_image_preview_url($img_path);
                        $filename = $this->getItemVar($this->getFilename, $item, $key);
                        ?>
                        <li id="<?= $name ?>-saved-image<?= $key ?>">
                            <input id="<?= $name ?>-photo-<?= $key ?>" type="hidden" name="<?= $name ?>[]" value="<?= $img_path ?>">
                            <img id="<?= $name ?>-photo-<?= $key ?>-preview" src="<?= $img_url ?>" style="height:34px;width: 44px;"
                                onclick="imagePreviewDialog(this.src);">
                            <?php if ($this->hasfilename): ?>
                                <input type="text" class="form-control" id="<?= $name ?>-photo-<?= $key ?>-name"
                                    name="<?= $name ?>_filename[]" value="<?= $filename ?>" style="width:200px">
                            <?php endif; ?>
                            <a class="btn btn-default" href="javascript:uploadOneImage('图片上传','#<?= $name ?>-photo-<?= $key ?>');"><i
                                    class="fa fa-upload fa-fw"></i></a>
                            <a class="btn btn-danger"
                                href="javascript:(function(){$('#<?= $name ?>-saved-image<?= $key ?>').remove();checkImageCount_<?= $name ?>();})();"><i
                                    class="fa fa-trash fa-fw"></i></a>
                            <a class="btn btn-success btn-up"
                                href="javascript:(function(){$('#<?= $name ?>-saved-image<?= $key ?>').insertBefore($('#<?= $name ?>-saved-image<?= $key ?>').prev());})();"><i
                                    class="fa fa-arrow-up fa-fw"></i></a>
                            <a class="btn btn-success btn-down"
                                href="javascript:(function(){$('#<?= $name ?>-saved-image<?= $key ?>').before($('#<?= $name ?>-saved-image<?= $key ?>').next());})();"><i
                                    class="fa fa-arrow-down fa-fw"></i></a>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
            <a href="javascript:controlUploadMultiImage_<?= $name ?>();" class="btn btn-default">选择图片</a>
        </div>
        <script type="text/html" id="<?= $name ?>-photos-item-tpl">
            <li id="<?= $name ?>-saved-image{id}">
                <input id="<?= $name ?>-photo-{id}" type="hidden" name="<?= $name ?>[]" value="{filepath}">
                <img id="<?= $name ?>-photo-{id}-preview" src="{url}" style="height:34px;max-width: 34px;object-fit: cover;"
                        onclick="imagePreviewDialog(this.src);">
                <?php if ($this->hasfilename): ?>
                            <input type="text" class="form-control" style="width:200px" id="<?= $name ?>-photo-{id}-name" name="<?= $name ?>_filename[]" value="{name}">
                <?php endif; ?>
                <a class="btn btn-default" href="javascript:uploadOneImage('图片上传','#<?= $name ?>-photo-{id}');"><i class="fa fa-upload fa-fw"></i></a>
                <a class="btn btn-danger" href="javascript:(function(){$('#<?= $name ?>-saved-image{id}').remove();checkImageCount_<?= $name ?>();})();"><i class="fa fa-trash fa-fw"></i></a>
                <a class="btn btn-success btn-up" href="javascript:(function(){$('#<?= $name ?>-saved-image{id}').insertBefore($('#<?= $name ?>-saved-image{id}').prev());})();"><i class="fa fa-arrow-up fa-fw"></i></a>
                <a class="btn btn-success btn-down" href="javascript:(function(){$('#<?= $name ?>-saved-image{id}').before($('#<?= $name ?>-saved-image{id}').next());})();"><i class="fa fa-arrow-down fa-fw"></i></a>
            </li>
        </script>
        <script type="text/javascript">
            document.addEventListener('DOMContentLoaded', function () {
                var photosContainer = document.getElementById('<?= $name ?>-photos');
                var minImages = <?= $min ?>;
                var maxImages = <?= $max > 0 ? $max : 0 ?>;
                var required = <?= $required ? 'true' : 'false' ?>;

                // 检查图片数量
                window['checkImageCount_<?= $name ?>'] = function () {
                    var imageCount = photosContainer.querySelectorAll('li').length;
                    if (maxImages > 0 && imageCount >= maxImages) {
                        alert('<?= $title ?>：最多只能上传 ' + maxImages + ' 张图片');
                        return false;
                    }
                    return true;
                };

                // 多图片上传函数
                window['controlUploadMultiImage_<?= $name ?>'] = function () {
                    var currentCount = photosContainer.querySelectorAll('li').length;
                    if (maxImages > 0 && currentCount >= maxImages) {
                        alert('<?= $title ?>：最多只能上传 ' + maxImages + ' 张图片');
                        return;
                    }

                    openUploadDialog("图片上传", function (dialog, files) {
                        var tpl = document.getElementById('<?= $name ?>-photos-item-tpl').innerHTML;
                        var html = '';
                        var addedCount = 0;

                        for (var i = 0; i < files.length; i++) {
                            if (maxImages > 0 && currentCount + addedCount >= maxImages) {
                                alert('<?= $title ?>：最多只能上传 ' + maxImages + ' 张图片');
                                break;
                            }

                            var item = files[i];
                            var itemtpl = tpl;
                            // 移除文件扩展名
                            var filename = item.name.replace(/\.[^.]+$/, '');
                            itemtpl = itemtpl.replace(/\{id\}/g, item.id);
                            itemtpl = itemtpl.replace(/\{url\}/g, item.url);
                            itemtpl = itemtpl.replace(/\{preview_url\}/g, item.preview_url);
                            itemtpl = itemtpl.replace(/\{filepath\}/g, item.filepath);

                            <?php if ($this->hasfilename): ?>
                                itemtpl = itemtpl.replace(/\{name\}/g, filename);
                            <?php endif; ?>
                            html += itemtpl;
                            addedCount++;
                        }

                        if (html) {
                            photosContainer.innerHTML += html;
                        }
                    }, [], 1, 'image');
                };
            });
        </script>
        <?php
        if ($this->required || $this->min > 0) {
            FormValidator::getInstance()->registerMultiImageValidator($this->name . '-photos', $this->title, $this->min);
        }
    }

}
