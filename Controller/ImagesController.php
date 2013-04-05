<?php
App::uses('TinyMceAppController', 'TinyMce.Controller');
class ImagesController extends TinyMceAppController {

    var $name = 'Images';

    var $helpers = array(
        'Html',
        'Form',
        'Js',
        'Number' // Used to show readable filesizes
    );


    function admin_index() {
		//$this->layout =  false;
        $this->set(
            'images',
            $this->Image->readFolder(WWW_ROOT.'img'.DS.'uploads')
        );
    }

    function admin_upload() {
        // Upload an image
        if (!empty($this->data)) {
            // Validate and move the file
            if($this->Image->upload($this->data)) {
                $this->Session->setFlash('The image was successfully uploaded.');
            } else {
                $this->Session->setFlash('There was an error with the uploaded file.');
            }

            $this->redirect(
                array(
                    'action' => 'index'
                )
            );
        } else {
            $this->redirect(
                array(
                    'action' => 'index'
                )
            );
        }
    }

	function admin_delete($basename = null) {
		if ($basename) {
			$this->Image->deleteFile($basename);
		}
		$this->redirect(array('action' => 'index'));
	}
}