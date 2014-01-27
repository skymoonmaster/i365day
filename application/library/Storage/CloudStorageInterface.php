<?php


/**
 * 云存储接口
 *
 */
interface  Storage_CloudStorageInterface {
	public static function upload($picName, $picContent);
	public static function generateThumbnail($picName, $width = null, $height = null, $quality = null, $format = null, $isCrop = FALSE);
}

?>
