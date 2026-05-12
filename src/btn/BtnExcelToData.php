<?php
namespace qtemp\btn;

/**
 * Excel导入按钮，将上传的Excel文件转为数据向控制器请求
 * @method $this column(array $column) 设置列，以及字段名，键为首行名称，值为字段名
 * @method $this shiftFirst(bool $shiftFirst=true) 是否移除第一行
 * @author 莫耶尔
 * @see Button
 */
class BtnExcelToData extends Button
{

    /**
     * 列，以及字段名，键为首行名称，值为字段名
     * @var array[string=>string]
     */
    protected array $column = [];
    protected bool $shiftFirst = false;

    protected function tempInit():void{
        include_once __DIR__ . '/_xlsx_full_min.html';
        $this->addHtmlclass(['import-excel-btn']);
        parent::tempInit();
    }
    protected function temp()
    {
        ?>
        <button type="button" class="<?= join(' ', $this->htmlclass); ?>" 
            id="<?= $this->id ?>"
            <?php if (!empty($this->action)): ?>
            data-action="<?= url($this->action, $this->param); ?>" <?php endif; ?>
            <?php if ($this->disabled): ?> disabled="disabled" <?php endif; ?>
            <?php $this->ConfigData(); ?>
        >
            <?php if (!empty($this->fa)): ?>
                <i class="fa fa-<?= $this->fa; ?> fa-fw"></i>
            <?php endif; ?>
            <?= $this->text; ?>
        </button>

        <div id="<?= $this->id ?>_importPreview" style="display: none;"></div>
        <script>
            const titles = <?= json_encode(array_keys($this->column), JSON_UNESCAPED_UNICODE) ?>;
            const columns = <?= json_encode(array_values($this->column)) ?>;
            document.getElementById('<?= $this->id ?>').addEventListener('click', function () {
                console.log('点击导入按钮');
                const input = document.createElement('input');
                input.type = 'file';
                input.accept = '.xlsx, .xls';
                input.onchange = function (event) {
                    const file = event.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function (e) {
                            const data = new Uint8Array(e.target.result);
                            const workbook = XLSX.read(data, { type: 'array' });
                            // 获取第一个工作表
                            const sheetName = workbook.SheetNames[0];
                            const worksheet = workbook.Sheets[sheetName];

                            // 将工作表数据转换为JSON
                            const jsonData = XLSX.utils.sheet_to_json(worksheet, { header: 1 });
                            // 移除第一行（表头）
                            <?php if ($this->shiftFirst) { ?>
                                jsonData.shift();
                            <?php } ?>
                            // 过滤掉包含未定义值或其他无效值的行
                            const filteredData = jsonData.filter(row => {
                                if (row.lenght > columns.length) {
                                    row.splice(columns.length);
                                }
                                // 检查这一行是否至少有一列不为空
                                return row.some(cell => cell !== undefined && cell !== null && cell !== '');
                            });
                            // 展示即将导入的数据
                            setTimeout(function () {
                                <?= $this->id ?>_displayImportData(filteredData);
                            }, 100);
                        };
                        reader.readAsArrayBuffer(file);
                    }
                };
                input.click();
            });

            function <?= $this->id ?>_displayImportData(data) {
                document.getElementById('<?= $this->id ?>_importPreview').innerHTML = '';
                document.getElementById('<?= $this->id ?>_importPreview').style.display = 'block';
                //表头
                let thead = ``;
                titles.forEach(title => {
                    //渲染表格头
                    thead += `<th>${title}</th>`;
                }
                );

                //提交数据
                let sumbit_data = [];
                //表内容
                let tbody = ``;
                data.forEach(row => {
                    let row_data = {};
                    //渲染表格行
                    tbody += `
                    <tr>`
                    let i = 0;
                    columns.forEach((column, index) => {
                        //渲染表格单元格
                        tbody += `<td>${row[index] || ''}</td>`;
                        row_data[column] = row[index] || '';
                    });
                    sumbit_data.push(row_data);
                    `</tr>`;
                });
                let tableHtml =
                    `<table class="table table-bordered">
                    <thead><tr>
                    ${thead}
                    </tr></thead>
                    <tbody>
                    ${tbody}
                    </tbody>
                    </table>`;

                tableHtml += '<button id="<?= $this->id ?>_confirmImportBtn" class="btn btn-primary">确认导入</button>';

                document.getElementById('<?= $this->id ?>_importPreview').innerHTML = tableHtml;

                // 使用setTimeout确保DOM元素已渲染后再添加事件监听器
                setTimeout(function () {
                    const confirmBtn = document.getElementById('<?= $this->id ?>_confirmImportBtn');
                    if (confirmBtn) {
                        confirmBtn.addEventListener('click', function () {
                            // 使用jQuery ajax替代fetch，以便更好地兼容ThinkCMF框架
                            $.ajax({
                                url: "<?= url($this->action, $this->param); ?>",
                                type: 'POST',
                                dataType: 'json',
                                contentType: 'application/json',
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest'
                                },
                                data: JSON.stringify({ data: sumbit_data }),
                                success: function (result) {
                                    if (result.code == 1) {
                                        let msg = '';
                                        if (result.msg) {
                                            msg=result.msg;
                                        }else{
                                            msg = '导入成功！';
                                        }
                                        if (result.data) {
                                            if(result.data.msg){
                                                msg += '\n' + result.data.msg;
                                            }
                                            if ((result.data.all!==undefined) && (result.data.success!==undefined)) {
                                                msg += '共' + result.data.all + '条数据\n' + result.data.success + '条数据已导入成功！';
                                            }
                                        }
                                        // 导入成功后恢复到初始状态
                                        document.getElementById('<?= $this->id ?>_importPreview').innerHTML = '';
                                        document.getElementById('<?= $this->id ?>_importPreview').style.display = 'none';
                                        alert(msg);
                                    } else {
                                        alert(result.msg);
                                    }
                                },
                                error: function (result) {
                                    if (!result.message) {
                                        alert('导入失败！请检查数据格式是否正确。');
                                        return;
                                    }
                                    alert(result.message);
                                }
                            });
                        });
                    } else {
                        console.error('找不到确认导入按钮元素：<?= $this->id ?>_confirmImportBtn');
                    }
                }, 100);
            }
        </script>
        <?php

    }
}