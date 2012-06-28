<?php
$this->breadcrumbs=array(
	'Queries'=>array('manage'),
	$model->judulQuery => array('view','id'=>$model->queryID),
	'Update',
);

$this->menu=array(
	array('label'=>'Back to Manage Query', 'url'=>array('manage')),
);
?>

<h1>Update Query <?php echo $model->queryID; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>