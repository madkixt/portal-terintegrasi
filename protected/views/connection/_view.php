<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('connectionID')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->connectionID), array('view', 'id'=>$data->connectionID)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('serverName')); ?>:</b>
	<?php echo CHtml::encode($data->serverName); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('IPAddress')); ?>:</b>
	<?php echo CHtml::encode($data->IPAddress); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('username')); ?>:</b>
	<?php echo CHtml::encode($data->username); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('password')); ?>:</b>
	<?php echo CHtml::encode($data->password); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('description')); ?>:</b>
	<?php echo CHtml::encode($data->description); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('creationDate')); ?>:</b>
	<?php echo CHtml::encode($data->creationDate); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('modifiedDate')); ?>:</b>
	<?php echo CHtml::encode($data->modifiedDate); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('createdBy')); ?>:</b>
	<?php echo CHtml::encode($data->createdBy); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('lastModifiedBy')); ?>:</b>
	<?php echo CHtml::encode($data->lastModifiedBy); ?>
	<br />

	*/ ?>

</div>