
<?php
$this->pageTitle=Yii::app()->name . ' - Exec';
?>
<h1>Execution</h1>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'exec-form',
	'enableAjaxValidation' => true,
	'action' => CHtml::normalizeUrl(array('result'))
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
	
<style type="text/css"><!--
.error { border:2px solid red; }
--></style>

<?php $this->endWidget(); ?>	

</div><!-- form -->

<script type="text/javascript">
	$(document).ready(function(){
		var x=document.getElementById("connection");
		var option=document.createElement("option");
		option.text="Buat Koneksi Baru";
		option.value = "koneksibaru";
		try {
			x.add(option,x.options[null]);
		}
		catch (e) {
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
			
			if (error == 1) {
				alert('Please fill all fields with red border');
				return false;
			}
		});
	});

	function coba(chk) {
		var id = chk.id.substr(chk.id.length-1);
		if (chk.checked) {
			splitQuery(id);
		} else {
			$('#vars' + id).html('');
		}
		setText();
	}

	function setText() {
		var txt = '';
		for(var i = 1; i <= $('#campur textarea').length; i++) {
			if  ($('#checkbox' + i).is(':checked')) {
				txt += assignVariable($('textarea[name="statement' + i + '"]').text(), i) + ";\n";
			}
		}
		
		$('#isiquery').text(txt);
	}
	
	function splitQuery(i) {
		if  ($('#checkbox' + i).is(':checked')) {
			str = $('textarea[name="statement' + i + '"]').text();
			var variables = parseVariable(str);
			for (varname in variables) {
				$('#vars' + i).html($('#vars' + i).html() + "<tr><td width='30px'>"+varname + "</td><td><input name='vari"+i+ varname + "' class= 'required' type='text' value='" + variables[varname] + "' onchange='setText()' /></td></tr>");
			}
		}
	}
		
	function parseVariable(text) {
		var ret = new Array();
		var txt = text;
	
		var idxQ = -1;
		var oldIdxQ = 0;
		while ((idxQ = txt.indexOf('?', idxQ + 1)) != -1) {
			var numQuotes = txt.substring(oldIdxQ, idxQ).split("'").length - 1;
			if ((numQuotes % 2 === 1))
				continue;
			
			oldIdxQ = idxQ;
			var terminIdx = txt.substr(idxQ + 1).search(/\W/);
			var varname = '';
			var varval = '';
			if (terminIdx != -1) {
				var sub = txt.substr(idxQ + 1);
				varname = sub.substr(0, terminIdx);
				if (sub.charAt(terminIdx) === '{') {
					var curlIdx = sub.indexOf('}', terminIdx);
					if (curlIdx != -1)
						varval = sub.substring(terminIdx + 1, curlIdx);
				}
			}
			else
				varname = txt.substr(idxQ + 1);
			varval = varval.replace(/\'/g, "woi");
			ret[varname] = varval;
		}
		
		return ret;
	}

	function printVariable(arr) {
		var str = '';
		for (i in arr)
			str += i + ': ' + arr[i] + "\n";
		return str;
	}
	
	function assignVariable(text, i) {
		var txt = text;
	
		var idxQ = -1;
		var oldIdxQ = 0;
		while ((idxQ = txt.indexOf('?', idxQ + 1)) != -1) {
			var numQuotes = txt.substring(oldIdxQ, idxQ).split("'").length - 1;
			if ((numQuotes % 2 === 1))
				continue;
			
			oldIdxQ = idxQ;
			var terminIdx = txt.substr(idxQ + 1).search(/\W/);
			var varname = '';
			if (terminIdx != -1) {
				var sub = txt.substr(idxQ + 1);
				varname = sub.substr(0, terminIdx);
				var varval = $('input[name="vari' + i + varname + '"]').val();
				
				if (varval !== '')
					varval = "'" + varval + "'";
				else
					varval = "?" + varname;
					
				if (sub.charAt(terminIdx) === '{') {
					var curlIdx = sub.indexOf('}', terminIdx);
					if (curlIdx != -1) {
						txt = txt.substring(0, idxQ) + varval + sub.substring(curlIdx + 1);
					} else {
						txt = txt.substring(0, idxQ) + varval + sub.substring(terminIdx);
					}
				} else {
					txt = txt.substring(0, idxQ) + varval + sub.substring(terminIdx);
				}
			}
			else {
				varname = txt.substring(idxQ + 1);
				var varval = $('input[name="vari' + i + varname + '"]').val();
				
				if (varval !== '')
					varval = "'" + varval + "'";
				else
					varval = "?" + varname;
					
				txt = txt.substring(0, idxQ) + varval;
			}
		}
		
		return txt;
	}
	
	function changeedit() {
		if  ($('#enableediting').is(':checked')) {
			$('#isiquery').attr('readonly',false);
			$('input[name^="vari"]').attr('readonly', true);
		}
		else
		{
			$('#isiquery').attr('readonly',true);
			$('input[name^="vari"]').attr('readonly', false);
		}
	}
	
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