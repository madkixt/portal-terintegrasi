<?php
$this->breadcrumbs=array(
	'Queries'=>array('index'),
	$model->queryID=>array('view','id'=>$model->queryID),
	'Update',
);

$this->menu=array(
	array('label'=>'List Query', 'url'=>array('index')),
	array('label'=>'Create Query', 'url'=>array('create')),
	array('label'=>'View Query', 'url'=>array('view', 'id'=>$model->queryID)),
	array('label'=>'Manage Query', 'url'=>array('admin')),
);
?>

<h1>Update Query <?php echo $model->queryID; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>