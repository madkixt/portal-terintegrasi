<?php
if (!$this->isUser())
	$this->breadcrumbs=array(
		'Users'=>array('manage'),
		$model->username,
	);

$qurl = array('query/manage');
$curl = array('connection/manage');
if ($model->role != User::ROLE_ADMINISTRATOR) {
	$qurl['id'] = $curl['id'] = $model->userID;
}

$this->menu=array(
	array('label' => 'Add User', 'url'=>array('add'), 'visible' => $this->isAdmin()),
	array('label' => 'Edit User', 'url'=>array('edit', 'id'=>$model->userID), 'visible' => $model->editClickable),
	array('label' => 'Queries', 'url' => $qurl),
	array('label' => 'Connections', 'url' => $curl),
	array('label' => 'Assign Query', 'url' => array('assignQuery', 'id' => $model->userID), 'visible' => $model->assignable),
	array('label' => 'Assign Connection', 'url' => array('assignConnection', 'id' => $model->userID), 'visible' => $model->assignable),
	array('label' => 'Delete User', 'url'=>'#', 'visible' => $model->deleteClickable, 'linkOptions'=>array('submit'=>array('delete','id'=>$model->userID),'confirm'=>'Are you sure you want to delete this item?')),
	array('label' => 'Back to Manage User', 'url'=>array('manage'), 'visible' => $this->isAdmin())
);
?>

<h1>View User <?php echo $model->username; ?></h1>


<?php
$role = User::getUserRoles();
$role = $role[$model->role];

$this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
			'name' => 'userID',
			'visible' => !$this->isUser(),
		),
		array(
			'value'=> $role,
			'name'=>'role',
			'visible' => !$this->isUser(),
		),
		'username',
		'description',
		'creationDate',
		'modifiedDate',
		array(
			'name' => 'createdBy',
			'type' => 'raw',
			// 'visible' => !$this->isUser(),
			'value' => $this->isAdmin() ? CHtml::link($model->creatorUsername, array('user/view', 'id' => $model->createdBy)) : CHtml::encode($model->creatorUsername)
		),
		array(
			'name' => 'lastModifiedBy',
			'type' => 'raw',
			// 'visible' => !$this->isUser(),
			'value' => $this->isAdmin() ? CHtml::link($model->editorUsername, array('user/view', 'id' => $model->lastModifiedBy)) : CHtml::encode($model->editorUsername)
		),
	),
	'nullDisplay' => ''
)); ?>
