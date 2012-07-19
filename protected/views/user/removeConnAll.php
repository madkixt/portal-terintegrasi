<?php
$this->breadcrumbs=array(
	'Users' => array('manage'),
	'Remove Connection'
);
?>

<?php
if (Yii::app()->user->hasFlash('success')) { ?>
<div class="flash-success">
	<em><?php echo Yii::app()->user->getFlash('success'); ?></em>
</div>
<?php } ?>

<h1>Remove Connection</h1>

<p><em>Removes connections from users. It is safe to remove a connection even if the user hasn't been assigned the connection.</em></p>

<form method='POST'>
<div style='float: left; margin-right: 20px'>
<?php
$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'user-grid',
	'dataProvider' => $model->users,
	'enableSorting' => false,
	'columns'=>array(
		array(
			'class' => 'CCheckBoxColumn',
			'selectableRows' => 2,
			'checkBoxHtmlOptions' => array(
				'name' => 'user[]'
			),
		),
		array(
			'name' => 'username',
			'header' => 'User',
			'htmlOptions' => array(
				'width' => '200px'
			)
		)
	),
	'summaryText' => ''
));
?>
</div>

<div style='float: left'>
<?php
$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'query-grid',
	'dataProvider' => $model->connections,
	'enableSorting' => false,
	'columns'=>array(
		array(
			'name' => 'name',
			'header' => 'Connection',
			'htmlOptions' => array(
				'width' => '200px'
			)
		),
		array(
			'class' => 'CCheckBoxColumn',
			'selectableRows' => 2,
			'checkBoxHtmlOptions' => array(
				'name' => 'conn[]'
			)
		),
	),
	'summaryText' => ''
));
?>
</div>

<div class="row buttons" style='clear: both'>
	<?php echo CHtml::submitButton('Remove'); ?>
</div>
</form>

<script type='text/javascript'>
$(document).ready(function() {
	$('form').submit(function() {
		var error = 0;
		var chks = $('input[name="user[]"]');
		for (var i = 0; i < chks.length; i++) {
			if (chks[i].checked) {
				break;
			}
		}
		
		if (i == chks.length) {
			error = 1;
		}
		
		var chks = $('input[name="conn[]"]');
		for (var i = 0; i < chks.length; i++) {
			if (chks[i].checked) {
				break;
			}
		}
		
		if (i == chks.length) {
			if (error == 1) {
				alert('Please check the users and the connections.');
			} else {
				alert('Please check the connections.');
			}
			return false;
		}
		
		if (error == 1) {
			alert('Please check the users.');
			return false;
		}
		return true;
	});
});
</script>