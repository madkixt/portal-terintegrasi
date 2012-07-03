<?php
$this->pageTitle=Yii::app()->name . ' - Exec';
?>

<h1>Execution</h1>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'exec-form',
	'enableAjaxValidation'=>false,
)); ?>

<?php

?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<div class="row">
		<?php echo $form->labelEx($model,'connection'); ?>
		<?php echo $form->dropDownList($model,'connection',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'connection'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'database'); ?>
		<?php echo $form->textField($model,'database',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'database'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'queryID'); ?>
		<?php echo $form->textArea($model, 'queryID', array('cols'=>60,'rows'=>5)); ?>
		<?php echo $form->error($model,'queryID'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'isiQuery'); ?>
		<?php echo $form->textArea($model, 'isiQuery', array('cols'=>60,'rows'=>5)); ?>
		<?php echo $form->error($model,'isiQuery'); ?>
	</div>
	
	<div class="row buttons">
		<?php echo CHtml::submitButton('Exec'); ?>
	</div>

	
<?php $this->endWidget(); ?>	
</div><!-- form -->