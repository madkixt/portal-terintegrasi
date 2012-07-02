<?php

class AssignQueryForm extends CFormModel {
	public $userID;
	public $queryID;
	
	public function rules()
	{
		return array(
			array('userID, queryID', 'required'),
		);
	}
	
	public function attributeLabels() {
		return array(
			'userID' => 'User',
			'queryID' => 'Query',
		);
	}
}

?>