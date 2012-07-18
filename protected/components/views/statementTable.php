<table>
<?php
	$i = 1;
	foreach ($this->statements as $statement) {
	?>
	
	<tr id='single<?php echo $i; ?>'>
		<td>
			<strong>Statement <?php echo $i; ?></strong><br />
			<textarea name='statement[<?php echo $i; ?>]' cols='40' rows='8'><?php echo $statement; ?></textarea><br />&nbsp;
		<?php 
			if ($i > 1) { ?>
				<a href='#' id='remove<?php echo $i; ?>' onclick='removeTArea()'>Remove</a>
			<?php }
		?>
		</td>
		<td>
			<strong>Notes <?php echo $i; ?></strong><br />
			<textarea name='notes[<?php echo $i; ?>]' cols='40' rows='8'><?php echo $this->notes[$i]; ?></textarea><br />&nbsp;
		</td>
	</tr>
	
	<?php
		$i++;
	}
	
Yii::app()->clientScript->registerScript('remove', <<<EOD
var inputs = $('tr[id^="single"]');
var i = 2;
for (; i <= inputs.length; i++) {
	$('#remove' + i).bind('click', function() {
		var j = Number($(this).attr('id').substr($(this).attr('id').length - 1, 1));
		var num = $('tr[id^="single"]').length;
		for (; j < num; j++) {
			$('textarea[name="statement[' + j + ']"]').val($('textarea[name="statement[' + (j + 1) + ']"]').first().val());
			$('textarea[name="notes[' + j + ']"]').val($('textarea[name="notes[' + (j + 1) + ']"]').first().val());
		}
		$('#single' + num).remove();
	});
}
EOD
, CClientScript::POS_READY);
	
?>
</table>