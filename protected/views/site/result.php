<?php
if ($error !== '') {
?><h3><?php
	echo $error;?></h3>
<?php
	return;
}?>

<p><?php
	echo CHtml::link('Download as Excel (.xls)', array('download', 'download' => 'txt'));
?></p>
<p>
<?php
	echo CHtml::link('Download as Text (.txt)', array('download', 'type' => 'txt'));
?></p>
<br />

<?php
$i = 0;
$queries = explode(";", $query);

for ($i = 0; $i < count($data); $i++) { ?>
<h3>Statement <?php echo ($i+1) . ": " . $queries[$i]; ?></h3>
<div style='overflow: auto; height: 600px'>
<?php
	$this->widget('zii.widgets.grid.CGridView', array(
    	'dataProvider'=> new CArrayDataProvider($data[$i], array(
			'keyField' => false,
			'pagination' => false, //array(
				// 'pageSize' => 100
			// ),
			'id' => 'resultgrid',
		)),
	));
?>
</div>
<br /><hr /><br />
<?php } ?>