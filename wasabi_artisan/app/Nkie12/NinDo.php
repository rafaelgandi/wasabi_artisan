<?php 
namespace App\Nkie12; // <-- A tribute to a four legged friend (https://flic.kr/p/pYYo6P). <3 RIP 01-12-2015 +

use App;
use App\Nkie12;
use Exception;

abstract class NinDo { // <-- See: http://naruto.wikia.com/wiki/Nind%C5%8D (My tribute to the Naruto series 11-07-2014)
	private static $instances = [];
	protected static function handleCalls($_instance, $_method, $_parameters) {
		// See: http://usman.it/laravel-4-uses-static-not-true/
		if (in_array($_method, get_class_methods($_instance))) {
			if (strpos($_method, '_') !== 0) {
				return call_user_func_array(array($_instance, $_method), $_parameters);
			}
			throw new Exception('[[[Method "'.$_method.'()" is private]]]');	
		}
		else {
			throw new Exception('[[[Method "'.$_method.'()" not found in class "'.get_class($_instance).'"]]]');	
		}	
	}
	
	public function __call($_method, $_parameters) {
		// LM: 09-01-2015 Using late static bindings //
		// See: http://php.net/manual/en/language.oop5.late-static-bindings.php
		return self::handleCalls(static::instance(), $_method, $_parameters);
	}
	
	public static function __callStatic($_method, $_parameters) { 
		// See: http://usman.it/laravel-4-uses-static-not-true/
		// See: http://php.net/manual/en/language.oop5.late-static-bindings.php
		return self::handleCalls(static::instance(), $_method, $_parameters);
	}
	
	protected static function getInstance() {
		$class_name = strtolower(get_called_class());
		// NOTE: Make sure the __construct of the child classes are set to "protected" or "public"
		if (! isset(self::$instances[$class_name])) { self::$instances[$class_name] = new static; }
		return self::$instances[$class_name];
	}
	// LM: 09-02-2015 
	// Made into an abstract class
	abstract public static function instance();
	// ie. public static function instance() { return parent::getInstance(); } <-- Sample implementation on the child class
	
}