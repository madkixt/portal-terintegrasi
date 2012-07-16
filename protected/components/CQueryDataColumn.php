<?php

class CQueryDataColumn extends CDataColumn {
	protected function renderDataCellContent($row,$data)
	{
		if($this->value!==null)
			$value=$this->evaluateExpression($this->value,array('data'=>$data,'row'=>$row));
		else if($this->name!==null) {
			echo CHtml::link($data->title, array("site/exec", 'id' => $data->queryID));
			$value = '';
		}
		echo $value===null ? $this->grid->nullDisplay : $this->grid->getFormatter()->format($value,$this->type);
	}
}

?>