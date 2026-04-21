<?php 
namespace qtemp\form;
/**
 * 表单验证管理类
 */
class FormValidator {
    /**
     * 表单验证器实例
     * @var array
     */
    protected static $instances = [];
    
    /**
     * 获取验证器实例
     * @param string $formId
     * @return FormValidator
     */
    public static function getInstance($formId = 'default') {
        if (!isset(self::$instances[$formId])) {
            self::$instances[$formId] = new self();
        }
        return self::$instances[$formId];
    }
    
    /**
     * 注册图片验证
     * @param string $elementId
     * @param string $title
     */
    public function registerImageValidator($elementId, $title) {
        $title_js = json_encode($title);
        ?>
        <script type="text/javascript">
            document.addEventListener("DOMContentLoaded", function() {
                var input = document.getElementById("<?= $elementId ?>");
                var title = <?= $title_js ?>;
                if (input) {
                    input.validateRequiredImage = function() {
                        var value = this.value;
                        if (!value || value.trim() === "") {
                            alert(title + "：请上传图片");
                            return false;
                        }
                        return true;
                    };
                    
                    var form = input.closest("form");
                    if (form) {
                        if (!form["__required_image_validators"]) {
                            form["__required_image_validators"] = [];
                        }
                        form["__required_image_validators"].push(input);
                        
                        if (!form["__has_image_submit_listener"]) {
                            form["__has_image_submit_listener"] = true;
                            
                            form.addEventListener("submit", function(e) {
                                if (form["__required_image_validators"]) {
                                    for (var i = 0; i < form["__required_image_validators"].length; i++) {
                                        var validator = form["__required_image_validators"][i];
                                        if (!validator.validateRequiredImage()) {
                                            e.preventDefault();
                                            e.stopPropagation();
                                            return false;
                                        }
                                    }
                                }
                            }, true);
                        }
                    }
                }
            });
        </script>
        <?php
    }
    
    /**
     * 注册多图片验证
     * @param string $containerId
     * @param string $title
     * @param int $minImages
     */
    public function registerMultiImageValidator($containerId, $title, $minImages) {
        $title_js = json_encode($title);
        ?>
        <script type="text/javascript">
            document.addEventListener("DOMContentLoaded", function() {
                var photosContainer = document.getElementById("<?= $containerId ?>");
                var title = <?= $title_js ?>;
                if (photosContainer) {
                    photosContainer.setAttribute("data-min-images", <?= $minImages ?>);
                    
                    var form = photosContainer.closest("form");
                    if (form) {
                        if (!form["__multi_image_validators"]) {
                            form["__multi_image_validators"] = [];
                        }
                        form["__multi_image_validators"].push(photosContainer);
                        
                        if (!form["__has_multi_image_submit_listener"]) {
                            form["__has_multi_image_submit_listener"] = true;
                            
                            form.addEventListener("submit", function(e) {
                                if (form["__multi_image_validators"]) {
                                    for (var i = 0; i < form["__multi_image_validators"].length; i++) {
                                        var container = form["__multi_image_validators"][i];
                                        var imageCount = container.querySelectorAll("li").length;
                                        var minRequired = parseInt(container.getAttribute("data-min-images") || 0);
                                        
                                        if (minRequired > 0 && imageCount < minRequired) {
                                            alert(title + "：至少需要上传 " + minRequired + " 张图片");
                                            e.preventDefault();
                                            e.stopPropagation();
                                            return false;
                                        }
                                    }
                                }
                            }, true);
                        }
                    }
                }
            });
        </script>
        <?php
    }
    
    /**
     * 注册文件验证
     * @param string $elementId
     * @param string $title
     */
    public function registerFileValidator($elementId, $title) {
        $title_js = json_encode($title);
        ?>
        <script type="text/javascript">
            document.addEventListener("DOMContentLoaded", function() {
                var input = document.getElementById("<?= $elementId ?>");
                var title = <?= $title_js ?>;
                if (input) {
                    input.validateRequiredFile = function() {
                        var value = this.value;
                        if (!value || value.trim() === "") {
                            alert(title + "：请上传文件");
                            return false;
                        }
                        return true;
                    };
                    
                    var form = input.closest("form");
                    if (form) {
                        if (!form["__required_file_validators"]) {
                            form["__required_file_validators"] = [];
                        }
                        form["__required_file_validators"].push(input);
                        
                        if (!form["__has_file_submit_listener"]) {
                            form["__has_file_submit_listener"] = true;
                            
                            form.addEventListener("submit", function(e) {
                                if (form["__required_file_validators"]) {
                                    for (var i = 0; i < form["__required_file_validators"].length; i++) {
                                        var validator = form["__required_file_validators"][i];
                                        if (!validator.validateRequiredFile()) {
                                            e.preventDefault();
                                            e.stopPropagation();
                                            return false;
                                        }
                                    }
                                }
                            }, true);
                        }
                    }
                }
            });
        </script>
        <?php
    }
    
