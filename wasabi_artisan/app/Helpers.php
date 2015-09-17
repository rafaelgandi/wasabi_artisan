<?php
namespace App;

use App;

class Helpers {
   
	public static function load($_file_name) {
		require_once app_path('Helpers/'.$_file_name);
	}
}
