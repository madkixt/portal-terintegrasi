<?php
$this->breadcrumbs=array(
	'Queries'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Query', 'url'=>array('index')),
	array('label'=>'Manage Query', 'url'=>array('admin')),
);
?>

<h1>Create Query</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>