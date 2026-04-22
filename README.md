# qtemp 集成式工具包

[![Latest Stable Version](https://poser.pugx.org/moyer/qtemp/v)](https://packagist.org/packages/moyer/qtemp)
[![License](https://poser.pugx.org/moyer/qtemp/license)](https://packagist.org/packages/moyer/qtemp)
[![PHP Version](https://poser.pugx.org/moyer/qtemp/require/php)](https://packagist.org/packages/moyer/qtemp)

qtemp 是一个为 ThinkCMF 框架设计的集成式工具包，提供了丰富的 UI 控件、表单组件和 Excel 处理功能，帮助开发者快速构建后台管理界面。

总的说，我还想做一个无手动编码的后台系统，但一个足够集成化的模板库是前置条件。

## 安装

### Composer 安装（推荐）

```bash
composer require moyer/qtemp
```

### 版本要求

- PHP >= 8.0
- ThinkCMF >= 8.0

## 目录结构

```
vendor/moyer/qtemp/
├── src/
│   ├── Qtemp.php              # 核心基类，提供魔术方法支持
│   ├── helpers.php            # 全局助手函数
│   ├── control/               # UI 控件
│   │   ├── Control.php        # 控件基类
│   │   ├── NavTab.php         # 导航标签
│   │   └── TableMin.php       # 迷你表格
│   ├── btn/                   #按钮
│   │   ├── Button.php         # 按钮控件基类
│   │   ├── BtnAction.php      # 动作按钮
│   │   ├── BtnExcelToData.php # Excel 导入按钮
│   │   ├── BtnSubmit.php      # 提交按钮
│   │   └── BtnTarget.php      # 目标按钮
│   ├── form/                  # 表单控件
│   │   ├── FormControl.php    # 表单基类
│   │   ├── FormValidator.php  # 表单验证器
│   │   ├── TextInput.php      # 文本输入框
│   │   ├── Textarea.php       # 多行文本框
│   │   ├── Label.php          # 标签
│   │   ├── SelectControl.php  # 选择框基类
│   │   ├── SelectOne.php      # 单选下拉框
│   │   ├── SelectMany.php     # 多选下拉框
│   │   ├── ImageUpload.php    # 图片上传
│   │   ├── ImageUploadMulti.php # 多图片上传
│   │   ├── FileUpload.php     # 文件上传
│   │   ├── FileUploadMulti.php  # 多文件上传
│   │   └── BaiduEditor.php    # 百度编辑器
│   └── excel/                 # Excel 处理
│       └── ExcelExport.php    # Excel 导出
├── composer.json
├── version
└── README.md
```

## 核心特性

### 1. 魔术方法调用

qtemp 基类提供了灵活的魔术方法调用方式：

- **属性设置**：`$obj->propertyName($value)` - 设置属性值
- **布尔属性**：`$obj->propertyName()` - 将布尔属性设为 `true`
- **布尔否定**：`$obj->noPropertyName()` - 将布尔属性设为 `false`
- **数组添加**：`$obj->addPropertyName($array)` - 为数组属性添加元素

### 2. 链式调用

所有 setter 方法都支持链式调用，使代码更加简洁：

```php
(new \qtemp\btn\Button)
    ->text('提交')
    ->btnclass('btn-primary')
    ->action('/submit')
    ->fa('save')
    ->echo();
```

### 3. 助手函数

通过 composer 自动加载的全局助手函数：

```php
// 安全获取数组值
$value = \qtemp\getValue($item, 'user.name', '默认值');

// 通配符字符串匹配
$property = \qtemp\getSubstring('addTitle', 'add*');
```

## 快速开始

### 按钮控件

```php
// 创建一个按钮
(new \qtemp\btn\Button)->text('点击我')
       ->btnclass('btn-success')
       ->size('btn-lg')
       ->action('/api/submit')
       ->param(['id' => 1])
       ->fa('fa-check')
       ->echo(); // 输出按钮
```

### 表单控件

```php
// 文本输入框
(new \qtemp\form\TextInput)->name('username')
      ->title('用户名')
      ->value('')
      ->required()
      ->group()->echo(); // 分组输出

// 下拉选择框
(new \qtemp\form\SelectOne)->name('status')
       ->title('状态')
       ->option([1 => '启用', 0 => '禁用'])
       ->value(1)
       ->group()->echo();

// 多行文本框
(new \qtemp\form\Textarea)->name('description')
         ->title('描述')
         ->rows(5)
         ->group()->echo();
```

### 导航标签 + 表格

```php
// 导航标签
(new \qtemp\control\NavTab)->tab([
    'index' => '列表',
    'add' => '新增'
])->echo();

// 数据表格
(new \qtemp\control\TableMin)->list($items)
    ->column([
        'ID' => 'id',
        '标题' => 'title',
        '创建时间' => 'create_time'
    ])
    ->autoAction()
    ->echo();
```

### Excel 导出

```php
(new \qtemp\excel\ExcelExport)->column([
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
// 单图片上传
(new \qtemp\form\ImageUpload)->name('avatar')
    ->title('头像')
    ->group()
    ->echo();

// 多文件上传
(new \qtemp\form\FileUploadMulti)
    ->name('attachments')
    ->title('附件')
    ->group()
    ->echo();
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

## 信息

- **作者**: 莫耶尔
- **Packagist**: [moyer/qtemp](https://packagist.org/packages/moyer/qtemp)
- **GitHub**: [Orcreala/qtemp](https://github.com/Orcreala/qtemp)

## 开源协议

MIT License
