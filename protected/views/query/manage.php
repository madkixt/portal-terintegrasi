<?php
$this->breadcrumbs=array(
	'Queries'
);

$this->menu=array(
	array('label'=>'Add Query', 'url'=>array('add'))
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('query-grid', {
		data: $(this).serialize()
	});
	return false;
});
");

if (Yii::app()->urlManager != null && Yii::app()->urlManager->urlFormat === 'path') {
	$path = true;
} else {
	$path = false;
}

?>

<h1>Manage Queries</h1>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'query-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'queryID',
		array(
			'class' => 'CQueryDataColumn',
			'name' => 'judulQuery'
		),
		'isiQuery',
		'databaseName',
		'notes',
		/*
		'creationDate',
		'modifiedDate',
		'notesModifiedDate',
		'createdBy',
		'lastModifiedBy',
		'lastNotesEditor',
		*/
		array(
			'class'=>'CButtonColumn',
		)
	)
)); ?>
