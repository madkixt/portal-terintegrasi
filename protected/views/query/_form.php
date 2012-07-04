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
	var extraDiv = $('#statement');
	var inputs = $('#statement textarea');
	
	extraDiv.html('');
	var i = 1;
	
	for (; i <= inputs.length; i++) {
		extraDiv.html(extraDiv.html() + 
			'<strong>Statement ' + i + '</strong><br /><textarea name=\'statement[' + i + ']\' rows=\'8\' cols=\'40\'>' + inputs[i-1].value + '</textarea><br />'
		);
	}
	
	extraDiv.html(extraDiv.html() + 
		'<strong>Statement ' + i + '</strong><br /><textarea name=\'statement[' + i + ']\' rows=\'8\' cols=\'40\'></textarea><br /><br />'
	);
})
"); ?>

	<?php echo CHtml::link('Add statement','#',array('id'=>'addStatement')); ?>
	
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

	<div id='statement'>
	
<?php
if ($statements != null) {
	foreach ($statements as $idx => $statement) {
		?>
		<strong>Statement <?php echo $idx ?></strong><br />
		<textarea name='statement[<?php echo $idx ?>]' rows='8' cols='40'><?php echo $statement ?></textarea><br />
		<?php
	}
} else { ?>
		<strong>Statement 1</strong><br />
		<textarea name='statement[1]' rows='8' cols='40'></textarea>
<?php } ?>

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