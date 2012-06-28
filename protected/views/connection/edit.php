<?php
$this->breadcrumbs=array(
	'Connections'=>array('manage'),
	"$model->IPAddress: $model->username" => array('view','id'=> $model->connectionID),
	'Edit',
);

$this->menu=array(
	array('label'=>'Back to Manage Connection', 'url'=>array('manage')),
);
?>

<h1>Update Connection <?php echo $model->connectionID; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>