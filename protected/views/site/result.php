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
	echo CHtml::link('Download as Access (.accdb)', array('download', 'type' => 'mdb'));
?></p>
<?php
	echo CHtml::link('Download as Text (.txt)', array('download', 'type' => 'txt'));
?></p>
<br />

<?php
$queries = explode(";", $query);

for ($i = 0; $i < count($data); $i++) {
	if (!$this->isUser()) { ?>
	
<p><strong>Statement <?php echo ($i + 1) . ": " . $queries[$i]; ?></strong></p>
	
<?php }
	if (count($data[$i]) === 100) { ?>

<p style='font-size: 12pt; color: orange;'>Only the first 100 rows are displayed.</p>
	
<?php } ?>

<div style='overflow: auto; height: 600px'>
<?php
	$this->widget('PGridView', array(
    	'dataProvider'=> new CArrayDataProvider($data[$i], array(
			'keyField' => false,
			'pagination' => false,
		)),
	));
?>
</div>
<br /><hr /><br />
<?php } ?>