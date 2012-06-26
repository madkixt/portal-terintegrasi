<?php
$this->breadcrumbs=array(
	'Queries'=>array('index'),
	$model->queryID,
);

$this->menu=array(
	array('label'=>'List Query', 'url'=>array('index')),
	array('label'=>'Create Query', 'url'=>array('create')),
	array('label'=>'Update Query', 'url'=>array('update', 'id'=>$model->queryID)),
	array('label'=>'Delete Query', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->queryID),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Query', 'url'=>array('admin')),
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
		'createdBy',
		'lastModifiedBy',
		'lastNotesEditor',
	),
)); ?>