    /**
     * 注册单选验证
     * @param string $elementId
     * @param string $title
     */
    public function registerSelectValidator($elementId, $title) {
        $title_js = json_encode($title);
        ?>
        <script>
            (function() {
                var selectElement = document.getElementById("<?= $elementId ?>");
                var title = <?= $title_js ?>;
                if (selectElement && selectElement.hasAttribute("required")) {
                    selectElement.validateRequiredSelect = function() {
                        var selectedValue = this.value;
                        if (!selectedValue || selectedValue.trim() === "" || selectedValue === "请选择" + title) {
                            this.classList.add("error");
                            return false;
                        } else {
                            this.classList.remove("error");
                            return true;
                        }
                    };

                    var form = selectElement.closest("form");
                    if (form) {
                        if (!form["__required_select_validators"]) {
                            form["__required_select_validators"] = [];
                        }
                        form["__required_select_validators"].push(selectElement);

                        if (!form["__has_select_submit_listener"]) {
                            form["__has_select_submit_listener"] = true;

                            form.addEventListener("submit", function(e) {
                                if (form["__required_select_validators"]) {
                                    for (var i = 0; i < form["__required_select_validators"].length; i++) {
                                        var validator = form["__required_select_validators"][i];
                                        if (!validator.validateRequiredSelect()) {
                                            e.preventDefault();
                                            e.stopPropagation();
                                            return false;
                                        }
                                    }
                                }
                            }, true);
                        }
                    }

                    selectElement.addEventListener("change", function() {
                        if (this.value && this.value.trim() !== "" && this.value !== "请选择" + title) {
                            this.classList.remove("error");
                        }
                    });
                }
            })();
        </script>
        <?php
    }
    
    /**
     * 注册多文件验证
     * @param string $containerId
     * @param string $title
     * @param int $minFiles
     */
    public function registerMultiFileValidator($containerId, $title, $minFiles) {
        $title_js = json_encode($title);
        ?>
        <script type="text/javascript">
            document.addEventListener("DOMContentLoaded", function() {
                var filesContainer = document.getElementById("<?= $containerId ?>");
                var title = <?= $title_js ?>;
                if (filesContainer) {
                    filesContainer.setAttribute("data-min-files", <?= $minFiles ?>);
                    
                    var form = filesContainer.closest("form");
                    if (form) {
                        if (!form["__multi_file_validators"]) {
                            form["__multi_file_validators"] = [];
                        }
                        form["__multi_file_validators"].push(filesContainer);
                        
                        if (!form["__has_multi_file_submit_listener"]) {
                            form["__has_multi_file_submit_listener"] = true;
                            
                            form.addEventListener("submit", function(e) {
                                if (form["__multi_file_validators"]) {
                                    for (var i = 0; i < form["__multi_file_validators"].length; i++) {
                                        var container = form["__multi_file_validators"][i];
                                        var fileCount = container.querySelectorAll("li").length;
                                        var minRequired = parseInt(container.getAttribute("data-min-files") || 0);
                                        
                                        if (minRequired > 0 && fileCount < minRequired) {
                                            alert(title + "：至少需要上传 " + minRequired + " 个文件");
                                            e.preventDefault();
                                            e.stopPropagation();
                                            return false;
                                        }
                                    }
                                }
                            }, true);
                        }
                    }
                }
            });
        </script>
        <?php
    }
    
