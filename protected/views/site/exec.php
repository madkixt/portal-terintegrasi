<?php
$this->pageTitle=Yii::app()->name . ' - Exec';
$this->breadcrumbs=array(
	'Exec',
);
?>

<h1>Execution</h1>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'exec-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<div class="row">
		<?php echo $form->labelEx($model,'mesin'); ?>
		<?php echo $form->textField($model,'mesin',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'mesin'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'database'); ?>
		<?php echo $form->textField($model,'database',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'database'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'judulQuery'); ?>
			<?php echo $form->dropDownList($model,'judulQuery', $model->getJudulQueryOptions()); ?>
		<?php echo $form->error($model,'judulQuery'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'isiQuery'); ?>
		<?php echo $form->textField($model,'isiQuery'); ?>
		<?php echo $form->error($model,'isiQuery'); ?>
	</div>
	
	<div class="row buttons">
		<?php echo CHtml::submitButton('Exec'); ?>
	</div>

	
<?php $this->endWidget(); ?>	
</div><!-- form -->