<?php
$this->breadcrumbs=array(
	'Connections'=>array('manage'),
	'Add',
);

$this->menu=array(
	array('label'=>'Back to Manage Connection', 'url'=>array('manage'))
);
?>

<h1>Create Connection</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>