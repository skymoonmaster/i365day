<?php

Class FileModel extends BasicModel {

    public static $cnt = 0;

    /**
     * @var FileModel
     */
    protected static $instances;

    /**
     * @return FileModel
     */
    public static function getInstance() {
        if (!isset(self::$instances)) {
            self::$instances = new FileModel();
        }
        return self::$instances;
    }

    public function upload($uploadFileName, $target) {
        $uploaded_size = $_FILES [$uploadFileName] ['size'];
        $fname = $_FILES [$uploadFileName] ['name'];
        if (empty($fname)) {
            throw new Exception("filename is empty");
        }
        //This is our size condition
        if ($uploaded_size > 50 * 1024 * 1024) {
            throw new Exception("file is too large");
        }
        //Writes the photo to the server
        if (move_uploaded_file($_FILES [$uploadFileName] ['tmp_name'], $target)) {
            $ret = Storage_QiNiuCloudStorage::upload($fname, $target);
            if (empty($ret)) {
                throw new Exception("file upload failed");
            }

            return Storage_QiNiuCloudStorage::getPicUrl($ret['key']);
        } else {
            throw new Exception("filename upload failed");
        }
    }

    public function generateFilenameForDiaryPic($createTime, $filename) {
        if (!isset($_SESSION['user_id']) || intval($_SESSION['user_id']) == 0) {
            throw new Exception_Login("please login");
        }
        if (!isset($_FILES [$filename] ['name'])) {
            throw new Exception_ReadFile("can not find the file $filename");
        }
        $pathInfo = pathinfo($_FILES [$filename] ['name']);
        return DIARY_PIC_DIR . DIRECTORY_SEPARATOR . md5($_SESSION['user_id'] . $createTime) . '.' . $pathInfo ['extension'];
    }
    
    public function generateSrcForDiaryPic($createTime, $filename) {
        if (!isset($_SESSION['user_id']) || intval($_SESSION['user_id']) == 0) {
            throw new Exception_Login("please login");
        }
        if (!isset($_FILES [$filename] ['name'])) {
            throw new Exception_ReadFile("can not find the file $filename");
        }
        $pathInfo = pathinfo($_FILES [$filename] ['name']);
        return DIARY_PIC_SRC . DIRECTORY_SEPARATOR . md5($_SESSION['user_id'] . $createTime) . '.' . $pathInfo ['extension'];
    }
    
        
    public function generateFilenameForAvatar($filename){
        if (!isset($_SESSION['user_id']) || intval($_SESSION['user_id']) == 0) {
            throw new Exception_Login("please login");
        }
        $pathInfo = pathinfo($filename);
        return AVATAR_PIC_DIR . DIRECTORY_SEPARATOR . md5($_SESSION['user_id']) . '.' . $pathInfo ['extension'];
    }
    
    public function uploadDiaryPic($createTime, $filename) {

        $targetFilename = $this->generateFilenameForDiaryPic($createTime, $filename);
        return $this->upload($filename, $targetFilename);
    }

}

/* vim: set ts=4 sw=4 sts=4 tw=100 noet: */
?>
