<?php
$this->breadcrumbs=array(
	'Users'=>array('manage'),
	'Add',
);

$this->menu=array(
	array('label'=>'Back to Manage User', 'url'=>array('manage'))
);
?>

<h1>Create User</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>