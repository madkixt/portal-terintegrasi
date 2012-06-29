<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'changePassword-form',
	'enableAjaxValidation'=>false,
));
?>


<h1>Change Password</h1>

	<div class="row">
		<?php echo $form->labelEx($model,'old password'); ?>
		<?php echo $form->passwordField($model,'oldpwd'); ?>
		<?php echo $form->error($model,'oldpwd'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'new password'); ?>
		<?php echo $form->passwordField($model,'newpwd'); ?>
		<?php echo $form->error($model,'newpwd'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'repeat new password'); ?>
		<?php echo $form->passwordField($model,'repeatnew'); ?>
		<?php echo $form->error($model,'repeatnew'); ?>
	</div>
	
	<div class="row buttons">
		<?php echo CHtml::submitButton('Save'); ?>
	</div>
	

<?php $this->endWidget(); ?>
</div><!-- form -->