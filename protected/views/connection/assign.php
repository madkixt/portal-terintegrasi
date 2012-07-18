<?php	
$this->breadcrumbs=array(
	'Users'=>array('manage'),
	$conn->name => array('view', 'id' => $conn->connectionID),
	'Assign Connection',
);

$this->menu=array(
	array('label'=>'Add Connection', 'url'=>array('add'), 'visible' => Yii::app()->user->getState('admin')),
	array('label'=>'View Connection', 'url'=>array('view', 'id'=>$conn->connectionID)),
	array('label'=>'Edit Connection', 'url'=>array('edit', 'id'=>$conn->connectionID), 'visible' => Yii::app()->user->getState('admin')),
	array('label'=>'Delete Connection', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$conn->connectionID),'confirm'=>'Are you sure you want to delete this item?'), 'visible' => Yii::app()->user->getState('admin')),
	array('label'=>'Back to Manage Connection', 'url'=>array('manage')),
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

<?php if (Yii::app()->user->hasFlash('success')) { ?>
<div class="flash-success">
	<em><?php echo Yii::app()->user->getFlash('success'); ?></em>
</div>
<?php } elseif (Yii::app()->user->hasFlash('error')) { ?>
<div class="flash-error">
	<em><?php echo Yii::app()->user->getFlash('error'); ?></em>
</div>
<?php } ?>

<?php
$assign = $conn->assignableUsers;
if (count($assign) === 0) {
?>
<h2>No user is assignable to <?php echo $conn->name; ?></h2>
<?php
	return;
}
?>

<h1>Assign user to <?php echo $conn->name; ?></h1>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'connection-form',
	'enableAjaxValidation'=>false,
)); 
?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>
	
	<div class="row">
		<?php echo $form->labelEx($model, 'userID'); ?>
		<?php echo $form->dropDownList($model, 'userID', CHtml::listData($assign, 'userID', 'username')); ?>
		<?php echo $form->error($model,'userID'); ?>
	</div>
	
	<br />
	
	<div class="row buttons">
		<?php echo CHtml::submitButton('Assign'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->