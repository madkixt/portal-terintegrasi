<?php
$this->breadcrumbs=array(
	'Users'=>array('manage'),
	$model->username => array('view','id'=>$model->userID),
	'Edit',
);

$this->menu=array(
	array('label'=>'Add User', 'url'=>array('add'), 'visible' => Yii::app()->user->getState('admin')),
	array('label'=>'View User', 'url'=>array('view', 'id'=>$model->userID)),
	array('label' => 'Assign Query', 'url' => array('assignQuery', 'id' => $model->userID), 'visible' => $model->assignable),
	array('label' => 'Assign Connection', 'url' => array('assignConnection', 'id' => $model->userID), 'visible' => $model->assignable),
	array('label'=>'Delete User', 'url'=>'#', 'visible' => $model->deleteClickable, 'linkOptions'=>array('submit'=>array('delete','id'=>$model->userID),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Back to Manage User', 'url'=>array('manage'), 'visible' => Yii::app()->user->getState('admin')),
);
?>

<h1>Edit User <?php echo $model->username; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>