    /**
     * 注册多选验证
     * @param string $elementId
     * @param string $title
     * @param int $min
     * @param int $max
     */
    public function registerMultiSelectValidator($elementId, $title, $min, $max) {
        $title_js = json_encode($title);
        ?>
        <script>
            (function() {
                var funcName = "limitSelectOptions_" + "<?= $elementId ?>";
                var title = <?= $title_js ?>;
                var maxOptions = <?= $max ?> > 0 ? <?= $max ?> : 0;
                var minOptions = <?= $min ?> > 0 ? <?= $min ?> : 0;
                window[funcName] = function(selectElement, previousSelections) {
                    var selectedOptions = Array.from(selectElement.selectedOptions);
                    
                    if (maxOptions > 0 && selectedOptions.length > maxOptions) {
                        var currentSelections = Array.from(selectElement.selectedOptions).map(option => option.value);
                        var newSelections = currentSelections.filter(value => !previousSelections.includes(value));
                        
                        if (newSelections.length > 0) {
                            for (var i = 0; i < selectElement.options.length; i++) {
                                var option = selectElement.options[i];
                                if (newSelections.includes(option.value)) {
                                    option.selected = false;
                                }
                            }
                        } else {
                            var lastSelected = selectedOptions.slice(maxOptions);
                            for (var i = 0; i < lastSelected.length; i++) {
                                lastSelected[i].selected = false;
                            }
                        }
                        
                        alert(title + "：最多只能选择 " + maxOptions + " 个选项");
                        return false;
                    }
                    
                    return true;
                };
                
                document.addEventListener("DOMContentLoaded", function() {
                    var selectElement = document.getElementById("<?= $elementId ?>");
                    
                    if (selectElement && (selectElement.getAttribute("data-max-options") || selectElement.getAttribute("data-min-options"))) {

                        selectElement.addEventListener("click", function(e) {
                            setTimeout(() => {
                                this.previousSelections = Array.from(this.selectedOptions).map(option => option.value);
                            }, 0);
                        });
                        
                        selectElement.addEventListener("change", function(e) {
                            var prevSelections = this.previousSelections || [];
                            window[funcName](selectElement, prevSelections);
                        });
                        
                        selectElement.validateMinOptions = function() {
                            var minOptions = parseInt(this.getAttribute("data-min-options"));
                            if (minOptions > 0) {
                                var selectedCount = Array.from(this.selectedOptions).length;
                                
                                if (selectedCount < minOptions) {
                                    this.classList.add("error");
                                    return false;
                                }
                            }
                            return true;
                        };
                        
                        var form = selectElement.closest("form");
                        if (form) {
                            if (!form["__min_option_validators"]) {
                                form["__min_option_validators"] = [];
                            }
                            form["__min_option_validators"].push(selectElement);
                            
                            if (!form["__has_submit_listener"]) {
                                form["__has_submit_listener"] = true;
                                
                                form.addEventListener("submit", function(e) {
                                    if (form["__min_option_validators"]) {
                                        for (var i = 0; i < form["__min_option_validators"].length; i++) {
                                            var validator = form["__min_option_validators"][i];
                                            if (!validator.validateMinOptions()) {
                                                e.preventDefault();
                                                e.stopPropagation();
                                                return false;
                                            }
                                        }
                                    }
                                }, true);
                            }
                        }
                    } else if (selectElement && selectElement.getAttribute("data-min-options")) {
                        selectElement.validateMinOptions = function() {
                            var minOptions = parseInt(this.getAttribute("data-min-options"));
                            if (minOptions > 0) {
                                var selectedCount = Array.from(this.selectedOptions).length;
                                if (selectedCount < minOptions) {
                                    alert(title + "：至少需要选择 " + minOptions + " 个选项");
                                    return false;
                                }
                            }
                            return true;
                        };
                        
                        var form = selectElement.closest("form");
                        if (form) {
                            if (!form["__min_option_validators"]) {
                                form["__min_option_validators"] = [];
                            }
                            form["__min_option_validators"].push(selectElement);
                            
                            if (!form["__has_submit_listener"]) {
                                form["__has_submit_listener"] = true;
                                
                                form.addEventListener("submit", function(e) {
                                    if (form["__min_option_validators"]) {
                                        for (var i = 0; i < form["__min_option_validators"].length; i++) {
                                            var validator = form["__min_option_validators"][i];
                                            if (!validator.validateMinOptions()) {
                                                e.preventDefault();
                                                e.stopPropagation();
                                                return false;
                                            }
                                        }
                                    }
                                }, true);
                            }
                        }
                    }
                });
            })();
        </script>
        <?php
    }
}
