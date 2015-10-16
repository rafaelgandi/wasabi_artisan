<?php
/* 
Simple PHP Class used for uploading files and images
See: http://digipiph.com/blog/simple-php-class-used-uploading-files-and-images

I wrote a simple PHP class that may be used for uploading and deleting files or images. This code allows the 
programmer to set the destination, file name, required extension, and max file size. The PHP class checks the 
file validation with its own internal functions and stores the errors or will automatically print the errors on 
screen if that is how you want them handled.
*/
if (!class_exists('fileManager')):
    class fileManager {
        
        //default settings
        public $destination = '/images/';
        public $fileName = 'file.txt';
        public $maxSize = '1048576'; // bytes (1048576 bytes = 1 meg)
        public $allowedExtensions = array('jpg', 'png', 'gif');
        public $printError = TRUE;
        public $error = '';
        
        //START: Functions to Change Default Settings
        public function setDestination($newDestination) {
            $this->destination = $newDestination;
        }
        public function setFileName($newFileName) {
            $this->fileName = $newFileName;
        }
        public function setPrintError($newValue) {
            $this->printError = $newValue;
        }
        public function setMaxSize($newSize) {
            $this->maxSize = $newSize;
        }
        public function setAllowedExtensions($newExtensions) {
            if (is_array($newExtensions)) {
                $this->allowedExtensions = $newExtensions;
            } else {
                $this->allowedExtensions = array(
                    $newExtensions
                );
            }
        }
        //END: Functions to Change Default Settings
        
        //START: Process File Functions
        public function upload($file) {
            
            $this->validate($file);
            
            if ($this->error) {
                if ($this->printError)
                    throw new Exception($this->error);
            } else {
                move_uploaded_file($file['tmp_name'], $this->destination . $this->fileName) or $this->error .= 'Destination Directory Permission Problem('.$this->destination . $this->fileName.').<br />';
                if ($this->error && $this->printError)
                    throw new Exception($this->error);
            }
        }
        public function delete($file) {
            
            if (file_exists($file)) {
                unlink($file) or $this->error .= 'Destination Directory Permission Problem.<br />';
            } else {
                $this->error .= 'File not found! Could not delete: ' . $file . '<br />';
            }
            
            if ($this->error && $this->printError)
                throw new Exception($this->error);
        }
        //END: Process File Functions
        
        //START: Helper Functions
        public function validate($file) {
            
            $error = '';
            
            //check file exist
            if (empty($file['name']))
                $error .= 'No file found.<br />';
            //check allowed extensions
            //if (!in_array($this->getExtension($file), $this->allowedExtensions))
                //$error .= 'Extension is not allowed.<br />';
            //check file size
            if ($file['size'] > $this->maxSize)
                $error .= 'Max File Size Exceeded. Limit: ' . $this->maxSize . ' bytes.<br />';
            
            $this->error = $error;
        }
        public function getExtension($file) {
            $filepath = $file['name'];
            $ext      = pathinfo($filepath, PATHINFO_EXTENSION);
            return $ext;
        }
        //END: Helper Functions
        
    }
endif;