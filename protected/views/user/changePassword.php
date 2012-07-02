<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'changepassword-form',
	'enableAjaxValidation'=>false,
));
?>

<h1>Change Password</h1>

	<div class="row">
		<?php echo $form->labelEx($model,'oldpwd'); ?>
		<?php echo $form->passwordField($model,'oldpwd'); ?>
		<?php echo $form->error($model,'oldpwd'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'newpwd'); ?>
		<?php echo $form->passwordField($model,'newpwd'); ?>
		<?php echo $form->error($model,'newpwd'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'newpwd_repeat'); ?>
		<?php echo $form->passwordField($model,'newpwd_repeat'); ?>
		<?php echo $form->error($model,'newpwd_repeat'); ?>
	</div>
	
	<div class="row buttons">
		<?php echo CHtml::submitButton('Save'); ?>
	</div>
	
<?php $this->endWidget(); ?>
</div><!-- form -->