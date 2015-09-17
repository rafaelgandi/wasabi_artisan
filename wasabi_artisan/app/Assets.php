<?php
namespace App;

use App;
use App\Nkie12;
use HTML;
use JShrink; // See: https://github.com/tedious/JShrink
use Exception;

class Assets extends App\Nkie12\NinDo {
	private $BUNDLES_DIR = '';
	private $BASE_BUNDLES_DIR = '';
	
	public static function instance() { return parent::getInstance(); }
	
	protected function __construct() {
		$this->BUNDLES_DIR = public_path('js/bundles');
		$this->BASE_BUNDLES_DIR = url('/js/bundles');
	}
	
	protected function embed($_filenames, $_type='js') {
		$html = '';
		$_filenames = (! is_array($_filenames)) ? array($_filenames) : $_filenames;
		// See: http://cheats.jesse-obrien.ca/#html
		foreach ($_filenames as $f) {
			if (! is_string($f)) {
				xplog('One of the path parameter passed to Eb\Asset::embed() was not a string path.', __METHOD__);
				continue;
			}
			if ($_type === 'js') { // js
				// See: http://laravel-recipes.com/recipes/183
				$html .= '<script src="'.$f.'"></script>'."\n";
			}
			else { // css
				$html .= '<link rel="stylesheet" href="'.$f.'">'."\n";
			}
		}
		return $html;
	}
	
	private function _getFileLastModifiedTimesIndentifier($_file_paths=array()) {
		// Creates a unique string identifier representing the modified times of files 
		// passed to it  as a parameter.
		$_file_paths = (! is_array($_file_paths)) ? array($_file_paths) : $_file_paths;
		$iden = '';
		foreach ($_file_paths as $file) {
			if (App\Files::exists($file)) {
				$time = App\Files::lastModified($file);
				if ($time === false) {
					xplog('Unable to get the last modified time of file "'.$file.'"', __METHOD__);
					continue;
				}
				$iden .= $time;
			}
			else {
				xplog('Found that file "'.$file.'" does not exist when trying to get its last modified time.', __METHOD__);
			}
		}
		return md5($iden);
	}
	
	protected function bundle($_filenames, $_ext='js') {
		$_filenames = (! is_array($_filenames)) ? array($_filenames) : $_filenames;
		$filename = 'wasabi'.md5(implode('|', $_filenames)).$this->_getFileLastModifiedTimesIndentifier($_filenames);	
		$ext = strtolower(trim($_ext));
		$filename = $filename.'.'.$ext;
		$bundle_dir_base_path = $this->BASE_BUNDLES_DIR;
		$content = '/*'."\n * ".implode("\n * ", $_filenames)."\n".' */'."\n";		
		$BUNDLES_DIR = App\Files::makeDirIfNotExists($this->BUNDLES_DIR);
		if (App\Files::exists($BUNDLES_DIR.'/'.$filename)) {
			return $bundle_dir_base_path.'/'.$filename;
		}
		foreach ($_filenames as $f) {
			$f = preg_replace('/\?.+$/','',$f); // Remove query string
			$content .= "\n /*----------- ".$f." ------------*/ \n";
			if ($ext === 'js') {
				// See: https://github.com/tedious/JShrink
				$content .= JShrink\Minifier::minify(App\Files::get(rtrim($f, '/')));
			}			
		}		
		App\Files::makeFile($BUNDLES_DIR.'/'.$filename, $content);
		return $bundle_dir_base_path.'/'.$filename;
	}
}