<?php
$this->breadcrumbs=array(
	'Users'=>array('manage'),
	$model->username => array('view','id'=>$model->userID),
	'Edit',
);

$this->menu=array(
	array('label'=>'Add User', 'url'=>array('add')),
	array('label'=>'View User', 'url'=>array('view', 'id'=>$model->userID)),
	array('label' => 'Assign Queries', 'url' => array('assignQuery')),
	array('label' => 'Assign Connections', 'url' => array('assignConnection')),
	array('label'=>'Delete User', 'url'=>'#', 'visible' => Yii::app()->user->getState('admin') && !$model->admin, 'linkOptions'=>array('submit'=>array('delete','id'=>$model->userID),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Back to Manage User', 'url'=>array('manage')),
);
?>

<h1>Edit User <?php echo $model->username; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>