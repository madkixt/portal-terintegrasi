<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'query-form',
	'enableAjaxValidation'=>false,
));

Yii::app()->clientScript->registerScript('adder', <<<EOD
$('#addStatement').bind('click', function() {
	var extraDiv = $('#statement');
	var inputs = $('textarea[name^="statement"]');
	var notes = $('textarea[name^="notes"]');
	
	var html = '<table><tr id="single1"><td><strong>Statement 1</strong><br /><textarea rows=\'8\' cols=\'40\' name=\'statement[1]\'>' + inputs[0].value + '</textarea></td>' +
		'<td><strong>Notes 1</strong><br /><textarea rows="8" cols="40" name="notes[1]">' + notes[0].value + '</textarea></td></tr>';
	
	var i = 2;
	// Add the remaining statements
	for (; i <= inputs.length; i++) {
		html += '<tr id="single' + i + '"><td><strong>Statement ' + i + '</strong><br /><textarea name=\'statement[' + i + ']\' rows=\'8\' cols=\'40\'>' + inputs[i-1].value + '</textarea><br /><a href=\'#\' id=\'remove' + i + '\'>Remove</a></td>' +
			'<td><textarea rows="8" cols="40" name="notes[' + i + ']">' + notes[i-1].value + '</textarea></td></tr>';
	}
	
	// Add the new statement
	html += '<tr id="single' + i + '"><td><strong>Statement ' + i + '</strong><br /><textarea name=\'statement[' + i + ']\' rows=\'8\' cols=\'40\'></textarea><br /><a href=\'#\' id=\'remove' + i + '\'>Remove</a></td>' +
		'<td><textarea rows="8" cols="40" name="notes[' + i + ']"></textarea></td></tr>';
	
	extraDiv.html(html);
	
	var inputs = $('tr[id^="single"]');
	var i = 2;
	for (; i <= inputs.length; i++) {
		$('#remove' + i).bind('click', function() {
			var j = Number($(this).attr('id').substr($(this).attr('id').length - 1, 1));
			var num = $('tr[id^="single"]').length;
			for (; j < num; j++) {
				$('textarea[name="statement[' + j + ']"]').val($('textarea[name="statement[' + (j + 1) + ']"]').first().val());
				$('textarea[name="notes[' + j + ']"]').val($('textarea[name="notes[' + (j + 1) + ']"]').first().val());
			}
			$('#single' + num).remove();
		});
	}
});
EOD
, CClientScript::POS_READY);

?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php echo CHtml::link('Add statement', '#', array('id' => 'addStatement')); ?>
	
	<div class="row">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>50,'maxlength'=>70)); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'databaseName'); ?>
		<?php echo $form->textField($model,'databaseName',array('size'=>30,'maxlength'=>30)); ?>
		<?php echo $form->error($model,'databaseName'); ?>
	</div>

	<div id='statement'>
	
<?php
if ($model->queryID != null) {
	$this->widget('StatementTable', array('queryID' => $model->queryID));
} elseif ($statements != null) {
	$this->widget('StatementTable', array('statements' => $statements, 'notes' => null));
} else { ?>
	<table>
		<tr>
			<td>
				<strong>Statement 1</strong><br />
				<textarea name='statement[1]' rows='8' cols='40'></textarea>
			</td>
			<td>
				<strong>Notes 1</strong><br />
				<textarea name='notes[1]' rows='8' cols='40'></textarea>
			</td>
	</table>
		<?php
} ?>

	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'notes'); ?>
		<?php echo $form->textArea($model,'notes',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'notes'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Add Query' : 'Save'); ?>
	</div>
	
<style type="text/css"><!--

--></style>

<?php $this->endWidget(); ?>

</div><!-- form -->

<script type="text/javascript">
$(document).ready(function() {
	$('.form').submit(function() {
		var error = 0;
		for (var i = 1; i <= $('tr[id^="single"]').length; i++) {
			var x = $('textarea[name="statement[' + i + ']"]').first().val();
			if (x == "") {
				$('textarea[name="statement[' + i + ']"]').addClass('error');
				error = 1;
			} else {
				$('textarea[name="statement[' + i + ']"]').removeClass('error');
			}
		}
		
		if (error == 1) { 
			return false;
		}
		
		return true;
	});
});
</script>