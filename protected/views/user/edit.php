<?php
$this->breadcrumbs=array(
	'Users'=>array('manage'),
	$model->userID=>array('view','id'=>$model->userID),
	'Edit',
);

$this->menu=array(
	array('label'=>'Back to Manage User', 'url'=>array('manage')),
);
?>

<h1>Update User <?php echo $model->userID; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>