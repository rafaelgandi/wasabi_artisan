<?php 
namespace App;

use App;
use App\Nkie12;
use Exception;
use Respect\Validation\Validator; // See: https://github.com/Respect/Validation

class Json extends App\Nkie12\NinDo {	
	
	public static function instance() { return parent::getInstance(); }
	
	protected function encode($_arr) {		
		return json_encode($_arr);
	}
	
	protected function decode($_str) {
		// Fix json_decode returning NULL on valid json from js.
		// See: http://blog.thefrontiergroup.com.au/2008/11/json_decode_php/
		return json_decode(stripslashes($_str), true);
	}
	
	protected function makeEncodeReady($_str) {
		$value = trim($_str);
		if ($value === '') { return $value; }
		// Make sure the values are json encode ready
		// See: http://www.utf8-chartable.de/unicode-utf8-table.pl?start=128&number=128&utf8=string-literal&unicodeinhtml=hex
		// See: http://stackoverflow.com/questions/10205722/json-encode-invalid-utf-8-sequence-in-argument
		$value = htmlentities((string) $value, ENT_QUOTES, 'utf-8', FALSE);
		// See: http://stackoverflow.com/a/28752503
		return mb_convert_encoding($value,'UTF-8','UTF-8');
	}
	
	protected function isValid($_string='') {
		// See: http://stackoverflow.com/questions/6041741/fastest-way-to-check-if-a-string-is-json-in-php
		json_decode($_string);
		return (json_last_error() == JSON_ERROR_NONE);
	}
	
}