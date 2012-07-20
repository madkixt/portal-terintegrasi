<?php
$this->breadcrumbs=array(
	'Connections'=>array('manage'),
	$model->name
);

$this->menu=array(
	array('label'=>'Add Connection', 'url'=>array('add'), 'visible' => $this->isAdmin()),
	array('label'=>'Edit Connection', 'url'=>array('edit', 'id'=>$model->connectionID), 'visible' => $this->isAdmin()),
	array('label'=>'Assign Connection', 'url'=>array('assign', 'id'=>$model->connectionID), 'visible' => $this->isAdmin()),
	array('label'=>'Delete Connection', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->connectionID),'confirm'=>'Are you sure you want to delete this item?'), 'visible' => $this->isAdmin()),
	array('label'=>'Back to Manage Connection', 'url'=>array('manage')),
);
?>

<h1>View Connection <?php echo $model->name; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
			'name' => 'connectionID',
			'visible' => !$this->isUser()
		),
		'name' => 'IPAddress',
		array(
			'name' => 'dbms',
			'value' => Connection::model()->dbmsOptions[$model->dbms]
		),
		array(
			'name' => 'username',
			'visible' => $this->isAdmin(),
		),
		array(
			'name' => 'password',
			'visible' => $this->isAdmin()
		),
		'serverName',
		'description',
		'creationDate',
		'modifiedDate',
		array(
			'name' => 'createdBy',
			'type' => 'raw',
			'value' => Yii::app()->user->getState('admin') ? CHtml::link($model->creatorUsername, array('user/view', 'id' => $model->createdBy)) : CHtml::encode($model->creatorUsername)
		),
		array(
			'name' => 'lastModifiedBy',
			'type' => 'raw',
			'value' => Yii::app()->user->getState('admin') ? CHtml::link($model->editorUsername, array('user/view', 'id' => $model->lastModifiedBy)) : CHtml::encode($model->editorUsername)
		),
	),
	'nullDisplay' => ''
)); ?>
