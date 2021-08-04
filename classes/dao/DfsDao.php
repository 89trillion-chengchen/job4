<?php
/**
 * 文件存储系统数据层
 */

namespace dao;

use \utils\Functions;

class DfsDao extends ConstDaoBase
{

	protected $dfsHelper = null;

	public function __construct()
	{
		$this->useFastDFS();
		$server = $this->dfsHelper->connect('default');
		if (!is_array($server)) {
			throw new \Exception('tracker connect fail');
		}
	}

	/**
	 * 文件上传
	 *
	 * @param string $localFilePath
	 *
	 * @return string  文件路径
	 */
	public function fileUpload($localFilePath, $fileExt = null, $fileMeta = [])
	{
		$filePath = $this->dfsHelper->fileUpload($localFilePath, $fileExt, $fileMeta);
		if (!$filePath) {
			Functions::log('debug', 'file upload error: ' . $this->dfsHelper->errorInfo() . ' ' . $localFilePath);
		}

		return $filePath;
	}

	/**
	 * 根据文件ID查询文件信息
	 */
	public function fileInfo($fileID)
	{
		return $this->dfsHelper->fileInfo($fileID);
	}

	/**
	 * 根据文件ID获取文件内容
	 */
	public function fileBuff($fileID)
	{
		return $this->dfsHelper->fileDownloadToBuff($fileID);
	}

	/**
	 * 下载DFS文件到本地
	 *
	 * @param $file_id
	 * @param $local_file
	 *
	 */
	public function fileDownload($file_id, $local_file)
	{
		return $this->dfsHelper->fileDownload($file_id, $local_file);
	}

	/**
	 * 根据文件ID删除文件
	 */
	public function fileDelete($fileID)
	{
		return $this->dfsHelper->fileDelete($fileID);
	}

	/**
	 * 检查文件ID是否存在
	 */
	public function fileExist($fileID)
	{
		return $this->dfsHelper->fileExist($fileID);
	}

	/**
	 * 获取上一条错误信息
	 */
	public function errorInfo()
	{
		return $this->dfsHelper->errorInfo();
	}

	/**
	 * 关闭连接
	 */
	public function close()
	{
		return $this->dfsHelper->close();
	}

	public function __destruct()
	{
		$this->close();
	}
}
