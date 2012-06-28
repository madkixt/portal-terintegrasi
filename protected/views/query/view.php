<?php
echo Yii::app()->user->getFlash('tes');
echo Yii::app()->user->getFlash('tes1');

$this->breadcrumbs=array(
	'Queries'=>array('manage'),
	$model->queryID,
);

$this->menu=array(
	array('label'=>'Back to Manage Query', 'url'=>array('manage')),
	array('label'=>'Create Query', 'url'=>array('add')),
	array('label'=>'Update Query', 'url'=>array('edit', 'id'=>$model->queryID)),
	array('label'=>'Delete Query', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->queryID),'confirm'=>'Are you sure you want to delete this item?')),
);
?>

<h1>View Query #<?php echo $model->queryID; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'queryID',
		'judulQuery',
		'isiQuery',
		'databaseName',
		'notes',
		'creationDate',
		'modifiedDate',
		'notesModifiedDate',
		array(
			'name' => 'createdBy',
			'value' => CHtml::encode($model->creatorUsername)
		),
		array(
			'name' => 'lastModifiedBy',
			'value' => CHtml::encode($model->editorUsername)
		),
		array(
			'name' => 'lastNotesEditor',
			'value' => CHtml::encode($model->notesEditorUsername)
		)
	),
)); ?>