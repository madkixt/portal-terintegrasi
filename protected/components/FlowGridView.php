<?php

Yii::import('zii.widgets.grid.CGridView');

class FlowGridView extends CGridView {
	public function renderItems() {
		if($this->dataProvider->getItemCount()>0 || $this->showTableOnEmpty) {
			echo "<table class=\"{$this->itemsCssClass}\">\n";
			$this->renderTableHeader();
			ob_start();
			$this->renderTableBody();
			$body=ob_get_clean();
			$this->renderTableFooter();
			echo $body; // TFOOT must appear before TBODY according to the standard.
			echo "</table>";
		}
		else
			$this->renderEmptyText();
	}

	/**
	 * Renders the table header.
	 */
	public function renderTableHeader()
	{
		if(!$this->hideHeader){
			echo "<tr><td><table>\n";

			if($this->filterPosition===self::FILTER_POS_HEADER)
				$this->renderFilter();

			echo "<tr>\n";
			foreach($this->columns as $column)
				$column->renderHeaderCell();
			echo "</tr>\n";

			if($this->filterPosition===self::FILTER_POS_BODY)
				$this->renderFilter();

			echo "</table></td></tr>\n";
		}
		else if($this->filter!==null && ($this->filterPosition===self::FILTER_POS_HEADER || $this->filterPosition===self::FILTER_POS_BODY))
		{
			echo "<thead>\n";
			$this->renderFilter();
			echo "</thead>\n";
		}
	}

	/**
	 * Renders the table body.
	 */
	public function renderTableBody()
	{
		$data = $this->dataProvider->getData();
		$n = count($data);
		echo "<tr><td><div style='overflow: auto'><table class=\"{$this->itemsCssClass}\">\n";

		if ($n > 0) {
			for($row=0;$row<$n;++$row)
				$this->renderTableRow($row);
		}
		else {
			echo '<tr><td colspan="'.count($this->columns).'">';
			$this->renderEmptyText();
			echo "</td></tr>\n";
		}
		echo "</table></div></td></tr>\n";
	}

	/**
	 * Renders a table body row.
	 * @param integer $row the row number (zero-based).
	 */
	public function renderTableRow($row)
	{
		if($this->rowCssClassExpression!==null) {
			$data=$this->dataProvider->data[$row];
			echo '<tr class="' . $this->evaluateExpression($this->rowCssClassExpression,array('row' => $row, 'data' => $data)).'">';
		}
		else if(is_array($this->rowCssClass) && ($n=count($this->rowCssClass))>0)
			echo '<tr class="'.$this->rowCssClass[$row%$n].'">';
		else
			echo '<tr>';
		foreach($this->columns as $column)
			$column->renderDataCell($row);
		echo "</tr>\n";
	}
}

?>