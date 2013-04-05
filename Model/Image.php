<?php
App::uses('TinyMceAppModel', 'TinyMce.Model');
App::uses('Folder', 'Utility');
App::uses('Hash', 'Utitily');
class Image extends TinyMceAppModel {

	var $name = 'Image';

	var $validate = array(
		'image' => array(
			'rule' => array(
				'validFile',
				array(
					'required' => true,
					'extensions' => array(
						'jpg',
						'jpeg',
                        'gif',
                        'png'
					)
				)
			)
		)
	);

    var $useTable = false;

    function readFolder($folderName = null) {
        $folder = new Folder($folderName);
        $images = $folder->read(
            true,
            array(
                '.',
                '..',
                'Thumbs.db'
            ),
            true
        );
        $images = $images[1]; // We are only interested in files

        // Get more infos about the images
        $retVal = array();
        foreach ($images as $the_image)
        {
            $the_image = new File($the_image);
            $retVal[] = array_merge(
                $the_image->info(),
                array(
                    'size' => $the_image->size(),
                    'last_changed' => $the_image->lastChange()
                )
            );
        }
		$retVal = Hash::sort($retVal, '{n}.last_changed', 'DESC');
		$retVal = array_slice($retVal, 0, 15);

        return $retVal;
    }

    function upload($data = null) {
        $this->set($data);

        if(empty($this->data)) {
            return false;
        }

        // Validation
        if(!$this->validates()) {
            return false;
        }

        // Move the file to the uploads folder
        if(!move_uploaded_file($this->data['Image']['image']['tmp_name'], WWW_ROOT.'img'.DS.'uploads'.DS.$this->data['Image']['image']['name'])) {
            return false;
        }

        return true;
    }



    function validFile($check, $settings) {
    	$_default = array(
    		'required' => false,
    		'extensions' => array(
    			'jpg',
    			'jpeg',
    			'gif',
    			'png'
    		)
    	);

    	$_settings = array_merge(
    		$_default,
    		is_array($settings) ? $settings : array()
    	);

		// Remove first level of Array
		$_check = array_shift($check);

		if($_settings['required'] == false && $_check['size'] == 0) {
			return true;
        }

        // No file uploaded.
        if($_settings['required'] && $_check['size'] == 0) {
			return false;
        }

        // Check for Basic PHP file errors.
        if($_check['error'] !== 0) {
			return false;
        }

        // Use PHPs own file validation method.
        if(is_uploaded_file($_check['tmp_name']) == false) {
        	return false;
        }

        // Valid extension
        return Validation::extension(
        	$_check,
        	$_settings['extensions']
        );
	}

	function deleteFile($basename = null) {
		if ($basename) {
			$path = WWW_ROOT.'img'.DS.'uploads'.DS.$basename;
			$file = new File($path);
			$file->delete();
			return true;
		}
		return false;
	}
}