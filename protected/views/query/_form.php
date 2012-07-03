<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'query-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

<?php
$curStatement = 0;

Yii::app()->clientScript->registerScript('adder', "
$('#addStatement').bind('click', function() {
	alert('c');
	var extraDiv = $('#statement');
	var inputs = $('#statement textarea');
	
	extraDiv.html('');
	var i = 0;
	
	for (; i < inputs.length; i++) {
		extraDiv.html(extraDiv.html() + 
			'<tr><td>Statement ' +	 (i + 1) + '</td><td><textarea name=\'statement[' + i + ']\' rows=\'8\' cols=\'20\'>' + inputs[i].value + '</textarea></td></tr>'
		);
	}
	
	extraDiv.html(extraDiv.html() + 
		'<tr><td>Statement ' + (i + 1) + '</td><td><textarea name=\'statement[' + i + ']\' rows=\'8\' cols=\'20\'>haha</textarea><br /></td></tr>'
	);
})
"); ?>

	<?php echo CHtml::link('Add statement','#',array('id'=>'addStatement')); ?>
	<table id='statement'></table>
	
	<div class="row">
		<?php echo $form->labelEx($model,'judulQuery'); ?>
		<?php echo $form->textField($model,'judulQuery',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'judulQuery'); ?>
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