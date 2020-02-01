<?php
class fileuploaderCfs {
	private $_error = '';
	private $_dest = '';
	private $_file = '';
	/**
	 * Result filename, if empty - it will be randon generated string
	 */
	private $_destFilename = '';
	/**
	 * Return last error
	 * @return string error message
	 */
	public function getError() {
		return $this->_error;
	}
	/**
	 * Validate before upload
	 * @param string $inputname name of the input HTML document (key in $_FILES array)
	 * @param string $destSubDir destination for uploaded file, for wp this should be directory in wp-content/uploads/ dir
	 * @param string $destFilename name of a file that be uploaded
	 */
	public function validate($inputname, $destSubDir, $destFilename = '') {
		$file = is_array($inputname) ? $inputname : $_FILES[$inputname];
		$res = false;
		if(!empty($file['error'])) {
			switch($file['error']) {
				case '1':
					$this->_error = __('The uploaded file exceeds the upload_max_filesize directive in php.ini', CFS_LANG_CODE);
					break;
				case '2':
					$this->_error = __('The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form', CFS_LANG_CODE);
					break;
				case '3':
					$this->_error = __('The uploaded file was only partially uploaded', CFS_LANG_CODE);
					break;
				case '4':
					$this->_error = __('No file was uploaded.', CFS_LANG_CODE);
					break;
				case '6':
					$this->_error = __('Missing a temporary folder', CFS_LANG_CODE);
					break;
				case '7':
					$this->_error = __('Failed to write file to disk', CFS_LANG_CODE);
					break;
				case '8':
					$this->_error = __('File upload stopped by extension', CFS_LANG_CODE);
					break;
				case '999':
				default:
					$this->_error = __('No error code avaiable', CFS_LANG_CODE);
			}
		} elseif(empty($file['tmp_name']) || $file['tmp_name'] == 'none') {
			$this->_error = __('No file was uploaded..', CFS_LANG_CODE);
		} else {
			$res = true;
		}
		if($res) {
			//$this->_fileSize = $file['size'];
			$this->_dest = $destSubDir;
			$this->_file = $file;
			$this->_destFilename = $destFilename;
		}
		return $res;
	}
	/**
	 * Upload valid file
	 */
	public function upload($d = array()) {
		$d['ignore_db_insert'] = isset($d['ignore_db_insert']) ? $d['ignore_db_insert'] : false;
		$res = false;
		add_filter('upload_dir', array($this,'changeUploadDir'));
		add_filter('wp_handle_upload_prefilter', array($this,'changeFileName'));
		// File name will be changed during upload process - we will bring it back after upload 
		$fileName = $this->_file['name'];
		$upload = wp_handle_upload($this->_file, array('test_form' => FALSE));
		$this->_file['name'] = $fileName;
		if (!empty($upload['type'])) {
			if(!isset($d['ignore_db_insert']) || !$d['ignore_db_insert']) {
				$saveData = array(
					'fid' => (isset($d['fid']) ? $d['fid'] : 0),
					'field_name' => (isset($d['field_name']) ? $d['field_name'] : ''),
					'name' => $this->_file['name'],
					'dest_name' => $this->_destFilename,
					'path' => str_replace(DS, '\\'. DS, $this->_dest),
					'mime_type' => $upload['type'],
					'size' => $this->_file['size'],
					'hash' => md5(mt_rand(1, 9999999)),
				);
				$this->_file['id'] = frameCfs::_()->getTable('files')->insert($saveData);
				$this->_file['hash'] = $saveData['hash'];
				$this->_file['field_name'] = $saveData['field_name'];
				$this->_file['url'] = $upload['url'];
			}
			$res = true;
		} elseif($upload['error']) {
			$this->_error = $upload['error'];
		}
		remove_filter('upload_dir', array($this,'changeUploadDir'));
		remove_filter('wp_handle_upload_prefilter', array($this,'changeFileName'));
		return $res;
	}
	public function getFileInfo() {
		return $this->_file;
	}
	public function changeUploadDir($uploads) {
		$uploads['subdir'] = $this->_dest;
		if(empty($uploads['subdir'])) {
			$uploads['path'] = $uploads['basedir'];
			$uploads['url'] = $uploads['baseurl'];
		} else {
			$uploads['path'] = $uploads['basedir'] . DS. $uploads['subdir'];
			$uploads['url'] = $uploads['baseurl'] . '/'.$uploads['subdir'];
		}
		return $uploads;
	}
	public function changeFileName($file) {
		$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
		if(empty($this->_destFilename)) {
			$this->_destFilename = $this->createFileName();
		}
		$this->_destFilename .= '.'. $ext;
		$file['name'] = $this->_destFilename;
		return $file;
	}
	private function createFileName() {
		return utilsCfs::getRandStr(). '-'. utilsCfs::getRandStr(). '-'. utilsCfs::getRandStr(). '-'. utilsCfs::getRandStr();
	}
	/**
	 * Delete uploaded file
	 * @param int|array $id ID of file in files table, or file row data from files table
	 */
	public function delete($id) {
		$file = is_array($id) ? $id : frameCfs::_()->getTable('files')->get('*', array('id' => $id), '', 'row');
		if($file) {
			frameCfs::_()->getTable('files')->delete($file['id']);
			$uploadsDir = wp_upload_dir( null, false );
			@unlink( $uploadsDir['basedir']. (empty($file['path']) ? '' : DS. $file['path']). DS. $file['dest_name'] );
		}
		return false;
	}
}