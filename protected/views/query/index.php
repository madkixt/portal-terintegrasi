<?php
$this->breadcrumbs=array(
	'Queries',
);

$this->menu=array(
	array('label'=>'Create Query', 'url'=>array('create')),
	array('label'=>'Manage Query', 'url'=>array('admin')),
);
?>

<h1>Queries</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
