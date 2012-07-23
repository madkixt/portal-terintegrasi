<?php

Yii::import('zii.widgets.grid.CGridView');

class PGridView extends CGridView {
	protected function createDataColumn($text)
	{
		if(!preg_match('/^([\w\s\*\(\)\.]+)(:([\w\s\*\(\)]*))?(:(.*))?$/',$text,$matches))
			throw new CException(Yii::t('zii','The column must be specified in the format of "Name:Type:Label", where "Type" and "Label" are optional.'));
			
		$column=new CDataColumn($this);
		$column->name=$matches[1];
		if(isset($matches[3]) && $matches[3]!=='')
			$column->type=$matches[3];
		if(isset($matches[5]))
			$column->header=$matches[5];
		return $column;
	}
}

?>