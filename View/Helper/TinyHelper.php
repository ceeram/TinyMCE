<?php
/**
 * Copyright 2009-2010, Cake Development Corporation (http://cakedc.com)
 *
 * Licensed under The LGPL License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2009-2010, Cake Development Corporation (http://cakedc.com)
 * @license LGPL License (http://www.opensource.org/licenses/lgpl-2.1.php)
 */

/**
 * TinyMCE Helper
 *
 * @package tiny_m_c_e
 * @subpackage tiny_m_c_e.views.helpers
 */

class TinyHelper extends AppHelper {

/**
 * Other helpers used by FormHelper
 *
 * @var array
 */
	public $helpers = array('Html');

/**
 * Configuration
 *
 * @var array
 */
	public $configs = array('default' => array(
		'mode' => 'textareas',
		'theme' => 'advanced',
		'skin'=> 'o2k7',
		'theme_advanced_toolbar_location' => 'top',
		'theme_advanced_toolbar_align' => 'left',
		'visual' => 'true',
		'accessibility_warnings' => 'false',
		'fix_list_elements' => 'true',
		'verify_html' => 'true',
		'verify_css_classes' => 'false',
		'plugins' => 'spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template',
		'paste_auto_cleanup_on_paste' => 'true',
		'paste_remove_spans' => 'true',
		'paste_remove_styles' => 'true',
		'theme_advanced_buttons1' => 'save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect,fullscreen',
		'theme_advanced_buttons2' => 'cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor',
		'theme_advanced_buttons3' => 'spellchecker,tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,iespell,media,advhr,|,print,|,ltr,rtl,|',
		'width' => '100%',
		'height'=> '300',
		'language' => 'nl',
		'file_browser_callback' => 'fileBrowserCallBack',
		'convert_urls' => true,
		'relative_urls' => false
	));

/**
 * Default values
 *
 * @var array
 */
	protected $_defaults = array();

/**
 * Adds a new editor to the script block in the head
 *
 * @see http://wiki.moxiecode.com/index.php/TinyMCE:Configuration for a list of keys
 * @param mixed If array camel cased TinyMce Init config keys, if string it checks if a config with that name exists
 * @return void
 */
	public function editor($options = array()) {
		if (is_string($options)) {
			if (isset($this->configs[$options])) {
				$options = $this->configs[$options];
			} else {
				throw new OutOfBoundsException(sprintf(__('Invalid TinyMCE configuration preset %s', true), $options));
			}
		}
		$options = array_merge($this->_defaults, $options);
		$lines = '';

		foreach ($options as $option => $value) {
			$lines .= Inflector::underscore($option) . ' : "' . $value . '",' . "\n";
		}
		$lines = rtrim($lines);
		$lines = rtrim($lines, ',');
		$this->Html->scriptBlock('tinyMCE.init({' . "\n" . $lines . '});' . "\n", array('inline' => false));
	}

/**
 * beforeRender callback
 *
 * @return void
 */
	public function beforeRender($viewFile) {
		$this->Html->script('/tiny_mce/js/tiny_mce/tiny_mce.js', false);
		$this->editor('default');
	}
}
