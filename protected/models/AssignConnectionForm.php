<?php

class AssignConnectionForm extends CFormModel {
	public $userID;
	public $connectionID;
	
	public function rules()
	{
		return array(
			array('userID, connectionID', 'required'),
		);
	}
	
	public function attributeLabels() {
		return array(
			'userID' => 'User',
			'connectionID' => 'Connection',
		);
	}
}

?>