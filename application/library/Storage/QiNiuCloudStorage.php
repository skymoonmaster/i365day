<?php

/*
 * 七牛云存储
 * 
 */

class QiNiuCloudStorage implements CloudStorageInterface {
	
	/**
	 * 初始化QiNiuCloudStorage
	 * 
	 * 为服务提供凭证
	 */
	private static function initQiNiu() {
		Qiniu_SetKeys(Conf_QiNiu::ACCESS_KEY, Conf_QiNiu::SECRET_KEY);	
	}

	/**
	 * 图片上传
	 * 
	 * @param string $picName
	 * @param type $picContent
	 * @return boolean
	 * 
	 * @link http://docs.qiniu.com/php-sdk/v6/index.html#upload 七牛上传接口文档
	 */
	public static function upload($picName, $picContent) {
		QiNiuCloudStorage::initQiNiu();

		$putPolicy = new Qiniu_RS_PutPolicy(Conf_QiNiu::BUCKET);			
		$upToken = $putPolicy->Token(null);
		$putExtra = new Qiniu_PutExtra();
		$putExtra->Crc32 = 1;
		list($return, $error) = Qiniu_PutFile($upToken, $picName, $picContent, null);

		if ($error !== null) {
			return FALSE;
		}		

		return $return;
	}	
	
	/**
	 * 生成图片缩略图
	 * 
	 * 当$isCrop为TRUE时，可进行裁剪操作
	 * 七牛缩略图接口形如：<ImageDownloadURL>?imageView/<mode>/w/<Width>/h/<Height>/q/<Quality>/format/<Format>
	 * 
	 * @param String $picName
	 * @param int $width
	 * @param int $height
	 * @param int $quality
	 * @param String $format
	 * @param boolean $isCrop
	 * 
	 * @throws Exception_BadInput
	 * 
	 * @link http://docs.qiniu.com/api/v6/image-process.html#imageView 七牛缩略图接口文档
	 */
	public static function generateThumbnail($picName, $width = null, $height = null, $quality = null, $format = null, $isCrop = FALSE) {
		if (empty($picName)) {
			throw new Exception_BadInput("The picName is not be empty");
		}

		if (empty($width) && empty($height)) {
			throw new Exception_BadInput("The width and height can not be empty.");
		}		
		
		if (!empty($quality) && (!is_int($quality) || $quality <= 1 || $quality >= 100)) {
			throw new Exception_BadInput("The quality is invalid.");
		}

		if (!empty($format) && !in_array($format, Conf_QiNiu::PIC_FORMAT)) {
			throw new Exception_BadInput("The pic format is invalid.");	
		}	

		QiNiuCloudStorage::initQiNiu();			
		
		$mode = 2;		
		$picUrl =  QiNiuCloudStorage::getPicUrl($picName);
		$requestUrl .= strstr($picUrl, '?') === FALSE ? '?imageView' : '&imageView';
		
		if ($isCrop) {
			$mode = 1;
		}
		$requestUrl .= "/$mode";	

		if (!empty($width)) {
			$requestUrl .= "/w/{$width}";
		}
		
		if (!empty($height)) {
			$requestUrl .= "/h/{$height}";
		}

		if (!empty($quality)) {
			$requestUrl .= "/q/{$quality}";
		}

		if (!empty($format)) {
			$requestUrl .= "/format/{$format}";
		}

		return $requestUrl;
	}	
	
	/**
	 * 获取图片URL
	 * 
	 * 图片URL根据Bucket开放属性设置的不同而不同
	 * 公共资源URL形如：http://<domain>/<key>
	 * 私有资源URL形如：http://<domain>/<key>?e=<deadline>&token=<downloadToken>
	 * 
	 * @param type $picName
	 * @return type
	 * @throws Exception_BadInput
	 * 
	 * @link http://kb.qiniu.com/53a4iwi2 Bucket公开与私有状态的介绍
	 * @link http://docs.qiniu.com/api/v6/get.html#public-download 公共资源下载
	 * @link http://docs.qiniu.com/api/v6/get.html#private-download 私有资源下载
	 */
	public static function getPicUrl($picName) {
		if (empty($picName)) {
			throw new Exception_BadInput("The picName is not be empty");
		}	

		if (Conf_QiNiu::IS_PUBLIC_RESOURCE) {
			return Qiniu_RS_MakeBaseUrl(Conf_QiNiu::DOMAIN, $picName);
		}	
		
		//private resource must be authorized
		QiNiuCloudStorage::initQiNiu();
		$baseUrl = Qiniu_RS_MakeBaseUrl(Conf_QiNiu::DOMAIN, $picName);
		$getPolicy = new Qiniu_RS_GetPolicy();
		return $getPolicy->MakeRequest($baseUrl, null);
	}

}
?>
