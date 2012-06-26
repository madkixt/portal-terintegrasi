<?php
$this->breadcrumbs=array(
	'Connections'=>array('index'),
	$model->connectionID=>array('view','id'=>$model->connectionID),
	'Update',
);

$this->menu=array(
	array('label'=>'List Connection', 'url'=>array('index')),
	array('label'=>'Create Connection', 'url'=>array('create')),
	array('label'=>'View Connection', 'url'=>array('view', 'id'=>$model->connectionID)),
	array('label'=>'Manage Connection', 'url'=>array('admin')),
);
?>

<h1>Update Connection <?php echo $model->connectionID; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>