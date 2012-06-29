<?php
$this->breadcrumbs=array(
	'Users'=>array('manage'),
	$model->username => array('view','id'=>$model->userID),
	'Assign Query',
);

$this->menu=array(
	array('label'=>'Add User', 'url'=>array('add')),
	array('label'=>'View User', 'url'=>array('view', 'id'=>$model->userID)),
	array('label' => 'Assign Connections', 'url' => array('assignConnection')),
	array('label'=>'Delete User', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->userID),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Back to Manage User', 'url'=>array('manage')),
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

<h1>Assign Queries</h1>

<?php
$dataProv = new CArrayDataProvider($model->tblQueries, array(
	'keyField' => 'queryID'
));

$cam = new CAssetManager;
$u = $cam->baseUrl . "/buttons/view.png";
echo "<a href='$u'>view</a>";

$this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider'=> $dataProv,
	'columns' => array(
		'queryID',
		'judulQuery',
		'isiQuery',
		'databaseName',
		'notes',
		array (
			'class' => 'CButtonColumn',
			'template' => '{view} {edit} {delete}',
			'buttons' => array(
				'view' => array(
					'url' => 'Yii::app()->createUrl("query/view", array("id" => $data->queryID))',
					'imageUrl' => Yii::app()->request->baseUrl . "/images/buttons/view.png"
				),
				'edit' => array(
					'url' => 'Yii::app()->createUrl("query/edit", array("id" => $data->queryID))',
					'imageUrl' => Yii::app()->request->baseUrl . "/images/buttons/edit.png"
				),
				'delete' => array(
					'url' => 'Yii::app()->createUrl("query/delete", array("id" => $data->queryID))',
					'imageUrl' => Yii::app()->request->baseUrl . "/images/buttons/delete.png"
				),
			),
		)
	),
));
?>
