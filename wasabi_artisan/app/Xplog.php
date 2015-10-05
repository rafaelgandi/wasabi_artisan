<?php 
namespace App;

use App;
use App\Nkie12;
use Exception;
use Respect\Validation\Validator; // See: https://github.com/Respect/Validation
use DB;

class Xplog extends App\Nkie12\NinDo {	
	public static function instance() { return parent::getInstance(); }
	
	private $log_file;
	
	protected function __construct() {
		$this->log_file = App\Files::makeFileIfNotExists(storage_path('debug.logs'));
	}
	
	protected function pinoyTime($_date_string, $_date_format='Y-m-d h:i:s A') {
		// See: http://stackoverflow.com/questions/2505681/timezone-conversion-in-php
		// See: http://php.net/manual/en/timezones.asia.php
		if (!class_exists('DateTime')) {
			throw new Exception('Unable to use DateTime class when calling Xplog::pinoyTime() method.');
			return false;
		}
		$datetime = new \DateTime($_date_string);
		$phil_time = new \DateTimeZone('Asia/Singapore'); // phil timezone
		$datetime->setTimezone($phil_time);
		return $datetime->format($_date_format);
	}
	
	protected function write($_msg, $_class_info=false) {
		$nl = chr(10);
		$msg = '';
		$data = array();
		$data['msg'] = '[' . trim($_msg) . ']';
		$data['request_uri'] = $_SERVER['REQUEST_URI'];
		$data['ip'] = $_SERVER['REMOTE_ADDR'];
		if (!! $_class_info) {
			if (is_object($_class_info)) {
				$data['classname'] = get_class($_class_info);
			} else {
				$data['code'] = $_class_info;
			}
		}
		$msg = str_replace('\n', ' ', App\Json::encode($data));
		//$msg = $nl.'INFO - '.date('Y-m-d h:i:s A').' --> '.str_replace('\\', '', $msg);  
		$msg = $nl . 'INFO - ' . $this->pinoyTime(date('Y-m-d h:i:s A'), 'Y-m-d h:i:s A') . ' --> ' . str_replace('\\', '', $msg);
		App\Files::append($this->log_file, $msg, true);
	}
	
	protected function backup() {
		/* 
			CREATE TABLE IF NOT EXISTS `xplogs` (
				`id` int(11) NOT NULL,
				`backup_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
				`logs` LONGTEXT NOT NULL
			) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;		
			ALTER TABLE `xplogs` ADD PRIMARY KEY (`id`);		
			ALTER TABLE `xplogs` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
		*/
		$logs = App\Files::get($this->log_file);
		return !! DB::table('xplogs')->insertGetId([
			'logs' => $logs
		]);
	}
	
	protected function clear() {
		if (App\Files::isReadable($this->log_file)) {
			return App\Files::put($this->log_file, '');
		}
		return false;
	}
	
}