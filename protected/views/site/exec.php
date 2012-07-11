
<?php
$this->pageTitle=Yii::app()->name . ' - Exec';
?>
<h1>Execution</h1>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'exec-form',
	'enableAjaxValidation' => true,
)); ?>
	<p class="note">Fields with <span class="required">*</span> are required.</p>
	
	<div class="row">
		<?php echo $form->labelEx($model,'connection'); ?>
		<?php echo $form->dropDownList($model,'connection', $model->getConnection()); ?>
		<?php echo $form->error($model,'connection'); ?>
	</div>
	
	
	<div class="row">
		<?php echo $form->labelEx($model,'queryID'); ?>
		<?php echo $form->dropDownList($model, 'queryID', $model->getJudul(), 
		array(
			'empty'=>'Pilih Judul',
			'ajax' => array(
				'type'=>'POST',
				'url'=> CController::createUrl('dinamik'),
				'data'=>'js:"queryID="+jQuery(this).val()',
				'update'=>'#campur',
				'onchange'=>'javascript: clearTextArea();'
			))
						
			); ?>
		<?php echo $form->error($model,'queryID'); ?>
	</div>
	
<div id = "campur">	
</div>
	
	<div class="row buttons">
		<?php echo CHtml::submitButton('Exec'); ?>
	</div>


  <script type="text/javascript">
	 $(document).ready(function(){
	   $('.form').submit(function(){
	   	if  ($('#enableediting').is(':checked')) {
			return true;
		}
		else {
			$('*').removeClass('error');
		   var error = 0;
			for(var i =1; i <= $('#campur textarea').length; i++) {
				if  ($('#checkbox' + i).is(':checked')) {
					var arr = parseVariable($('#statement' + i).text());
					for (varname in arr) {
						var x = $('input[name="vari'+i+ varname+'"]');
						arr[varname] = x[0].value;
						if (arr[varname] == "") {	
							 $('input[name="vari'+i+ varname+'"]').addClass('error');
							error = 1;	
						}
					}
				} 
			}
		}
		if (error == 1) 
		{ alert('Please fill all fields with red border');
		return false;
		}
		}); 
	});
</script>

<style type="text/css"><!--
.error { border:2px solid red; }
--></style>

	
	
<?php $this->endWidget(); ?>	

</div><!-- form -->