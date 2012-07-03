<?php
$this->breadcrumbs=array(
	'Connections'=>array('manage'),
	$model->name
);

$this->menu=array(
	array('label'=>'Add Connection', 'url'=>array('add'), 'visible' => Yii::app()->user->getState('admin')),
	array('label'=>'Edit Connection', 'url'=>array('edit', 'id'=>$model->connectionID), 'visible' => Yii::app()->user->getState('admin')),
	array('label'=>'Delete Connection', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->connectionID),'confirm'=>'Are you sure you want to delete this item?'), 'visible' => Yii::app()->user->getState('admin')),
	array('label'=>'Back to Manage Connection', 'url'=>array('manage')),
);
?>

<h1>View Connection <?php echo $model->IPAddress.':'.$model->username; ?></h1>

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
