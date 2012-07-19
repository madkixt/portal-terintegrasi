<?php
$this->breadcrumbs=array(
	'Queries'=>array('manage'),
	$model->title,
);

$this->menu=array(
	array('label' => 'Use Query', 'url' => array('site/exec', 'id' => $model->queryID)),
	array('label' => 'Add Query', 'url'=>array('add'), 'visible' => $this->isAdmin()),
	array('label' => 'Edit Query', 'url'=>array('edit', 'id'=>$model->queryID), 'visible' => $this->isAdmin()),
	array('label' => 'Assign Query', 'url'=>array('assign', 'id' => $model->queryID), 'visible' => $this->isAdmin()),
	array('label' => 'Delete Query', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->queryID),'confirm'=>'Are you sure you want to delete this item?'), 'visible' => $this->isAdmin()),
	array('label' => 'Back to Manage Query', 'url'=>array('manage'), 'visible' => !$this->isUser()),
);

?>

<h1>View Query <?php echo $model->title; ?></h1>

<?php if (Yii::app()->user->hasFlash('success')) { ?>
<div class="flash-success">
	<em><?php echo Yii::app()->user->getFlash('success'); ?></em>
</div>
<?php } ?>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
			'name' => 'queryID',
			'visible' => !$this->isUser(),
		),
		'title',
		array(
			'name' => 'databaseName',
			'visible' => !$this->isUser()
		),
		'notes',
		'creationDate',
		'modifiedDate',
		'notesModifiedDate',
		array(
			'name' => 'createdBy',
			'type' => 'raw',
			'visible' => !$this->isUser(),
			'value' => $this->isAdmin() ? CHtml::link($model->creatorUsername, array('user/view', 'id' => $model->createdBy)) : CHtml::encode($model->creatorUsername)
		),
		array(
			'name' => 'lastModifiedBy',
			'type' => 'raw',
			'visible' => !$this->isUser(),
			'value' => $this->isAdmin() ? CHtml::link($model->editorUsername, array('user/view', 'id' => $model->lastModifiedBy)) : CHtml::encode($model->editorUsername)
		),
		array(
			'name' => 'lastNotesEditor',
			'type' => 'raw',
			'visible' => !$this->isUser(),
			'value' => $this->isAdmin() ? CHtml::link($model->notesEditorUsername, array('user/view', 'id' => $model->lastNotesEditor)) : CHtml::encode($model->notesEditorUsername)
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
		array(
			'name' => 'queryStatement',
			'visible' => !$this->isUser()
		),
		'notes'
	)
));
?>