<?php

class AssignQueryForm extends CFormModel {
	public $queryID;
	
	public function rules()
	{
		return array(
			array('queryID', 'required'),
		);
	}
	
	public function attributeLabels() {
		return array(
			'queryID' => 'Query',
		);
	}
}

?>