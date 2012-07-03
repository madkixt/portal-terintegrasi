<?php
$this->breadcrumbs=array(
	'Users'=>array('manage'),
	$model->username,
);

$this->menu=array(
	array('label'=>'Add User', 'url'=>array('add'), 'visible' => Yii::app()->user->getState('admin')),
	array('label'=>'Edit User', 'url'=>array('edit', 'id'=>$model->userID), 'visible' => $model->editClickable),
	array('label' => 'Queries', 'url' => array('/query', 'id' => $model->userID)),
	array('label' => 'Connections', 'url' => array('/connection', 'id' => $model->userID)),
	array('label' => 'Assign Query', 'url' => array('assignQuery', 'id' => $model->userID), 'visible' => $model->assignable),
	array('label' => 'Assign Connection', 'url' => array('assignConnection', 'id' => $model->userID), 'visible' => $model->assignable),
	array('label'=>'Delete User', 'url'=>'#', 'visible' => $model->deleteClickable, 'linkOptions'=>array('submit'=>array('delete','id'=>$model->userID),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Back to Manage User', 'url'=>array('manage'), 'visible' => Yii::app()->user->getState('admin')),
);
?>

<h1>View User <?php echo $model->username; ?></h1>


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
		array(
			'name' => 'createdBy',
			'value' => CHtml::encode($model->creatorUsername)
		),
		array(
			'name' => 'lastModifiedBy',
			'value' => CHtml::encode($model->editorUsername)
		)
	),
	'nullDisplay' => ''
)); ?>
