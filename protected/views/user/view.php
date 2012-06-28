<?php
$this->breadcrumbs=array(
	'Users'=>array('manage'),
	$model->userID,
);

$this->menu=array(
	array('label'=>'Back to Manage User', 'url'=>array('manage')),
	array('label'=>'Add User', 'url'=>array('add')),
	array('label'=>'Edit User', 'url'=>array('edit', 'id'=>$model->userID)),
	array('label'=>'Delete User', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->userID),'confirm'=>'Are you sure you want to delete this item?')),
);
?>

<h1>View User #<?php echo $model->userID; ?></h1>


<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'userID',
		array(
			'value'=>$model->userRoles[$model->admin],
			'name'=>'admin'
		),
		'username',
		'description',
		'creationDate',
		'modifiedDate',
		'createdBy',
		'lastModifiedBy',
	),
)); ?>
