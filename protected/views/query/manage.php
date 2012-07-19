<?php
if (!$this->isUser())
	$this->breadcrumbs=array(
		'Queries'
	);

$this->menu=array(
	array('label'=>'Add Query', 'url'=>array('add'), 'visible' => $this->isAdmin()),
	array('label' => 'Assign Queries', 'url' => array('user/assignQueryAll'), 'visible' => $this->isAdmin()),
	array('label' => 'Remove Queries', 'url' => array('user/removeQueryAll'), 'visible' => $this->isAdmin())
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

<?php if (Yii::app()->user->hasFlash('success')) { ?>
<div class="flash-success">
	<em><?php echo Yii::app()->user->getFlash('success'); ?></em>
</div>
<?php } ?>

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
			'htmlOptions' => array('width' => '50px'),
			'visible' => !$this->isUser()
		),
		array(
			'class' => 'CQueryDataColumn',
			'name' => 'title'
		),
		array(
			'name' => 'databaseName',
			'htmlOptions' => array('width' => '100px'),
			'visible' => !$this->isUser()
		),
		'notes',
		array(
			'class'=>'CButtonColumn',
			'template' => $template,
			'buttons' => array(
				'remove' => array(
					'label' => 'Remove',
					'imageUrl' => Yii::app()->request->baseUrl . '/images/buttons/remove.png',
					'url' => 'Yii::app()->createUrl(\'user/removeQuery\', array(\'id\' => ' . $id . ', \'qid\' => $data["queryID"]))',
					'click' => 
'function() {
	if(!confirm("Are you sure you want to remove this item?")) return false;
	var th = this;
	var afterUpdate = function(){};
	$.fn.yiiGridView.update("query-grid", {
		type:"POST",
		url:$(this).attr("href"),
		success:function(data) {
			$.fn.yiiGridView.update("query-grid");
			afterUpdate(th,true);
		},
		error:function(XHR) {
			return afterUpdate(th,false,XHR);
		}
	});
	return false;
}'
				),
			),
			'htmlOptions' => array('width' => '75px')
		)
	),
	'nullDisplay' => ''
)); ?>
