
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
		<?php echo $form->labelEx($model,'queryID'); ?>
		<?php echo $form->dropDownList($model, 'queryID', $model->getJudul(), 
		array(
		
			'ajax' => array(
				'type'=>'POST',
				'dataType'=>'json',
				'url'=> CController::createUrl('/site/dinamik'),
				'data'=>'js:"queryID="+jQuery(this).val()',
			//	'data'=>array('queryID'=>'js:this.value'),
				//'data'=>"js:{queryID:$(this).val()}",
			//	'update'=>'#databasee',
				'success'=>'function(data) {
					$("#databasee").html(data.satu);
				}',
			))); ?>
		<?php echo $form->error($model,'queryID'); ?>
	</div>
		
	<div class="row">
		<?php echo $form->labelEx($model,'connection'); ?>
		<?php echo $form->dropDownList($model,'connection', $model->getConnection()); ?>
		<?php echo $form->error($model,'connection'); ?>
	</div>
	
	<div class="row">
		<div id = "databasee">
		<?php echo $form->labelEx($model,'database'); ?>
		<?php echo $form->dropDownList($model,'database',$model->getDatabaseBy($model->queryID), array('id'=>'queryID')); ?>
		<?php echo $form->error($model,'database'); ?>
		</div>
	</div>

	
	<div class="row">
		<?php echo $form->labelEx($model,'isiQuery'); ?>
		<?php echo $form->textArea($model, 'isiQuery', array('cols'=>60,'rows'=>5)); ?>
		<?php echo $form->error($model,'isiQuery'); ?>
	</div>
	
	<div class="row buttons">
		<?php echo CHtml::submitButton('Exec'); ?>
	</div>

	
<?php $this->endWidget(); ?>	
</div><!-- form -->