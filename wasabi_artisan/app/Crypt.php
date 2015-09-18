<?php 
namespace App;

use App;
use App\Nkie12;
use Exception;

class Crypt extends App\Nkie12\NinDo {
	public static function instance() { return parent::getInstance(); }
	
	public static $crypter_instance = false;

	public function __construct() {
		if (self::$crypter_instance === false) {
			require_once app_path('third_party/Crypter.php');
			self::$crypter_instance = new \Crypter(config('app.key'), MCRYPT_RIJNDAEL_256); 
		}
		return self::$crypter_instance;
	}
	
	protected function encode($_str) {
		return self::$crypter_instance->Encrypt($_str);
	}
	
	protected function decode($_str) {
		return self::$crypter_instance->Decrypt($_str);
	}	
	
	protected function urlencode($_str) {
		return base64_encode($this->encode($_str));
	}
	
	protected function urldecode($_str) {
		return $this->decode(base64_decode($_str));
	}
	
}