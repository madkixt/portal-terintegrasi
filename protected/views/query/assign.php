<?php	
$this->breadcrumbs=array(
	'Users'=>array('manage'),
	$query->title => array('view', 'id' => $query->queryID),
	'Assign Query',
);

if (Yii::app()->user->getState('admin')) {
	$this->menu=array(
		array('label' => 'Use Query', 'url' => array('site/exec', 'id' => $query->queryID)),
		array('label'=>'Add Query', 'url'=>array('add')),
		array('label'=>'View Query', 'url'=>array('view', 'id'=>$query->queryID)),
		array('label'=>'Edit Query', 'url'=>array('edit', 'id'=>$query->queryID)),
		array('label'=>'Delete Query', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$query->queryID),'confirm'=>'Are you sure you want to delete this item?')),
		array('label'=>'Back to Manage Query', 'url'=>array('manage')),	
	);
} else {
	$this->menu=array(
		array('label'=>'Add Query', 'url'=>array('add')),
		array('label'=>'View Query', 'url'=>array('view', 'id'=>$query->queryID)),
		array('label'=>'Edit Query', 'url'=>array('edit', 'id'=>$query->queryID)),
		array('label'=>'Back to Manage Query', 'url'=>array('manage')),	
	);
}

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
<?php } ?>

<?php
$assign = $query->assignableUsers;
if (count($assign) === 0) {
?>
<h2>No user is assignable to <?php echo $query->title; ?></h2>
<?php
	return;
}
?>

<h1>Assign User to <?php echo $query->title; ?></h1>

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