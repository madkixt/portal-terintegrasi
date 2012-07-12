<?php
$this->breadcrumbs=array(
	'Connections'=>array('manage'),
	$model->name => array('view','id'=> $model->connectionID),
	'Edit',
);

$this->menu=array(
	array('label'=>'Add Connection', 'url'=>array('add')),
	array('label'=>'View Connection', 'url'=>array('view', 'id'=>$model->connectionID)),
	array('label'=>'Delete Connection', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->connectionID),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Back to Manage Connection', 'url'=>array('manage')),
);
?>

<h1>Edit Connection <?php echo $model->name; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>