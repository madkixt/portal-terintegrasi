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
?>

<h1>Manage <?php if ($username != null) {echo CHtml::link($username, array('/user', 'id' => $id)); echo "'s";} ?> Queries</h1>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php
$this->widget('zii.widgets.grid.CGridView', array(
	'id' => 'query-grid',
	'dataProvider' => $model->search($id),
	'filter' => $model,
	'columns' => array(
		array(
			'name' => 'queryID',
			'htmlOptions' => array('width' => '50px')
		),
		array(
			'class' => 'CQueryDataColumn',
			'name' => 'judulQuery'
		),
		'databaseName',
		'notes',
		array(
			'class'=>'CButtonColumn',
			'template' => $this->getVisibleButtons($id),
			'buttons' => array(
				'remove' => array(
					'label' => 'Remove',
					'imageUrl' => Yii::app()->request->baseUrl . '/images/buttons/remove.png'
				)
			),
			'htmlOptions' => array('width' => '75px')
		)
	),
	'nullDisplay' => ''
)); ?>
