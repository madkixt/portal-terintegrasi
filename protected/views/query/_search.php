<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

<?php if (!$this->isUser()) { ?>
	<div class="row">
		<?php echo $form->label($model,'queryID'); ?>
		<?php echo $form->textField($model,'queryID'); ?>
	</div>
<?php } ?> 

	<div class="row">
		<?php echo $form->label($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>50,'maxlength'=>50)); ?>
	</div>

<?php if (!$this->isUser()) { ?>
	<div class="row">
		<?php echo $form->label($model,'databaseName'); ?>
		<?php echo $form->textField($model,'databaseName',array('size'=>30,'maxlength'=>30)); ?>
	</div>
<?php } ?>

	<div class="row">
		<?php echo $form->label($model,'notes'); ?>
		<?php echo $form->textArea($model,'notes',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'queryString'); ?>
		<?php echo $form->textArea($model,'queryString',array('rows'=>6, 'cols'=>50)); ?>
	</div>
	
	<div class="row">
		<?php echo $form->label($model,'creationDate'); ?>
		<?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
			'model' => $model,
			'attribute' => 'creationDate',
			'options' => array(
				'dateFormat' => 'yy-mm-dd'
			)
		)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'modifiedDate'); ?>
		<?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
			'model' => $model,
			'attribute' => 'modifiedDate',
			'options' => array(
				'dateFormat' => 'yy-mm-dd'
			)
		)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'notesModifiedDate'); ?>
		<?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
			'model' => $model,
			'attribute' => 'notesModifiedDate',
			'options' => array(
				'dateFormat' => 'yy-mm-dd'
			)
		)); ?>
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