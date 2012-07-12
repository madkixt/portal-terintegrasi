
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
	<table>
		<tr>
			<td>
				<div id = "koneksi">
					<?php echo $form->labelEx($model,'connection'); ?>
					<?php echo $form->dropDownList($model,'connection', $model->getConnection(), array ('id'=>'connection', 
					'onchange'=>'javascript: TambahTextField();'
					)); ?>
					<?php echo $form->error($model,'connection'); ?>
				</div> 
			</td>
			<td>
				<br />
				<div id = "koneksibaru">	
				</div> 
			</td> 
		</tr>
	</table>
	</div>
	
	<div class="row">
		<table>
			<tr>
			<td>
		<?php echo $form->labelEx($model,'queryID'); ?>
		<?php echo $form->dropDownList($model, 'queryID', $model->getJudul(), 
		array(
			'empty'=>'Pilih Judul',
//			'options'=>array($id=>array('selected'=>'selected')),
			'ajax' => array(
				'type'=>'POST',
				'url'=> CController::createUrl('dinamik'),
				'data'=>'js:"queryID="+jQuery(this).val()',
				'update'=>'#campur',
			//	'onchange'=>'javascript: clearTextArea();'
			))
						
			); ?>
		<?php echo $form->error($model,'queryID'); ?>
		</td>
			</tr>
		</table>
	</div>
	
<div id = "campur">	
</div>


	
	<div class="row buttons">
		<?php echo CHtml::submitButton('Exec'); ?>
	</div>
<script type="text/javascript">
	 function TambahTextField() {
		$('#koneksibaru').empty();
		var selected =  $("#connection option:selected").text();
		if (selected == 'Buat Koneksi Baru') {
			$('#koneksibaru').html($('#koneksibaru').html() + "IP : <input name='IP' type='text' size ='10' />      ");
			$('#koneksibaru').html($('#koneksibaru').html() + "     Username : <input name='username' size = '10' type='text'/>");
			$('#koneksibaru').html($('#koneksibaru').html() + "     Password : <input name='password' size = '10' type='password'/>");
			}
	}
	
	
</script>

  <script type="text/javascript">
	$(document).ready(function(){
	var x=document.getElementById("connection");
	var option=document.createElement("option");
	option.text="Buat Koneksi Baru";
	option.value = "koneksibaru";
		try
		{
			x.add(option,x.options[null]);
		}
		catch (e)
		{
		x.add(option,null);
		}

	 
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