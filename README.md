# qtemp 集成式工具包

qtemp 是一个为 ThinkCMF 框架设计的集成式工具包，提供了丰富的 UI 控件、表单组件和 Excel 处理功能，帮助开发者快速构建后台管理界面。
总的说，我还想做一个无手动编码的后台系统，但一个足够集成化的模板库是前置条件

## 目录结构

```
app/qtemp/
├── Qtemp.php              # 核心基类，提供魔术方法支持
├── manifest.json          # 包配置文件
├── header.php             # 公共头文件
├── control/               # UI 控件
│   ├── Control.php        # 控件基类
│   ├── Button.php         # 按钮控件
│   ├── BtnAction.php      # 动作按钮
│   ├── BtnExcelToData.php # Excel 导入按钮
│   ├── BtnSubmit.php      # 提交按钮
│   ├── BtnTarget.php      # 目标按钮
│   ├── NavTab.php         # 导航标签
│   └── TableMin.php       # 迷你表格
├── form/                  # 表单控件
│   ├── FormControl.php    # 表单基类
│   ├── FormValidator.php  # 表单验证器
│   ├── TextInput.php      # 文本输入框
│   ├── Textarea.php       # 多行文本框
│   ├── Label.php          # 标签
│   ├── SelectControl.php  # 选择框基类
│   ├── SelectOne.php      # 单选下拉框
│   ├── SelectMany.php     # 多选下拉框
│   ├── ImageUpload.php    # 图片上传
│   ├── ImageUploadMulti.php # 多图片上传
│   ├── FileUpload.php     # 文件上传
│   ├── FileUploadMulti.php  # 多文件上传
│   └── BaiduEditor.php    # 百度编辑器
├── excel/                 # Excel 处理
│   ├── ExcelExport.php    # Excel 导出
└── trait/                 # 特性
    ├── AnyToGet.php       # 数据获取特性
    └── ArrayValue.php     # 数组值处理特性
```

## 核心特性

### 1. 魔术方法调用

qtemp 基类提供了灵活的魔术方法调用方式：

- **属性设置**：`$obj->propertyName($value)` - 设置属性值
- **布尔属性**：`$obj->propertyName()` - 将布尔属性设为 `true`
- **布尔否定**：`$obj->noPropertyName()` - 将布尔属性设为 `false(不过不太常用，因为大部分bool的默认值是false)`
- **数组添加**：`$obj->addPropertyName($array)` - 为数组属性添加元素

### 2. 链式调用

所有 setter 方法都支持链式调用，使代码更加简洁：

```php
$button = (new Button())
    ->text('提交')
    ->btnclass('btn-primary')
    ->action('/submit')
    ->fa('fa-save');
```

## 快速开始

### 按钮控件

```php
use qtemp\control\Button;

// 创建一个按钮
$button = new Button();
$button->text('点击我')
       ->btnclass('btn-success')
       ->size('btn-lg')
       ->action('/api/submit')
       ->param(['id' => 1])
       ->fa('fa-check')
       ->echo(); // 输出按钮
```

### 表单控件

```php
use qtemp\form\TextInput;
use qtemp\form\SelectOne;
use qtemp\form\Textarea;

// 文本输入框
$input = new TextInput();
$input->name('username')
      ->title('用户名')
      ->value('')
      ->required()
      ->group()->echo() // 分组输出

// 下拉选择框
$select = new SelectOne();
$select->name('status')
       ->title('状态')
       ->option([1 => '启用', 0 => '禁用'])
       ->value(1)
       ->group()->echo()

// 多行文本框
$textarea = new Textarea();
$textarea->name('description')
         ->title('描述')
         ->rows(5)
         ->group()->echo()
```

### Excel 导出

```php
use qtemp\excel\ExcelExport;

$export = new ExcelExport();
$export->column([
            'ID' => 'id',
            '用户名' => 'username',
            '邮箱' => 'email',
            '创建时间' => function($item) {
                return date('Y-m-d H:i:s', $item['create_time']);
            }
        ])
       ->data($userList)
       ->name('用户列表')
       ->limit(10000)
       ->export();
```

### 文件上传

```php
use qtemp\form\ImageUpload;
use qtemp\form\FileUploadMulti;

// 单图片上传
$image = new ImageUpload();
$image->name('avatar')
      ->title('头像')
      ->group()->echo()

// 多文件上传
$files = new FileUploadMulti();
$files->name('attachments')
      ->title('附件')
      ->extensions('doc,docx,pdf')
      ->group()->echo()
```

## API 参考

### Qtemp 基类方法

所有继承 `Qtemp` 的类都支持以下魔术方法：

| 方法模式          | 说明                 | 示例                             |
| ----------------- | -------------------- | -------------------------------- |
| `addXxx(array)` | 为数组属性添加元素   | `addParam(['key' => 'value'])` |
| `xxx()`         | 将布尔属性设为 true  | `required()`                   |
| `noXxx()`       | 将布尔属性设为 false | `noDisabled()`                 |
| `xxx(mixed)`    | 设置属性值           | `name('username')`             |

### Control 控件基类

| 方法                           | 说明               |
| ------------------------------ | ------------------ |
| `htmlclass(string\|array)`    | 设置样式类（覆盖） |
| `addHtmlclass(string\|array)` | 添加样式类（追加） |
| `config(array)`              | 设置配置           |
| `addConfig(array)`           | 添加配置           |
| `data(array)`                | 设置 data 属性     |
| `addData(array)`             | 添加 data 属性     |
| `echo()`                     | 输出控件           |
| `str()`                      | 获取控件字符串     |

### FormControl 表单基类

| 方法                | 说明               |
| ------------------- | ------------------ |
| `name(string)`    | 设置字段名         |
| `title(string)`   | 设置标题           |
| `value(mixed)`    | 设置值             |
| `required(bool)`  | 设置是否必填       |
| `disabled(bool)`  | 设置是否禁用       |
| `read_only(bool)` | 设置是否只读       |
| `group()`         | 分组输出（带标签） |
| `groupStr()`      | 获取分组输出字符串 |

### ExcelExport 导出类

| 方法                 | 说明           |
| -------------------- | -------------- |
| `column(array)`    | 设置列配置     |
| `addColumn(array)` | 添加列配置     |
| `data(array)`      | 设置数据       |
| `addData(array)`   | 添加数据       |
| `name(string)`     | 设置导出文件名 |
| `limit(int)`       | 设置数据量限制 |
| `export()`         | 执行导出下载   |

## 版本信息

- **版本**: 1.4.4
- **作者**: 莫耶尔

## 许可证

本工具包为 ThinkCMF 项目的一部分，遵循相应的开源协议。
