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

<h1>Manage <?php if ($username != null) {echo CHtml::link($username, array('/user', 'id' => $id)); echo "'s";} ?> Connections</h1>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'connection-grid',
	'dataProvider'=>$model->search($id),
	'filter'=>$model,
	'columns'=>array(
		array(
			'name' => 'connectionID',
			'htmlOptions' => array('width' => '50px')
		),
		'serverName',
		'IPAddress',
		'username',
		'description',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
