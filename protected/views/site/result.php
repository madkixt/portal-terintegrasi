<p><?php
	echo CHtml::link('Download as Excel (.xls)', array('download', 'download' => 'txt'));
?></p>
<p>
<?php
	echo CHtml::link('Download as Text (.txt)', array('download', 'type' => 'txt'));
?></p>
<br />

<div style='overflow: auto; height: 600px'>
<?php
	$this->widget('zii.widgets.grid.CGridView', array(
    	'dataProvider'=> new CArrayDataProvider($data, array(
			'keyField' => false,
			'pagination' => array(
				'pageSize' => 100
			),
			'id' => 'resultgrid',
		)),
	));
?>
</div>