<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Storage;

class Wasabi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wasabi';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add some oriental hotness to laravel 5';
	
	private $dump_string = '';
	
	private $WASABI_DIR = '';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
		$this->WASABI_DIR = app_path('Console/Commands/wasabi_artisan');
		$this->dump_string = $this->get($this->WASABI_DIR.'/templates/header.txt');
    }
	
	private function _makeFile($_file_path, $_contents='') {
		$file_handler = fopen($_file_path, 'w');
		fwrite($file_handler, $_contents);
		fclose($file_handler);
		return basename($_file_path);
	}
	
	private function _makeFileIfNotExists($_path, $_contents='') {
		if (! is_file($_path)) {
			$this->_makeFile($_path, $_contents);
		}
		return $_path;
	}	
	
	private function _isDir($directory) {
		return is_dir(rtrim($directory, '/'));
	}
	
	private function _makeDir($path, $mode = 0777, $recursive = false, $force = false) {
		if ($force) {
			return @mkdir($path, $mode, $recursive);
		}
		else {
			return mkdir($path, $mode, $recursive);
		}
	}
	
	private function _makeDirIfNotExists($_path, $_contents='') {
		if (! $this->_isDir($_path)) {
			$this->_makeDir($_path, 0777, true);
		}
		return $_path;
	}
	
	protected function get($_path) {
		if (is_file($_path)) { return file_get_contents($_path); }
		$this->_log($_path.' is not a file.');
	}
	
	private function _recurseCopy($src,$dst) { 
		// See: http://www.internoetics.com/2011/01/30/copy-the-contents-of-a-directory-from-one-location-to-another-with-php/
		$src = rtrim($src, '/');
		$dst = rtrim($dst, '/');
		$dir = opendir($src); 
		@mkdir($dst); 
		while(false !== ( $file = readdir($dir)) ) { 
			if (( $file != '.' ) && ( $file != '..' )) { 
				if ( is_dir($src . '/' . $file) ) { 
					$this->_recurseCopy($src . '/' . $file,$dst . '/' . $file); 
				} 
				else { 
					copy($src . '/' . $file,$dst . '/' . $file); 
				} 
			} 
		} 
		closedir($dir); 
		$this->_log($dst.' copied');
	} 
	
	private function _removeDir($dir) {
		if (! is_dir($dir)) { return; }	
		foreach(glob($dir . '/*') as $file) { 
			if (is_dir($file)) { $this->_removeDir($file); } 
			else { unlink($file); }
		} 
		rmdir($dir); 
	}
	
	private function _log($_msg) {
		$this->dump_string .= $_msg.PHP_EOL;
		return $this;
	}
	
	private function _copyFiles($_files=[]) {
		foreach ($_files as $from => $to) {
			if (is_file($from)) {
				if (! copy($from, $to)) {
					$this->_log('Unable to copy file "'.$from.'" to "'.$to.'"');
				}	
				else {
					$this->_log('File '.$to.' copied');
				}
			}
			else {
				$this->_log('Unable to find file "'.$from.'" when trying to copy it');
			}
		}
		return $this;
	}
	
	private function _removeFiles($_files=[]) {
		$_files = (! is_array($_files)) ? [$_files] : $_files;
		foreach ($_files as $file) {
			if (is_file($file)) {
				if (! @unlink($file)) {
					$this->_log('Unable to remove file "'.$file.'"');
				}
			}			
		}
		return $this;
	}
	
	private function _rootDirectory() {
		$this->_log('doing root changes...');
		$root_dir = app_path('..');
		$etc_dir = $this->_makeDirIfNotExists($root_dir.'/etc');
		// Backup the initial installation files from laravel //
		$etc_files = [
			$root_dir.'/.env.example' => $etc_dir.'/.env.example',
			$root_dir.'/gulpfile.js' => $etc_dir.'/gulpfile.js',
			$root_dir.'/phpspec.yml' => $etc_dir.'/phpspec.yml',
			$root_dir.'/phpunit.xml' => $etc_dir.'/phpunit.xml',
			$root_dir.'/readme.md' => $etc_dir.'/readme.md',
			$root_dir.'/composer.json' => $etc_dir.'/composer.json',
			$root_dir.'/composer.lock' => $etc_dir.'/composer.lock',
			$root_dir.'/package.json' => $etc_dir.'/package.json',
			$root_dir.'/.gitignore' => $etc_dir.'/.gitignore',
			$root_dir.'/.env' => $etc_dir.'/.env'
			
		];
		$this->_copyFiles($etc_files);
		$this->_removeFiles(array_keys($etc_files));
		// Replace with the files from wasabi //
		$this->_copyFiles([
			$this->WASABI_DIR.'/composer.json' => $root_dir.'/composer.json',
			$this->WASABI_DIR.'/composer.lock' => $root_dir.'/composer.lock',
			$this->WASABI_DIR.'/package.json' => $root_dir.'/package.json',
			$this->WASABI_DIR.'/.env' => $root_dir.'/.env',
			$this->WASABI_DIR.'/.gitignore' => $root_dir.'/.gitignore',
			$this->WASABI_DIR.'/environment.php' => $root_dir.'/environment.php'
		]);	
		$this->_log('root changes done...');
		return $this;
	}
	
	private function _publicDirectory() {
		$this->_log('doing public dir changes...');
		$this->_makeDirIfNotExists(public_path('js/'));
		$this->_makeDirIfNotExists(public_path('css/'));
		$this->_makeDirIfNotExists(public_path('uploads/'));
		// Populate the necessary directories //
		$this->_recurseCopy($this->WASABI_DIR.'/public/js/', public_path('js/'));
		$this->_recurseCopy($this->WASABI_DIR.'/public/css/', public_path('css/'));
		$this->_recurseCopy($this->WASABI_DIR.'/public/uploads/', public_path('uploads/'));
		// Replace the index.php with a wasabified index.php //
		$this->_removeFiles(public_path('index.php')); 
		if (! copy($this->WASABI_DIR.'/public/index.php', public_path('index.php'))) {
			$this->_log('ERROR! Unable to copy public/index.php');
		}
		else {
			$this->_log(public_path('index.php').' copied');
		}
		$this->_log('public dir changes done...');
	}
	
	private function _configDirectory() {
		$this->_log('doing config dir changes...');
		$config_path = config_path();
		$this->_removeDir($config_path); // remove the current config dir
		$this->_makeDirIfNotExists($config_path); // make the dir again
		$this->_recurseCopy($this->WASABI_DIR.'/config/', $config_path); // place the wasabified config files
		$this->_log('config dir changes done...');
	}
	
	private function _appDirectory() {
		$this->_log('doing app dir changes...');
		// app/Exceptions/Handler.php //
		$this->_removeFiles(app_path('Exceptions/Handler.php'));
		if (! copy($this->WASABI_DIR.'/app/Exceptions/Handler.php', app_path('Exceptions/Handler.php'))) {
			$this->_log('ERROR! Unable to copy /app/Exceptions/Handler.php');
		}
		else {
			$this->_log(app_path('Exceptions/Handler.php').' copied');
		}
		// Controllers and Routes //
		$this->_recurseCopy($this->WASABI_DIR.'/app/Http/Routes/', app_path('Http/Routes/')); // Make a Routes directory
		$this->_copyFiles([
			$this->WASABI_DIR.'/app/Http/Controllers/AuthController.php' => app_path('Http/Controllers/AuthController.php'),
			$this->WASABI_DIR.'/app/Http/Controllers/WasabiBaseController.php' => app_path('Http/Controllers/WasabiBaseController.php'),
			$this->WASABI_DIR.'/app/Http/routes.php' => app_path('Http/routes.php')		
		]);	
		// Helpers dir //
		$this->_recurseCopy($this->WASABI_DIR.'/app/Helpers/', app_path('Helpers/'));
		$this->_recurseCopy($this->WASABI_DIR.'/app/Nkie12/', app_path('Nkie12/')); // <-- VERY IMPORTANT
		$this->_recurseCopy($this->WASABI_DIR.'/app/third_party/', app_path('third_party/'));
		// Wasabi Utility Components //
		$this->_copyFiles([
			$this->WASABI_DIR.'/app/app_autoloader.php' => app_path('app_autoloader.php'),
			$this->WASABI_DIR.'/app/Assets.php' => app_path('Assets.php'),
			$this->WASABI_DIR.'/app/Crypt.php' => app_path('Crypt.php'),
			$this->WASABI_DIR.'/app/Files.php' => app_path('Files.php'),
			$this->WASABI_DIR.'/app/Json.php' => app_path('Json.php'),
			$this->WASABI_DIR.'/app/Helpers.php' => app_path('Helpers.php'),
			$this->WASABI_DIR.'/app/Upload.php' => app_path('Upload.php')		
		]);		
		$this->_log('app dir changes done...');
	}
	
	private function _wasabiExit() {
		$root_dir = app_path('..');
		// Add wasabi custom welcome page //
		unlink($root_dir.'/resources/views/welcome.blade.php');
		$this->_copyFiles([
			$this->WASABI_DIR.'/welcome.blade.php' => $root_dir.'/resources/views/welcome.blade.php'
		]);
		$this->_log('====================================================');
		$this->_log('Wasabified at '.date('Y-m-d @ h:i:s A'));
		$this->_log('====================================================');
		$this->_makeFileIfNotExists($root_dir.'/wasabified.dump', $this->dump_string);
		return $this;
	}

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
		set_time_limit(0);
		$laravel = app(); // See: http://www.elcoderino.com/check-laravel-version/
		if (is_file(app_path('../wasabified.dump'))) {
			$this->info('Wasabi has already been installed here');	
			return;
		}	
		if (! $this->_isDir($this->WASABI_DIR)) {
			$this->info('Unable to find the wasabi setup directory(wasabi_artisan)');	
			return;
		}	
		$confirmation_message = 'Confirm installation. Wasabi currently works on Laravel version 5.1.10.'.PHP_EOL.' Your current version installed is '.$laravel::VERSION.'. '.PHP_EOL.'Do you wish to continue?';
		if (! $this->confirm($confirmation_message, false)) {
			$this->comment('Thanks for stopping by :)');
			return;
		}
		$this->_rootDirectory();
		$this->_publicDirectory();
		$this->_configDirectory();
		$this->_appDirectory();
		$this->_wasabiExit();
		$this->info($this->dump_string);	
		$this->info('DONT FORGET TO REMOVE THE WASABI SETUP DIRECTORY FOUND AT '.PHP_EOL.'"'.$this->WASABI_DIR.'"');			
		// See: https://adamcod.es/2013/03/07/composer-install-vs-composer-update.html
		$this->info(PHP_EOL.PHP_EOL.'Now run composer update to compelete the installation... Good luck! :)');			
    }
}
