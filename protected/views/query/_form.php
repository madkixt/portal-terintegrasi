<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'query-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'judulQuery'); ?>
		<?php echo $form->textField($model,'judulQuery',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'judulQuery'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'isiQuery'); ?>
		<?php echo $form->textArea($model,'isiQuery',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'isiQuery'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'databaseName'); ?>
		<?php echo $form->textField($model,'databaseName',array('size'=>30,'maxlength'=>30)); ?>
		<?php echo $form->error($model,'databaseName'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'notes'); ?>
		<?php echo $form->textArea($model,'notes',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'notes'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Add Query' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->