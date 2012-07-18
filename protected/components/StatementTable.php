<?php

class StatementTable extends CWidget {
	public $statements = array();
	public $notes = array();
	public $queryID;
	
	public function init() {
		if ($this->queryID !== null) {
			$query = Query::model()->findByPk($this->queryID);
			if ($query === null)
				return;
			
			$i = 1;
			foreach ($query->statements as $stmt) {
				$this->statements[$i] = $stmt->queryStatement;
				$this->notes[$i++] = $stmt->notes;
			}
			
			return;
		}
	}
	
	public function run() {
		$this->render('statementTable');
	}
}

?>