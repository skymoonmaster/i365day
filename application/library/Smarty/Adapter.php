<?php

/**
 * 视图引擎定义
 * Smarty/Adapter.php
 */
class Smarty_Adapter implements Yaf_View_Interface {

    /**
     * Smarty object
     * @var Smarty
     */
    public $_smarty;

    /**
     * Constructor
     *
     * @param string $tmplPath
     * @param array $extraParams
     * @return void
     */
    public function __construct($tmplPath = null, $extraParams = array()) {


        Yaf_Loader::import (APPLICATION_PATH . DIRECTORY_SEPARATOR . "application/library/Smarty/libs/Smarty.class.php");
        Yaf_Loader::import (SMARTY_SYSPLUGINS_DIR . "smarty_internal_templatecompilerbase.php");
        Yaf_Loader::import (SMARTY_SYSPLUGINS_DIR . "smarty_internal_templatelexer.php");
        Yaf_Loader::import (SMARTY_SYSPLUGINS_DIR . "smarty_internal_templateparser.php");
        Yaf_Loader::import (SMARTY_SYSPLUGINS_DIR . "smarty_internal_compilebase.php");
        Yaf_Loader::import (SMARTY_SYSPLUGINS_DIR . "smarty_internal_write_file.php");
        $this->_smarty = new Smarty;

        if (null !== $tmplPath) {
            $this->setScriptPath($tmplPath);
        }
        if (is_array($extraParams) && count($extraParams) > 0) {
            foreach ($extraParams as $key => $value) {
                $this->_smarty->$key = $value;
            }
        }
    }

    /**
     * Set the path to the templates
     *
     * @param string $path The directory to set as the path.
     * @return void
     */
    public function setScriptPath($path) {
        if (is_readable($path)) {
            $this->_smarty->template_dir = $path;
            return;
        }

        throw new Exception('Invalid path provided');
    }

    public function getScriptPath() {
        return $this->_smarty->template_dir;
    }

    /**
     * Assign variables to the template
     *
     * Allows setting a specific key to the specified value, OR passing
     * an array of key => value pairs to set en masse.
     *
     * @see __set()
     * @param string|array $spec The assignment strategy to use (key or
     * array of key => value pairs)
     * @param mixed $value (Optional) If assigning a named variable,
     * use this as the value.
     * @return void
     */
    public function assign($spec, $value = null) {
        if (is_array($spec)) {
            $this->_smarty->assign($spec);
            return;
        }

        $this->_smarty->assign($spec, $value);
    }

    /**
     * Processes a template and returns the output.
     *
     * @param string $name The template to process.
     * @return string The output.
     */
    public function render($name, $tplVars = null) {
        return $this->_smarty->fetch($name);
    }

    public function display($name, $value = NULL) {
        echo $this->_smarty->fetch($name);
    }

}

?>
