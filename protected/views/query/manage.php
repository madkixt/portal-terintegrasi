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

<?php

if (Yii::app()->user->getState('admin')) {
	if (($id != null) && (($user = User::model()->findByPk($id)) != null) && !$user->admin)
		$templ = '{view} {update} {remove} {delete}';
	else
		$templ = '{view} {update} {delete}';
} else {
	$templ = '{view} {update} {remove}';
}

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
			'template' => $templ,
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
