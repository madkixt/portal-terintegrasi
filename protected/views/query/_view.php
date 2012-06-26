<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('queryID')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->queryID), array('view', 'id'=>$data->queryID)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('judulQuery')); ?>:</b>
	<?php echo CHtml::encode($data->judulQuery); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('isiQuery')); ?>:</b>
	<?php echo CHtml::encode($data->isiQuery); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('databaseName')); ?>:</b>
	<?php echo CHtml::encode($data->databaseName); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('notes')); ?>:</b>
	<?php echo CHtml::encode($data->notes); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('creationDate')); ?>:</b>
	<?php echo CHtml::encode($data->creationDate); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('modifiedDate')); ?>:</b>
	<?php echo CHtml::encode($data->modifiedDate); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('notesModifiedDate')); ?>:</b>
	<?php echo CHtml::encode($data->notesModifiedDate); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('createdBy')); ?>:</b>
	<?php echo CHtml::encode($data->createdBy); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('lastModifiedBy')); ?>:</b>
	<?php echo CHtml::encode($data->lastModifiedBy); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('lastNotesEditor')); ?>:</b>
	<?php echo CHtml::encode($data->lastNotesEditor); ?>
	<br />

	*/ ?>

</div>