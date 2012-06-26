<?php
$this->breadcrumbs=array(
	'Connections'=>array('index'),
	$model->connectionID,
);

$this->menu=array(
	array('label'=>'List Connection', 'url'=>array('index')),
	array('label'=>'Create Connection', 'url'=>array('create')),
	array('label'=>'Update Connection', 'url'=>array('update', 'id'=>$model->connectionID)),
	array('label'=>'Delete Connection', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->connectionID),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Connection', 'url'=>array('admin')),
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
