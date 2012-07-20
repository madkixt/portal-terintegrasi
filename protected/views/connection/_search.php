<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

<?php if (!$this->isUser()) { ?>
	<div class="row">
		<?php echo $form->label($model,'connectionID'); ?>
		<?php echo $form->textField($model,'connectionID', array('size' => 3, 'maxlength' => 3)); ?>
	</div>
<?php } ?>

	<div class="row">
		<?php echo $form->label($model,'dbms'); ?>
		<?php echo $form->textField($model,'dbms',array('size'=>20,'maxlength'=>30)); ?>
	</div>
	
	<div class="row">
		<?php echo $form->label($model,'IPAddress'); ?>
		<?php echo $form->textField($model,'IPAddress',array('size'=>15,'maxlength'=>15)); ?>
	</div>

<?php if ($this->isAdmin()) { ?>
	<div class="row">
		<?php echo $form->label($model,'username'); ?>
		<?php echo $form->textField($model,'username',array('size'=>20,'maxlength'=>20)); ?>
	</div>
<?php } ?>
	
	<div class="row">
		<?php echo $form->label($model,'serverName'); ?>
		<?php echo $form->textField($model,'serverName',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'description'); ?>
		<?php echo $form->textArea($model,'description',array('rows'=>6, 'cols'=>50)); ?>
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
		<?php echo $form->label($model,'createdBy'); ?>
		<?php echo $form->textField($model,'createdBy'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'lastModifiedBy'); ?>
		<?php echo $form->textField($model,'lastModifiedBy'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->