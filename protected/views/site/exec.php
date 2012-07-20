<link href="datepicker/jquery.ui.all.css"  rel="stylesheet" type="text/css" />
<script type="text/javascript" src="datepicker/jquery.ui.core.js"></script>
<script type="text/javascript" src="datepicker/jquery.ui.datepicker.js"></script>
<script type="text/javascript">
	function split() {
		var i = document.getElementsByName('checkbox').length;
		for (var j = 1; j <= i; j++)
			splitQuery(j);
	}
	
	function shownote() {
		if ($('#textnotes').css('opacity') == 0) {
			$('#shownote').text('Hide Notes');
			$('#textnotes').animate({
				opacity: 1.0
			}, 1000);
		} else {
			$('#shownote').text('Show Notes');
			$('#textnotes').animate({
				opacity: 0
			}, 1000);
		}
	}
	
	$(document).ready(function() {
		$('.form').submit(function(){
			var error = 0;
			// Isiquery
			var z = ($('textarea[name="isiquery1"]').val());
			if (z == "") {
				$('textarea[name="isiquery1"]').addClass('error');
				error = 3;
			} else {
				$('textarea[name="isiquery1"]').removeClass('error');
			}
			
			// Connection
			var selected =  $("#connection option:selected");
			selected = selected[0].value;
			if (selected == "") {
				$('#connection').addClass('error');
				if (error == 3) 
				{
					error =2 ;
				} else 
				{
					error = 1;
				}
			} else if (selected == "other") {
				var IP1 = $('input[name="IP"]');
				IP1 = IP1[0].value;
				if (IP1 == "") {
					$('input[name="IP"]').addClass('error');
					if (error == 3) {
						error = 2;
					}
					else {
						error = 1;
					}
				}
				else {
					$('#connection').removeClass('error');
					$('input[name="IP"]').removeClass('error');
				}
			} else {
				$('#connection').removeClass('error');
			}
			
			// Variable
			if  (!$('#enableediting').is(':checked')) {
				for (var i =1; i <= $('#campur textarea').length; i++) {
					if  ($('#checkbox' + i).is(':checked')) {
						var arr = parseVariable($('#statement' + i).text());
						for (varname in arr) {
							if (varname.substr(varname.length - 2, 2) == ':d') {
								varname = varname.substr(0, varname.length - 2);
							}
						
							var x = $('input[name="vari'+i+ varname+'"]');
							x = x[0].value;
							if (x == "") {	
								$('input[name="vari'+i+ varname+'"]').addClass('error');
								if (error == 3)
								{
									error =2;
								}
								else {
									error =1;
								}
							} else {
								$('input[name="vari'+i+ varname+'"]').removeClass('error');
							}
						}
					} 
				}
			}
			
			if (error == 1) {
				alert('Please fill all fields with red border');
				return false;
			}
			else if (error ==2) {
				alert('Query must not be empty and fill all fields with red border');
				return false;
			}
			else if (error ==3) {
				alert('Query must not be empty');
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
		for (var i = 1; i <= $('#campur textarea').length; i++) {
			if  ($('#checkbox' + i).is(':checked')) {
				txt += assignVariable($('textarea[name="statement' + i + '"]').text(), i) + ";\n";
			}
		}
		
		$('#isiquery').val(txt);
	}
	
	
	
	function splitQuery(i) {
		if  ($('#checkbox' + i).is(':checked')) {
			var str = $('textarea[name="statement' + i + '"]').text();
			var variables = parseVariable(str);
			for (varname in variables) {
				if (varname.substr(varname.length - 2, 2) == ':d') {
					varname = varname.substr(0, varname.length - 2);
					$('#vars' + i).html($('#vars' + i).html() + "<tr><td width='40px' style='max-width: 40px'>" + varname + "</td><td width='250px' style='max-width: 125px'><input id='vari"+i +varname+"' name='vari"+i+ varname + "' class= 'required' size= '15' type='text' value='" + variables[varname + ":d"] + "' onchange='setText()' /></td></tr>");
					
					$('#vari' + i +varname).datepicker({dateFormat: 'yy-mm-dd'});
				}
				else
					$('#vars' + i).html($('#vars' + i).html() + "<tr><td width='40px' style='max-width: 40px'>"+varname + "</td><td width='250px' style='max-width: 125px'><input name='vari"+i+ varname + "' class= 'required' size= '15' type='text' value='" + variables[varname] + "' onchange='setText()' /></td></tr>");
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
					if (curlIdx != -1) {
						varval = sub.substring(terminIdx + 1, curlIdx);
						if (sub.charAt(curlIdx + 1) == 'd') {
							varname += ':d';
						}
					}
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
						if (sub.charAt(curlIdx + 1) == 'd')
							txt = txt.substring(0, idxQ) + varval + sub.substring(curlIdx + 2);
						else
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
		
		var selected =  $("#connection option:selected").val();
		if (selected === 'other') {
			$('#koneksibaru').html($('#koneksibaru').html() + "IP <input name='IP' type='text' size ='10' /> &nbsp; &nbsp; &nbsp; ");
			$('#koneksibaru').html($('#koneksibaru').html() + "Username <input name='username' size = '10' type='text'/> &nbsp; &nbsp; &nbsp; ");
			$('#koneksibaru').html($('#koneksibaru').html() + "Password <input name='password' size = '10' type='password'/> &nbsp; &nbsp; &nbsp; ");
			$('#koneksibaru').html($('#koneksibaru').html() + "DBMS " +
			"<select name='dbms'>" +
				"<option value='0'>Microsoft SQL Server</option>" +
				"<option value='1'>MySQL</option>" +
			"</select>");
		}
		
	}
</script>

<?php
	$this->pageTitle=Yii::app()->name . ' - Exec';
?>
<h1>Execution</h1>


	<link rel="stylesheet" type="text/css" href="/portal/assets/44e798dc/jui/css/base/jquery-ui.css" />
<link rel="stylesheet" type="text/css" href="/portal/assets/73c60228/gridview/styles.css" />



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
					<?php echo $form->dropDownList($model,'connection', $model->getConnection(), array (
						'id'=>'connection',
						'empty'=>'Choose connection',						
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
			'empty'=>'Choose query',
			'options'=>array($id => array('selected'=>'selected')),
			'onchange'=> 'javascript: setText();',
			'ajax' => array(
				'type'=>'POST',
				'url'=> CController::createUrl('dinamik'),
				'data'=>'js:"queryID="+jQuery(this).val()',
				'update'=>'#campur',
				
			))
						
			); ?>
		<?php echo $form->error($model,'queryID'); ?>
		</td>
			</tr>
		</table>
	</div>
	
	<div id = "campur">
		<?php if ($id !== null) {
			$this->actionDinamik($id); ?>
			<script type='text/javascript'>split();</script>
		<?php
		}//echo $this->autoGen($id); } ?>
	</div>
	
<style type="text/css"><!--
.error { border:2px solid red; }
--></style>

<?php $this->endWidget(); ?>	

</div><!-- form -->

