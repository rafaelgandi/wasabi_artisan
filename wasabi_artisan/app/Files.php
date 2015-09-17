<?php 
namespace App;

use App;
use App\Nkie12;
use Exception;
use FilesystemIterator;
use Symfony\Component\Finder\Finder;
/* 
	Note: Most of the methods here are from the Laravel 4 file component found here:
	\vendor\laravel\framework\src\Illuminate\Filesystem
 */
class FileNotFoundException extends Exception {}

class Files extends App\Nkie12\NinDo {
	
	public static function instance() { return parent::getInstance(); }
	
	protected function exists($path) {
		return file_exists($path);
	}
	
	protected function get($path) {
		if ($this->isFile($path)) { return file_get_contents($path); }
		throw new FileNotFoundException("File does not exist at path {$path}");
	}
	
	protected function put($path, $contents, $_supress=false) {
		if ($_supress === true) {
			return @file_put_contents($path, $contents);
		}
		return file_put_contents($path, $contents);
	}
	
	protected function prepend($path, $data) {
		if ($this->exists($path)) {
			return $this->put($path, $data.$this->get($path));
		}
		else {
			return $this->put($path, $data);
		}
	}
	
	protected function append($path, $data, $_supress=false) {
		if ($_supress === true) {
			return @file_put_contents($path, $data, FILE_APPEND);
		}
		return file_put_contents($path, $data, FILE_APPEND);
	}
	
	protected function delete($paths) {
		$paths = is_array($paths) ? $paths : func_get_args();
		$success = true;
		foreach ($paths as $path) { 
			if (! $this->exists($path)) { continue; } // Make sure file exists first
			if (! @unlink($path)) { $success = false; } 
		}
		return $success;
	}
	
	protected function move($path, $target) {
		return rename($path, $target);
	}
	
	protected function copy($path, $target) {
		return copy($path, $target);
	}
	
	protected function extension($path) {
		return pathinfo($path, PATHINFO_EXTENSION);
	}
	
	protected function type($path) {
		return filetype($path);
	}
	
	protected function size($path) {
		return filesize($path);
	}
	
	protected function lastModified($path) {
		return filemtime($path);
	}
	
	protected function isDir($directory) {
		return is_dir(rtrim($directory, '/'));
	}
	
	protected function isWritable($path) {
		return is_writable($path);
	}
	
	protected function isReadable($_path) {
		return is_readable($_path);
	}
	
	protected function isFile($file) {
		return is_file($file);
	}
	
	protected function glob($pattern, $flags = 0) {
		return glob($pattern, $flags);
	}
	
	protected function files($directory) {
		$glob = glob($directory.'/*');
		if ($glob === false) { return array(); }
		// To get the appropriate files, we'll simply glob the directory and filter
		// out any "files" that are not truly files so we do not end up with any
		// directories in our list, but only true files within the directory.
		return array_filter($glob, function($file) {
			return filetype($file) == 'file';
		});
	}
	
	protected function makeDir($path, $mode = 0777, $recursive = false, $force = false) {
		if ($force) {
			return @mkdir($path, $mode, $recursive);
		}
		else {
			return mkdir($path, $mode, $recursive);
		}
	}
	
	protected function makeFile($_file_path, $_contents='') {
		$file_handler = fopen($_file_path, 'w');
		fwrite($file_handler, $_contents);
		fclose($file_handler);
		return basename($_file_path);
	}
	
	protected function makeDirIfNotExists($_path) {
		if (! $this->isDir($_path)) {
			$this->makeDir($_path, 0777, true);
		}
		return $_path;
	}

	protected function makeFileIfNotExists($_path, $_contents='') {
		if (! $this->isFile($_path)) {
			$this->makeFile($_path, $_contents);
		}
		return $_path;
	}	
	
	protected function deleteAllFiles($_dir) {
		// See: http://stackoverflow.com/a/4594262
		$files = $this->glob(rtrim($_dir,'/').'/*'); // get all file names
		foreach ($files as $file) { // iterate files
			if ($this->isFile($file)) {
				$this->delete($file); // delete file  
			}			
		}
		return true;
	}
	
}