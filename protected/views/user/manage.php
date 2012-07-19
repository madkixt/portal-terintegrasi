<?php
$this->breadcrumbs=array(
	'Users'
);

$this->menu=array(
	array('label'=>'Add User', 'url'=>array('add')),
	array('label' => 'Assign Queries', 'url' => array('assignQueryAll')),
	array('label' => 'Remove Queries', 'url' => array('removeQueryAll')),
	array('label' => 'Assign Connections', 'url' => array('assignConnAll')),
	array('label' => 'Remove Connections', 'url' => array('removeConnAll')),
	
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('user-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Users</h1>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'user-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array(
			'name' => 'userID',
			'htmlOptions' => array('width' => '50px')
		),
		array(
			'name' => 'role',
			'value' => 'User::model()->userRoles[$data["role"]]',
			'htmlOptions' => array('width' => '80px')
		),
		array(
			'name' => 'username',
			'htmlOptions' => array('width' => '150px')
		),
		'description',
		array(
			'class'=>'CButtonColumn',
			'buttons' => array(
				'delete' => array(
					'visible' => '$data->deleteClickable',
				),
				'update' => array(
					'visible' => '$data->editClickable'
				),
			)
		),
	),
)); ?>
