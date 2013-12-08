<?php

/*
 * 七牛云存储
 */

class QiNiuCloudStorage implements CloudStorageInterface {
	
	public function __constrct() {
		Qiniu_SetKeys(Conf_QiNiu::ACCESS_KEY, Conf_QiNiu::SECRET_KEY);
	}

	public function upload($picContent) {
		//TODO 重新生成文件的名字
		$picName = "";

		$putPolicy = new Qiniu_RS_PutPolicy(Conf_QiNiu::BUCKET);			
		$upToken = $putPolicy->Token(null);
		$putExtra = new Qiniu_PutExtra();
		$putExtra->Crc32 = 1;
		list($return, $error) = Qiniu_PutFile($upToken, $picName, $picContent, null);

		if ($error !== null) {
			//TODO log
			return FALSE;
		}		

		return $return;
	}	
}

?>
