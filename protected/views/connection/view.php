<?php
$this->breadcrumbs=array(
	'Connections'=>array('manage'),
	"$model->IPAddress: $model->username"
);

$this->menu=array(
	array('label'=>'Back to Manage Connection', 'url'=>array('manage')),
	array('label'=>'Add User', 'url'=>array('add')),
	array('label'=>'Edit User', 'url'=>array('edit', 'id'=>$model->connectionID)),
	array('label'=>'Delete User', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->connectionID),'confirm'=>'Are you sure you want to delete this item?')),

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
