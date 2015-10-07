<?php 
namespace App;

use App;
use App\Nkie12;
use Exception;
use Respect\Validation\Validator; // See: https://github.com/Respect/Validation
use fileManager;

class Upload extends App\Nkie12\NinDo {
	public static function instance() { return parent::getInstance(); }

	protected function __construct() {
		if (! class_exists('fileManager')) { require_once app_path('third_party/fileManager.class.php'); }
	}
	
	private function _getCodedName($_filename='') {
		$special_sauce = chr(mt_rand( 97 ,122 )).substr(md5(time()), 1); // See: http://stackoverflow.com/a/19018286
		return 'file_'.$special_sauce.md5($_filename);
	}
	
	protected function save($_file, $_options=[]) {
		$options = array_merge([
			'destination' => storage_path(),
			'max_size' => 104857600 // 100mb	 
		], $_options);
		$error = '';	
		$uploader = new fileManager;
		$uploader->setDestination(rtrim($options['destination'], '/').'/');		
		$uploader->setMaxSize($options['max_size']);
		$ext = $uploader->getExtension($_file);
		$filename = $_file['name'];	
		$coded_name = $this->_getCodedName($filename).'.'.$ext;		
		$uploader->setFilename($coded_name);
		$uploader->upload($_file);
		$error = $uploader->error;
		if ($error) { return $error; }
		return (object) array(
			'coded_name' => $coded_name,
			'filename' => $filename,
			'extension' => $ext
		);
	}
	
	protected function saveBase64($_base64_str='', $_options=[]) { 
		$options = array_merge([
			'destination' => storage_path(),
			'extension' => 'file',
			'filename' => time()	
		], $_options);
		$error = '';
		$base64_str = trim($_base64_str);
		if ($base64_str === '') { return false; }
		// See: http://www.tricksofit.com/2014/10/save-base64-encoded-image-to-file-using-php#.VhMnlPmqqko
		$data = base64_decode($base64_str);
		$coded_name = $this->_getCodedName($options['filename']).'.'.strtolower($options['extension']);
		$file_path = rtrim($options['destination'], '/').'/'.$coded_name;
		App\Files::put($file_path, $data);
		return (object) [
			'coded_name' => $coded_name,
			'filename' => $options['filename'],
			'extension' => $options['extension']
		];
	}
	
	protected function getExtension($_file) {
		$uploader = new fileManager;
		$ext = $uploader->getExtension($_file);
		if ($ext) { return strtolower($ext); }
		return '';
	}
	
	protected function reArrayFiles(&$file_post) {
		// Rearranges the multiple files super global array($_FILES)
		// See: http://php.net/manual/en/features.file-upload.multiple.php#53240
		$file_ary = array();
		$file_count = count($file_post['name']);
		$file_keys = array_keys($file_post);
		for ($i=0; $i<$file_count; $i++) {
			foreach ($file_keys as $key) {
				$file_ary[$i][$key] = $file_post[$key][$i];
			}
		}
		return $file_ary;
	}
	
	protected function download($_path) {
		if (! App\Files::isReadable($_path)) {
			xplog('Unable to read file "'.$_path.'" when trying to download', __METHOD__);
			return '';
		}
		$file_size = filesize($_path);
		$file_contents = App\Files::get($_path);
		$file_name = basename($_path);
		header("Content-length: ".$file_size);
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename="'.$file_name.'"');
		return $file_contents;
	}
}