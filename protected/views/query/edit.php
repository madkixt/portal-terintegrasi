<?php
$this->breadcrumbs=array(
	'Queries'=>array('manage'),
	$model->judulQuery => array('view','id'=>$model->queryID),
	'Edit',
);

if (Yii::app()->user->getState('admin')) {
	$this->menu=array(
		array('label' => 'Use Query', 'url' => array('site/exec', 'id' => $model->queryID)),
		array('label'=>'Add Query', 'url'=>array('add')),
		array('label'=>'View Query', 'url'=>array('view', 'id'=>$model->queryID)),
		array('label'=>'Delete Query', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->queryID),'confirm'=>'Are you sure you want to delete this item?')),
		array('label'=>'Back to Manage Query', 'url'=>array('manage')),	
	);
} else {
	$this->menu=array(
		array('label'=>'Add Query', 'url'=>array('add')),
		array('label'=>'View Query', 'url'=>array('view', 'id'=>$model->queryID)),
		array('label'=>'Back to Manage Query', 'url'=>array('manage')),	
	);
}
?>

<h1>Edit Query <?php echo $model->judulQuery; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model, 'statements' => $statements)); ?>