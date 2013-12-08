<?php

class Util_Result {
	private $_isSuccess;
	private $_code;	
	private $_msg;
	private $_data;
	
	public function __construct($code, $msg, $data) {
		$this->_code = $code;
		$this->_msg = $msg;
		$this->_data = $data;
	}
	
	public function setCode($code) {
		$this->_code = $code;
	}
	
	public function setMsg($msg) {
		$this->_msg = $msg;
	}
	
	public function setData($data) {
		$this->_data = $data;
	}
	
	public function setIsSuccess($isSuccess) {
		$this->_isSuccess = $isSuccess;
	}

	public static function success($code, $msg, $data) {
		$result = new Util_Result($code, $msg, $data);
		$result->setIsSuccess(TRUE);		

		return $result;	
	}
	
	public static function failure($code, $msg, $data) {
		$result = new Util_Result($code, $msg, $data);
		$result->setIsSuccess(FALSE);

		return $result;
	}
	
	public function toJson() {
		return json_encode($this->toArray());	
	}

	public function toArray() {
		$result = array(
			'code' => $this->_code,
			'msg' => $this->_msg,
			'data' => $this->_data
		);		

		return $result;
	}

	public function __toString() {
		return json_encode($this->toArray());		
	}
}
