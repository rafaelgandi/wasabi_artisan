<?php 

if (! function_exists('_pr')) {
	function _pr($_arr) {
		echo '<pre>';
		print_r($_arr);
		echo '</pre>';
	}	
}

function xplog($_msg, $_class_info = false) {
    $logfile = App\Files::makeFileIfNotExists(storage_path('debug.logs'));
    $nl = chr(10);
    $msg = '';
    $data = array();
    $data['msg'] = '[' . trim($_msg) . ']';
    $data['request_uri'] = $_SERVER['REQUEST_URI'];
    $data['ip'] = $_SERVER['REMOTE_ADDR'];
    if (!!$_class_info) {
        if (is_object($_class_info)) {
            $data['classname'] = get_class($_class_info);
        } else {
            $data['code'] = $_class_info;
        }
    }
    $msg = str_replace('\n', ' ', json_encode($data));
    //$msg = $nl.'INFO - '.date('Y-m-d h:i:s A').' --> '.str_replace('\\', '', $msg);  
    $msg = $nl . 'INFO - ' . pinoy_time(date('Y-m-d h:i:s A'), 'Y-m-d h:i:s A') . ' --> ' . str_replace('\\', '', $msg);
    App\Files::append($logfile, $msg, true);
}

function pinoy_time($_date_string, $_date_format = 'Y-m-d h:i:s A') {
    // See: http://stackoverflow.com/questions/2505681/timezone-conversion-in-php
    // See: http://php.net/manual/en/timezones.asia.php
    if (!class_exists('DateTime')) {
        throw new Exception('Unable to use DateTime class when calling pinoy_time() function.');
        return false;
    }
    $datetime = new DateTime($_date_string);
    $phil_time = new DateTimeZone('Asia/Singapore'); // phil timezone
    $datetime->setTimezone($phil_time);
    return $datetime->format($_date_format);
}

function make_file($_file_path, $_contents = '') {
    $file_handler = fopen($_file_path, 'w');
    fwrite($file_handler, $_contents);
    fclose($file_handler);
    return basename($_file_path);
}

function make_dir_if_not_exists($_path) {
    if (!is_dir($_path)) {
        @mkdir($_path, 0777, true);
    }
    return $_path;
}

function delete_all_files($_dir) {
    // See: http://stackoverflow.com/a/4594262
    $files = glob(rtrim($_dir, '/') . '/*'); // get all file names
    foreach ($files as $file) { // iterate files
        if (is_file($file))
            @unlink($file); // delete file
    }
    return true;
}

// Helpers that are common to the whole ink in time application //
//include_once 'ink_helpers.php';