<?php
$this->breadcrumbs=array(
	'Connections'=>array('manage'),
	$model->connectionID,
);

$this->menu=array(
	array('label'=>'Back to Manage Connection', 'url'=>array('manage')),
);
?>

<h1>View Connection #<?php echo $model->connectionID; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'connectionID',
		'serverName',
		'IPAddress',
		'username',
		'password',
		'description',
		'creationDate',
		'modifiedDate',
		'createdBy',
		'lastModifiedBy',
	),
)); ?>
