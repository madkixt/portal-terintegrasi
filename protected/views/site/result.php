<p><?php
	echo CHtml::link('Download as Excel (.xls)', array('download', 'type' => 'xls'));
?></p>
<p>
<?php
	echo CHtml::link('Download as Text (.txt)', array('download', 'type' => 'txt'));
?></p>
<br />
<?php
	$this->widget('zii.widgets.grid.CGridView', array(
    	'dataProvider'=>$res,
	));
?>