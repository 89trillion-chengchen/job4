<?php
/**
 * @author: ChengRennt <ChengRennt@gmail.com>
 * @created: 2014-1-14 下午5:20:10
 * @description: 
 * $Id: FastDFSHelper.php 3236 2014-07-22 10:31:13Z pengcheng2 $
 */
namespace framework\data\fastdfs;

class FastDFSHelper {
    
    private $fdfs;
    private $server;
    
    public function __construct($config) {
        if ($config) {
            $this->fdfs = FastDFSManager::getInstance($config);
        }
    }
    
    public function connect($config) {
        $this->server = $this->fdfs ? $this->fdfs->connect_server(FastDFSManager::$configs[$config]->ip_addr, FastDFSManager::$configs[$config]->port) : null;
        return $this->server;
    }
    
    public function close() {
        return $this->fdfs ? $this->fdfs->disconnect_server($this->server) : null;
    }
    
    public function errorNo() {
        return $this->fdfs ? $this->fdfs->get_last_error_no() : null;
    }
    
    public function errorInfo() {
        return $this->fdfs ? $this->fdfs->get_last_error_info() : null;
    }
    
    public function fileExist($file_id) {
        return $this->fdfs ? $this->fdfs->storage_file_exist1($file_id) : null;
    }
    
    public function fileInfo($file_id) {
        return $this->fdfs ? $this->fdfs->get_file_info1($file_id) : null;
    }
    
    public function fileUpload($filename,$file_ext=null,$file_meta=array()) {
        if(is_array($this->server)) {
            return $this->fdfs ? $this->fdfs->storage_upload_by_filename1($filename, $file_ext, $file_meta, null, $this->server) : null;
        } else {
            return $this->fdfs ? $this->fdfs->storage_upload_by_filename1($filename, $file_ext, $file_meta) : null;
        }
    }
    
    public function fileUploadByBuff($content, $extension) {
        return $this->fdfs ? $this->fdfs->storage_upload_by_filebuff1($content, $extension) : null;
    }
    
    public function fileDelete($file_id) {
        if(is_array($this->server)) {
            return $this->fdfs ? $this->fdfs->storage_delete_file1($file_id, $this->server) : null;
        } else {
            return $this->fdfs ? $this->fdfs->storage_delete_file1($file_id) : null;
        }
    }
    
    public function fileDownload($file_id, $local_filename) {
        return $this->fdfs ? $this->fdfs->storage_download_file_to_file1($file_id, $local_filename) : null;
    }
    
    public function fileDownloadToBuff($file_id) {
        return $this->fdfs ? $this->fdfs->storage_download_file_to_buff1($file_id) : null;
    }
}
