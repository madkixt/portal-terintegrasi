<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'query-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

<?php
Yii::app()->clientScript->registerScript('adder', "
$('#addStatement').bind('click', function() {
	var extraDiv = $('#statement');
	var inputs = $('div[id^=\"single\"] textarea');
	
	extraDiv.html('');
	
	// Add the first statement
	extraDiv.html(extraDiv.html() + '<div id=\'single1\'><strong>Statement 1</strong><br /><textarea rows=\'8\' cols=\'40\' name=\'statement[1]\'>' + inputs[0].value + '</textarea></div>');
	
	var i = 2;
	// Add the remaining statements
	for (; i <= inputs.length; i++) {
		extraDiv.html(extraDiv.html() + '<div id=\'single' + i + '\'><strong>Statement ' + i + '</strong><br /><textarea name=\'statement[' + i + ']\' rows=\'8\' cols=\'40\'>' + inputs[i-1].value + '</textarea><br /><a href=\'#\' id=\'remove' + i + '\'>Remove</a></div>');
	}
	
	// Add the new statement
	extraDiv.html(extraDiv.html() + '<div id=\'single' + i + '\'><strong>Statement ' + i + '</strong><br /><textarea name=\'statement[' + i + ']\' rows=\'8\' cols=\'40\'></textarea><br /><a href=\'#\' id=\'remove' + i + '\'>Remove</a></div>');
	
	var inputs = $('div[id^=\"single\"] textarea');
	var i = 2;
	for (; i <= inputs.length; i++) {
		$('#remove' + i).bind('click', function() {
			var j = Number($(this).attr('id').substr($(this).attr('id').length - 1, 1));
			var num = $('div[id^=\"single\"]').length;
			for (; j < num; j++) {
				$('#single' + j + ' textarea').val($('#single' + (j + 1) + ' textarea').val());
			}
			$('#single' + num).remove();
		});
	}
})
");	

?>

	<?php echo CHtml::link('Add statement', '#', array('id' => 'addStatement')); ?>
	
	<div class="row">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'title'); ?>
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
		<div id='single<?php echo $idx ?>'>
			<strong>Statement <?php echo $idx ?></strong><br />
			<textarea name='statement[<?php echo $idx ?>]' rows='8' cols='40'><?php echo $statement ?></textarea><br />
			<?php
			if ($idx > 1) {
				echo CHtml::link('Remove', '#', array('id' => 'remove'.$idx));
			}
			?>
			<br />
		</div>
		<?php
	}
	
Yii::app()->clientScript->registerScript('remover', "
	$('a[id^=\"remove\"]').bind('click', function() {
		var j = Number($(this).attr('id').substr($(this).attr('id').length - 1, 1));
		var num = $('div[id^=\"single\"]').length;
		for (; j < num; j++) {
			$('#single' + j + ' textarea').val($('#single' + (j + 1) + ' textarea').val());
		}
		$('#single' + num).remove();
	});
");
	
} else { ?>
		<div id='single1'>
			<strong>Statement 1</strong><br />
			<textarea name='statement[1]' rows='8' cols='40'></textarea><br />
		</div>
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
	
 <script type="text/javascript">
	$(document).ready(function(){
		$('.form').submit(function(){
			$('*').removeClass('error');
			var error = 0;
			for (var i=1; i<=  $('div[id^=\"single\"]').length; i++) 		  	{
				var x = $('textarea[name="statement[' + i + ']"]');
				x = x[0].value;
				if (x == "") {
					$('textarea[name="statement[' + i + ']"]').addClass('error');
					error = 1;
				}
			}
			if (error == 1) 
			{ 
				return false;
			}
			else  {return true;}
		}); 
	});
</script>
<style type="text/css"><!--
.error { border:2px solid red; }
--></style>

<?php $this->endWidget(); ?>

</div><!-- form -->