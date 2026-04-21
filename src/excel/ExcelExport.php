<?php
namespace qtemp\excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
/**
 * 导出Excel
 * 需求PhpOffice库
 * @method $this column(array $column) 列,key为表头，value为数据获取方式(value可为一个回调函数，参数为此条数据)
 * @method $this addColumn(array $column) 添加列,key为表头，value为数据获取方式(value可为一个回调函数，参数为此条数据)
 * @method $this data(array $data) 设置数据
 * @method $this addData(array $data) 添加数据
 * @method $this name(string $name) 设置导出文件名
 * @method $this limit(int $limit) 设置导出数据量限制
 * @method $this export() 导出并下载Excel
 * @author 莫耶尔
 */
class ExcelExport extends \qtemp\Qtemp
{
    use \qtemp\control\AnyToGet;
    protected array $column = [];
    protected array $data = [];
    protected string $name = '';
    protected int $limit = 10000;

    public function __construct()
    {
        if (!class_exists(Spreadsheet::class)) {
            throw new \Exception('使用 ExcelExport 需要安装 phpoffice/phpspreadsheet: composer require phpoffice/phpspreadsheet');
        }
        return parent::__construct();
    }
    /**
     * 导出并下载Excel
     */
    public function export()
    {
        try {
            // 设置默认导出名称
            $exportName = $this->name ?: 'export_' . date('Y-m-d_H-i-s');

            // 获取表头
            $exportData = [array_keys($this->column)];

            if (count($this->data) > $this->limit) {
                $this->data = array_slice($this->data, 0, $this->limit);
            }

            // 直接构建导出数据
            foreach ($this->data as $key => $item) {
                $row = [];
                foreach ($this->column as $get) {
                    $row[] = $this->getItemVar($get, $item, $key);
                }
                $exportData[] = $row;
            }
            // 如果没有数据，直接返回空数组
            if (count($exportData) <= 1) {
                // 返回JSON提示信息而不是空数组
                header('Content-Type: application/json');
                return json_encode(['code' => 1, 'msg' => '没有可导出的数据']);
            }
            // 创建一个新的Spreadsheet对象
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // 填充数据到工作表
            foreach ($exportData as $rowIndex => $rowData) {
                $columnIndex = 1;
                foreach ($rowData as $cellData) {
                    // 将列数字转换为字母
                    $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($columnIndex);
                    // 使用setCellValue方法设置单元格值
                    $sheet->setCellValue($columnLetter . ($rowIndex + 1), $cellData);
                    $columnIndex++;
                }
            }

            // 清理输出缓冲区
            if (ob_get_level()) {
                ob_end_clean();
            }

            // 设置响应头，以附件形式下载Excel文件
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . urlencode($exportName) . '.xlsx"');
            header('Cache-Control: max-age=0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Expires: 0');

            // 创建Xlsx写入器并输出到浏览器
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
            exit;
        } catch (\Exception $e) {
            // 错误处理
            header('Content-Type: application/json');
            echo json_encode([
                'code' => 0,
                'msg' => $e->getMessage()
            ]);
            exit;
        }
    }

}