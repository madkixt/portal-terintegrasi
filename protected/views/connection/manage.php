<?php
$this->breadcrumbs=array(
	'Connections'
);

$this->menu=array(
	array('label'=>'Add Connection', 'url'=>array('add'))
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('connection-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Connections</h1>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'connection-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'connectionID',
		'serverName',
		'IPAddress',
		'username',
		'description',
		/*
		'password',
		'creationDate',
		'modifiedDate',
		'createdBy',
		'lastModifiedBy',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
