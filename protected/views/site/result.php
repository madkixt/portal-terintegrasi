<?php
if ($error !== '') {
?><div class='flash-error'><?php
	echo $error;?></div>
<?php
	return;
}?>

<p><?php
	echo CHtml::link('Download as Excel (.xls)', array('download', 'type' => 'xls'));
?></p>
<p>
<?php
	echo CHtml::link('Download as Text (.txt)', array('download', 'type' => 'txt'));
?></p>
<br />

<?php
$queries = explode(";", $query);

for ($i = 0; $i < count($data); $i++) { ?>
	<p><strong>Statement
<?php
	echo ($i+1);
	if (!$this->isUser())
		echo ": " . $queries[$i];
?></strong></p>

<div style='overflow: auto; height: 600px'>
<?php
	$this->widget('PGridView', array(
    	'dataProvider'=> new CArrayDataProvider($data[$i], array(
			'keyField' => false,
			'pagination' => false, //array(
				// 'pageSize' => 100
			// ),
		)),
	));
?>
</div>
<br /><hr /><br />
<?php } ?>