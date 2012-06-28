<?php
$this->breadcrumbs=array(
	'Queries'=>array('manage'),
	$model->queryID,
);

$this->menu=array(
	array('label'=>'Back to Manage Query', 'url'=>array('manage')),
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
