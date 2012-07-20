<?php
$this->breadcrumbs=array(
	'Connections'
);

$this->menu=array(
	array('label'=>'Add Connection', 'url'=>array('add'), 'visible' => $this->isAdmin()),
	array('label' => 'Assign Connections', 'url' => array('user/assignConnAll'), 'visible' => $this->isAdmin()),
	array('label' => 'Remove Connections', 'url' => array('user/removeConnAll'), 'visible' => $this->isAdmin())
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
			'htmlOptions' => array('width' => '50px'),
			'visible' => !$this->isUser()
		),
		'IPAddress',
		array(
			'name' => 'username',
			'visible' => $this->isAdmin(),
		),
		array(
			'name' => 'dbms',
			'value' => 'Connection::model()->dbmsOptions[$data["dbms"]]'
		),
		'description',
		array(
			'class'=>'CButtonColumn',
			'template' => $template,
			'buttons' => array(
				'remove' => array(
					'label' => 'Remove',
					'imageUrl' => Yii::app()->request->baseUrl . '/images/buttons/remove.png',
					'url' => 'Yii::app()->createUrl(\'user/removeConnection\', array(\'id\' => ' . $id . ', \'cid\' => $data["connectionID"]))',
					'click' => 
'function() {
	if(!confirm("Are you sure you want to remove this item?")) return false;
	var th = this;
	var afterUpdate = function(){};
	$.fn.yiiGridView.update("connection-grid", {
		type:"POST",
		url:$(this).attr("href"),
		success:function(data) {
			$.fn.yiiGridView.update("connection-grid");
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
)); ?>
