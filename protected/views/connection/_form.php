<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'connection-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'IPAddress'); ?>
		<?php echo $form->textField($model,'IPAddress',array('size'=>15,'maxlength'=>15)); ?>
		<?php echo $form->error($model,'IPAddress'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'dbms'); ?>
		<?php echo $form->dropDownList($model, 'dbms', Connection::getDbmsOptions()); ?>
		<?php echo $form->error($model,'dbms'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'username'); ?>
		<?php echo $form->textField($model,'username',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'username'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'password'); ?>
		<?php echo $form->textField($model,'password',array('size'=>20,'maxlength'=>32)); ?>
		<?php echo $form->error($model,'password'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'serverName'); ?>
		<?php echo $form->textField($model,'serverName',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'serverName'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textArea($model,'description',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->