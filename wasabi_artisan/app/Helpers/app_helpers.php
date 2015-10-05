<?php 

if (! function_exists('_pr')) {
	function _pr($_arr) {
		echo '<pre>';
		print_r($_arr);
		echo '</pre>';
	}	
}

function xplog($_msg, $_class_info=false) {
    App\Xplog::write($_msg, $_class_info);
}

function pinoy_time($_date_string, $_date_format='Y-m-d h:i:s A') {
    App\Xplog::pinoyTime($_date_string, $_date_format);
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