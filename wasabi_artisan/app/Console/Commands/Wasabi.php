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
    protected $description = 'Command description.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
	
	private function _makeFile($_file_path, $_contents='') {
		$file_handler = fopen($_file_path, 'w');
		fwrite($file_handler, $_contents);
		fclose($file_handler);
		return basename($_file_path);
	}
	
	private function _makeDirIfNotExists($_path, $_contents='') {
		if (! is_file($_path)) {
			$this->_makeFile($_path, $_contents);
		}
		return $_path;
	}
	
	private function _recurseCopy($src,$dst) { 
		// See: http://www.internoetics.com/2011/01/30/copy-the-contents-of-a-directory-from-one-location-to-another-with-php/
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
	} 
	
	private function _removeDir($dir) {
		if (! is_dir($dir)) { return; }	
		foreach(glob($dir . '/*') as $file) { 
			if (is_dir($file)) { $this->_removeDir($file); } 
			else { unlink($file); }
		} 
		rmdir($dir); 
	}

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
		//$this->info(file_get_contents(public_path('/index.php')));
		//$this->info($this->_makeDirIfNotExists(public_path('/wasabify.info'), 'hotnesss'));
		set_time_limit(0);
		//$this->_recurseCopy(app_path('third_party'), public_path('third_party'));
		$this->_removeDir(public_path('third_party'));
		$this->info('=====================================');
		$this->info('heheheheheheh');
		$this->info('=====================================');
       
    }
}
