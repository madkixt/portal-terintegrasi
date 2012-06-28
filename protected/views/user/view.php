<?php
$this->breadcrumbs=array(
	'Users'=>array('manage'),
	$model->userID,
);

$this->menu=array(
	array('label'=>'Back to Manage User', 'url'=>array('manage')),
);
?>

<h1>View User #<?php echo $model->userID; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'userID',
		'admin',
		'username',
		'password',
		'description',
		'creationDate',
		'modifiedDate',
		'createdBy',
		'lastModifiedBy',
	),
)); ?>
