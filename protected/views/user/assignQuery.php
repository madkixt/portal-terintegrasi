<?php
$this->breadcrumbs=array(
	'Users'=>array('manage'),
	$user->username => array('view', 'id' => $user->userID),
	'Assign Query',
);

$this->menu=array(
	array('label'=>'Add User', 'url'=>array('add'), 'visible' => Yii::app()->user->getState('admin')),
	array('label'=>'View User', 'url'=>array('view', 'id'=> $user->userID)),
	array('label'=>'Edit User', 'url'=>array('edit', 'id'=> $user->userID), 'visible' => $user->editClickable),
	array('label' => 'Assign Connection', 'url' => array('assignConnection', 'id' => $user->userID), 'visible' => $user->assignable),
	array('label'=>'Delete User', 'url'=>'#', 'visible' => $user->deleteClickable, 'linkOptions'=>array('submit'=>array('delete','id'=>$user->userID),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Back to Manage User', 'url'=>array('manage'), 'visible' => Yii::app()->user->getState('admin')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('user-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Assign Query to <?php echo $user->username ?></h1>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'query-form',
	'enableAjaxValidation'=>false,
)); 
?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>
	
	
	
	<div class="row">
		<?php echo $form->labelEx($model, 'queryID'); ?>
		<?php echo $form->dropDownList($model, 'queryID', CHtml::listData($user->assignableQueries, 'queryID', 'judulQuery')); ?>
		<?php echo $form->error($model,'queryID'); ?>
	</div>
	
	<br />
	
	<div class="row buttons">
		<?php echo CHtml::submitButton('Assign'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->