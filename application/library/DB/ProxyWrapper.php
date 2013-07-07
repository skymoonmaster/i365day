<?php

/**
 * Wrapper class for Proxy
 *
 * @category	DB
 * @package		DBProxy
 * @version		$Revision: 1.2 $
 */
class DB_ProxyWrapper extends DB_Proxy {

    /**
     * Get a DBProxyWrapper instance for the specified database.
     *
     * @see DBProxy::getInstance()
     *
     * @param string $database
     * @return DB_ProxyWrapper
     */
    public static function getInstance($database) {
        return self::_getInstance(__CLASS__, $database);
    }

    /**
     * Query all the result rows, each row as associated array
     * Caller should pass all the argument for format string following the $format parameter
     * example:
     * <code>
     * $ret = $db->queryAllRows('SELECT * FROM tb WHERE uid=%d', $uid);
     * </code>
     *
     * @param string $format	SQL query string template
     * @return bool|array	Return array on success or false on failure
     */
    public function queryAllRows($format) {
        $argv = func_get_args();
        $sql = call_user_func_array(array($this, 'buildSqlStr'), $argv);
        return $this->__doSelectQuery($format, $sql, 1);
    }

    /**
     * Query all the result rows, each row as associated array
     * @param string $format	SQL query string template, can be empty,
     * 							just for Log printing when needed
     * @param string $sql		SQL query string
     * @return bool|array		Return array on success or false on failure
     */
    public function queryAllRowsEx($format, $sql) {
        return $this->__doSelectQuery($format, $sql, 1);
    }

    /**
     * Query the first row of the result as associated array
     * Caller should pass all the argument for format string following the $format parameter
     * example:
     * <code>
     * $ret = $db->queryFirstRow('SELECT * FROM tb WHERE uid=%d', $uid);
     * </code>
     *
     * @param string $format	SQL query string template
     * @return bool|array		Return array on success or false on failure
     */
    public function queryFirstRow($format) {
        $argv = func_get_args();
        $sql = call_user_func_array(array($this, 'buildSqlStr'), $argv);
        return $this->__doSelectQuery($format, $sql, 2);
    }

    /**
     * Query the first row of the result as associated array
     *
     * @param string $format	SQL query string template, can be empty,
     * 							just for Log printing when needed
     * @param string $sql		SQL query string
     * @return bool|array		Return array on success or false on failure
     */
    public function queryFirstRowEx($format, $sql) {
        return $this->__doSelectQuery($format, $sql, 2);
    }

    /**
     * Query the specified field value of the first result row
     * Caller should pass all the argument for format string following the $format parameter
     * example:
     * <code>
     * $ret = $db->querySpecifiedField('SELECT uname FROM tb WHERE uid=%d', $uid);
     * </code>
     *
     * @param string $format	SQL query string template
     * @return bool|string		The specified field value on success or false on failure
     */
    public function querySpecifiedField($format, $isInt = false) {
        $argv = func_get_args();
        $sql = call_user_func_array(array($this, 'buildSqlStr'), $argv);
        return $this->__doSelectQuery($format, $sql, 3);
    }

    /**
     * Query the specified field value of the first result row
     *
     * @param string $format	SQL query string template, can be empty,
     * 							just for Log printing when needed
     * @param string $sql		SQL query string
     * @return bool|string		The specified field value on success or false on failure
     */
    public function querySpecifiedFieldEx($format, $sql) {
        return $this->__doSelectQuery($format, $sql, 3);
    }

    /**
     * Do update query according to the SQL query string template and its arguments
     * Caller should pass all the argument for format string following the $format parameter
     * example:
     * <code>
     * $ret = $db->doUpdateQuery('UPDATE tb SET uname=%s WHERE uid=%d', $uname, $uid);
     * </code>
     *
     * @param string $format	SQL query string template
     * @return bool	Return true on success or false on failure
     */
    public function doUpdateQuery($format) {
        $argv = func_get_args();
        $sql = call_user_func_array(array($this, 'buildSqlStr'), $argv);
        if (empty($sql)) {
            $this->__buildSqlStrError($format, 2);
            return false;
        }

        if (parent::doUpdateQuery($sql) === false) {
            $this->__sqlQueryError();
            return false;
        }
        return true;
    }

    /**
     *
     * @param string $format	SQL query string template, can be empty,
     * 							just for Log printing when needed
     * @param string $sql		SQL query string
     * @return bool	Return true on success or false on failure
     */
    public function doUpdateQueryEx($format, $sql) {
        if (empty($sql)) {
            $this->__buildSqlStrError($format, 2);
            return false;
        }

        if (parent::doUpdateQuery($sql) === false) {
            $this->__sqlQueryError();
            return false;
        }
        return true;
    }

    private function __doSelectQuery($format, $sql, $mode, $log_trace_depth = 1) {
        if (empty($sql)) {
            $this->__buildSqlStrError($format, $log_trace_depth + 1);
            return false;
        }

        switch ($mode) {
            case 1: //select all rows
                $arrData = parent::queryAllRows($sql);
                if (empty($arrData)) {
                    $ret = false;
                    break;
                }
                if (!strpos($sql, 'charge_statistics')) {
                    $ret = $this->AccordingFieldDecryption($arrData);
                } else {
                    $ret = $arrData;
                }
                break;

            case 2: //select first row(or select single row in the other word)
                $arrData = parent::queryFirstRow($sql);
                if (empty($arrData)) {
                    $ret = false;
                    break;
                }
                if (!strpos($sql, 'charge_statistics')) {
                    $ret = $this->AccordingFieldFirstDecryption($arrData);
                } else {
                    $ret = $arrData;
                }
                break;

            case 3: //select the specified field
                $arrData = parent::querySpecifiedField($sql);
                if (empty($arrData)) {
                    $ret = false;
                    break;
                }
                if (!strpos($sql, 'charge_statistics')) {
                    $ret = $this->AccordingFieldDecryption($arrData);
                } else {
                    $ret = $arrData;
                }
                break;

            default:
                $ret = false;
                break;
        }

        if ($ret === false) {
            $this->__sqlQueryError($log_trace_depth + 1);
            return false;
        }

        return $ret;
    }

    private function __errorMessage() {
        return ' errcode[' . $this->getErrno() . '] errmsg[' .
                $this->getErrmsg() . '] sql[' . $this->getSqlStr() . ']';
    }

    private function __buildSqlStrError($format, $log_trace_depth = 1) {
        Util_CLog::warning("format[$format] buildSqlStr failed", 0, null, $log_trace_depth + 1);
    }

    private function __sqlQueryError($log_trace_depth = 1) {
        $errmsg = $this->__errorMessage();
        Util_CLog::selflog('db', $errmsg);
    }

}

/* vim: set ts=4 sw=4 sts=4 tw=100 noet: */
?>
