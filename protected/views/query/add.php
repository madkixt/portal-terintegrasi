<?php
$this->breadcrumbs=array(
	'Queries'=>array('manage'),
	'Add',
);

$this->menu=array(
	array('label'=>'Back to Manage Query', 'url'=>array('manage'))
);
?>

<h1>Create Query</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model, 'statements' => $statements, 'notes' => $notes)); ?>