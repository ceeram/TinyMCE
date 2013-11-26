<?php
	$this->layout = 'upload_pop_up';
	$full = Router::url('/', true);
	echo $this->Html->scriptBlock(
		"function selectURL(url) {
		if (url == '') return false;
		url = '" . $full . "img/uploads/' + url;
		field = window.top.opener.browserWin.document.forms[0].elements[window.top.opener.browserField];
		field.value = url;
		if (field.onchange != null) field.onchange();
		window.top.close();
		window.top.opener.browserWin.focus();
		}"
	);

	echo $this->Form->create(
		null,
		array(
			'type' => 'file',
			'url' => array(
				'action' => 'upload',
				'admin' => true
			)
		)
	);

	echo $this->Form->input('image', array('label' => 'Afbeelding uploaden', 'type' => 'file'));

	echo $this->Form->end('Uploaden');
?>
<?php
	if(isset($images[0])) {
		$tableCells = array();

		foreach($images As $image) {
			$tableCells[] = array(
				$this->Html->link(
					$image['basename'],
					'#',
					array(
					'onclick' => 'selectURL("'.$image['basename'].'");'
					)
				),
				$this->Html->image('uploads/' . $image['basename'], array('height' => 30)),
				$this->Number->toReadableSize($image['size']),
				date('m/d/Y H:i', $image['last_changed']),
				$this->Html->link('verwijderen', array('controller' => 'images', 'action' => 'delete', 'admin' => true, $image['basename']))
			);
		}

		echo '<table class="upload">';
		echo $this->Html->tableHeaders(
			array('Bestandsnaam', 'Voorbeeld', 'Grootte', 'Gemaakt op', 'Actie'),
			array('class' => 'uploadtr'),
			array('class' => 'uploadth')
		);
		echo $this->Html->tableCells($tableCells);
		echo '</table>';
	} ?>
