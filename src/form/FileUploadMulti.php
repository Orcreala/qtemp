<?php
namespace qtemp\form;
use qtemp\control\ArrayValue;
use qtemp\control\AnyToGet;
use qtemp\form\FormValidator;
include_once __DIR__ . '/_upload_multi_style.html';
/**
 * 多文件上传
 * 默认value接收字符串数组，filepath为数组元素值
 * @method $this getFilepath(string|int|callable|array|null $getFilepath) 获取文件路径的变量名的方式
 * @method $this getItemFilepath(mixed $item,mixed $key)从元素获取文件路径
 * @method $this getFilename(string|int|callable|array|null $getFilename) 获取文件文件名的变量名的方式
 * @method $this getItemFilename(mixed $item,mixed $key)从元素获取文件文件名
 * @method $this hasfilename(bool $hasfilename=true) 是否显示文件名输入框
 * @method $this filetype(string $filetype) 设置文件类型
 * @method $this help_text(string $help_text) 设置帮助文本，默认'最多上传max个文件；最少上传min个文件；允许上传格式为'
 * @see FormControl
 * @see ArrayValue
 * @see AnyToGet
 * @author 莫耶尔
 */
class FileUploadMulti extends FormControl
{

    use ArrayValue;
    use AnyToGet;
    /**
     * 文件样式
     * @var array
     */
    protected $htmlclass = [];
    /**
     * 文件路径的变量名的方式
     * @var string
     */
    protected $getFilepath = VALUE;
    /**
     * 文件文件名的变量名的方式
     * @var string
     */
    protected $getFilename = null;
    /**
     * 是否显示文件名输入框
     * @var bool
     */
    protected $hasfilename = false;
    /**
     * 文件类型
     * @var string
     */
    protected $filetype = 'file';
    protected function tempInit()
    {
        if (empty($this->help_text)) {
            if (!empty($this->max)) {
                $this->help_text .= '最多上传' . $this->max . '个文件；';
            }
            if (!empty($this->min)) {
                $this->help_text .= '最少上传' . $this->min . '个文件';
            }
            $this->help_text .= '<br>';
            $this->help_text .= self::uploadSettingText($this->filetype, true);
        }
    }
    protected function temp()
    {
        $name = $this->name;
        $title = $this->title;
        $min = $this->min;
        $max = $this->max;
        $required = $this->required;
        $value = $this->value;
        if (!$this->hasfilename) {
            $this->getFilename = null;
        }
        switch ($this->filetype) {
            case 'video':
                $fa = 'video-camera';
                break;
        }
        ?>
        <div class="<?= $this->name ?>upload-container multi-file-upload-container upload-container" <?php $this->ConfigData(); ?>>
            <ul id="<?= $name ?>-files" class="file-list list-unstyled form-inline">
                <?php if (!empty($value) && is_array($value)): ?>
                    <?php foreach ($value as $key => $item): ?>
                        <?php
                        $file_path = $this->getItemVar($this->getFilepath, $item, $key);
                        if (empty($file_path)) {
                            // 跳过无效项
                            continue;
                        }
                        $filename = $this->getItemVar($this->getFilename, $item, $key);
                        ?>
                        <li id="<?= $name ?>-saved-file<?= $key ?>">
                            <input id="<?= $name ?>-file-<?= $key ?>" type="hidden" name="<?= $name ?>[]" value="<?= $file_path ?>">
                            <div class="file-info" style="display: inline-block; vertical-align: middle; margin-right: 10px;">
                                <i class="fa fa-<?= $fa ?>"></i>
                                <span><?= $filename ?: basename($file_path) ?></span>
                            </div>
                            <?php if ($this->hasfilename): ?>
                                <input type="text" class="form-control" id="<?= $name ?>-file-<?= $key ?>-name"
                                    name="<?= $name ?>_filename[]" value="<?= $filename ?>" style="width:200px">
                            <?php endif; ?>
                            <a class="btn btn-default" href="javascript:uploadOneFile('文件上传','#<?= $name ?>-file-<?= $key ?>');"><i
                                    class="fa fa-upload fa-fw"></i></a>
                            <a class="btn btn-danger"
                                href="javascript:(function(){$('#<?= $name ?>-saved-file<?= $key ?>').remove();checkFileCount_<?= $name ?>();})();"><i
                                    class="fa fa-trash fa-fw"></i></a>
                            <a class="btn btn-success btn-up"
                                href="javascript:(function(){$('#<?= $name ?>-saved-file<?= $key ?>').insertBefore($('#<?= $name ?>-saved-file<?= $key ?>').prev());})();"><i
                                    class="fa fa-arrow-up fa-fw"></i></a>
                            <a class="btn btn-success btn-down"
                                href="javascript:(function(){$('#<?= $name ?>-saved-file<?= $key ?>').before($('#<?= $name ?>-saved-file<?= $key ?>').next());})();"><i
                                    class="fa fa-arrow-down fa-fw"></i></a>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
            <a href="javascript:controlUploadMultiFile_<?= $name ?>();" class="btn btn-default">选择文件</a>
        </div>
        <script type="text/html" id="<?= $name ?>-files-item-tpl">
            <li id="<?= $name ?>-saved-file{id}">
                <input id="<?= $name ?>-file-{id}" type="hidden" name="<?= $name ?>[]" value="{filepath}">
                <div class="file-info" style="display: inline-block; vertical-align: middle; margin-right: 10px;">
                    <i class="fa fa-<?= $fa ?>"></i>
                </div>
                <?php if ($this->hasfilename): ?>
                            <input type="text" class="form-control" style="width:200px" id="<?= $name ?>-file-{id}-name" name="<?= $name ?>_filename[]" value="{name}">
                <?php endif; ?>
                <a class="btn btn-default" href="javascript:uploadOne('文件上传','#<?= $name ?>-file-{id}','file');"><i class="fa fa-upload fa-fw"></i></a>
                <a class="btn btn-danger" href="javascript:(function(){$('#<?= $name ?>-saved-file{id}').remove();checkFileCount_<?= $name ?>();})();"><i class="fa fa-trash fa-fw"></i></a>
                <a class="btn btn-success btn-up" href="javascript:(function(){$('#<?= $name ?>-saved-file{id}').insertBefore($('#<?= $name ?>-saved-file{id}').prev());})();"><i class="fa fa-arrow-up fa-fw"></i></a>
                <a class="btn btn-success btn-down" href="javascript:(function(){$('#<?= $name ?>-saved-file{id}').before($('#<?= $name ?>-saved-file{id}').next());})();"><i class="fa fa-arrow-down fa-fw"></i></a>
            </li>
        </script>
        <script type="text/javascript">
            document.addEventListener('DOMContentLoaded', function () {
                var filesContainer = document.getElementById('<?= $name ?>-files');
                var minFiles = <?= $min ?>;
                var maxFiles = <?= $max > 0 ? $max : 0 ?>;
                var required = <?= $required ? 'true' : 'false' ?>;

                // 检查文件数量
                window['checkFileCount_<?= $name ?>'] = function () {
                    var fileCount = filesContainer.querySelectorAll('li').length;
                    if (maxFiles > 0 && fileCount >= maxFiles) {
                        alert('<?= $title ?>：最多只能上传 ' + maxFiles + ' 个文件');
                        return false;
                    }
                    return true;
                };

                // 多文件上传函数
                window['controlUploadMultiFile_<?= $name ?>'] = function () {
                    var currentCount = filesContainer.querySelectorAll('li').length;
                    if (maxFiles > 0 && currentCount >= maxFiles) {
                        alert('<?= $title ?>：最多只能上传 ' + maxFiles + ' 个文件');
                        return;
                    }

                    openUploadDialog("文件上传", function (dialog, files) {
                        var tpl = document.getElementById('<?= $name ?>-files-item-tpl').innerHTML;
                        var html = '';
                        var addedCount = 0;

                        for (var i = 0; i < files.length; i++) {
                            if (maxFiles > 0 && currentCount + addedCount >= maxFiles) {
                                alert('<?= $title ?>：最多只能上传 ' + maxFiles + ' 个文件');
                                break;
                            }

                            var item = files[i];
                            var itemtpl = tpl;
                            // 移除文件扩展名
                            var filename = item.name.replace(/\.[^.]+$/, '');
                            itemtpl = itemtpl.replace(/\{id\}/g, item.id);
                            itemtpl = itemtpl.replace(/\{filepath\}/g, item.filepath);
                            <?php if ($this->hasfilename): ?>
                                itemtpl = itemtpl.replace(/\{name\}/g, filename);
                            <?php endif; ?>
                            html += itemtpl;
                            addedCount++;
                        }

                        if (html) {
                            filesContainer.innerHTML += html;
                        }
                    }, [], 1, '<?= $this->filetype ?>');
                };
            });
        </script>
        <?php
        if ($this->required || $this->min > 0) {
            FormValidator::getInstance()->registerMultiFileValidator($this->name . '-files', $this->title, $this->min);
        }
    }

}