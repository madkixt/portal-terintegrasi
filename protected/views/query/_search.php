<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'queryID'); ?>
		<?php echo $form->textField($model,'queryID'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'judulQuery'); ?>
		<?php echo $form->textField($model,'judulQuery',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'isiQuery'); ?>
		<?php echo $form->textArea($model,'isiQuery',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'databaseName'); ?>
		<?php echo $form->textField($model,'databaseName',array('size'=>30,'maxlength'=>30)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'notes'); ?>
		<?php echo $form->textArea($model,'notes',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'creationDate'); ?>
		<?php echo $form->textField($model,'creationDate'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'modifiedDate'); ?>
		<?php echo $form->textField($model,'modifiedDate'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'notesModifiedDate'); ?>
		<?php echo $form->textField($model,'notesModifiedDate'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'createdBy'); ?>
		<?php echo $form->textField($model,'createdBy'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'lastModifiedBy'); ?>
		<?php echo $form->textField($model,'lastModifiedBy'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'lastNotesEditor'); ?>
		<?php echo $form->textField($model,'lastNotesEditor'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->