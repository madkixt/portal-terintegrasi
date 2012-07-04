<?php
echo Yii::app()->user->getFlash('tes');
echo Yii::app()->user->getFlash('tes1');

$this->breadcrumbs=array(
	'Queries'=>array('manage'),
	$model->judulQuery,
);

if (Yii::app()->user->getState('admin')) {
	$this->menu=array(
		array('label'=>'Add Query', 'url'=>array('add')),
		array('label'=>'Edit Query', 'url'=>array('edit', 'id'=>$model->queryID)),
		array('label'=>'Delete Query', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->queryID),'confirm'=>'Are you sure you want to delete this item?')),
		array('label'=>'Back to Manage Query', 'url'=>array('manage')),	
	);
} else {
	$this->menu=array(
		array('label'=>'Add Query', 'url'=>array('add')),
		array('label'=>'Edit Query', 'url'=>array('edit', 'id'=>$model->queryID)),
		array('label'=>'Back to Manage Query', 'url'=>array('manage')),	
	);
}
?>

<h1>View Query <?php echo $model->judulQuery; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'queryID',
		'judulQuery',
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
	'nullDisplay' => ''
)); 

$this->widget('zii.widgets.grid.CGridView', array(
	'id' => 'statement-grid',
	'dataProvider' => new CActiveDataProvider('Statement', array(
		'criteria' => array(
			'condition' => 'queryID = :queryID',
			'params' => (array(':queryID' => $model->queryID))
		),
		'pagination' => false
	)),
	'columns' => array(
		array(
			'name' => 'queryNum',
			'htmlOptions' => array(
				'width' => '50px'
			)
		),
		'queryStatement'
	)
)); 
?>