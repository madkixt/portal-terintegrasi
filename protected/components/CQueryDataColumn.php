<?php

class CQueryDataColumn extends CDataColumn {
	protected function renderDataCellContent($row,$data)
	{
		if($this->value!==null)
			$value=$this->evaluateExpression($this->value,array('data'=>$data,'row'=>$row));
		else if($this->name!==null) {
			$url = CHtml::link($data->judulQuery, $this->name);
			$url = substr($url, strpos($url, ">") + 1);
			$url = substr($url, 0, strpos($url, "<"));
			echo CHtml::link($data->judulQuery, array("index/query", 'id' => $data->queryID));
			
			$value = "";
		}
		echo $value===null ? $this->grid->nullDisplay : $this->grid->getFormatter()->format($value,$this->type);
	}
}

?>