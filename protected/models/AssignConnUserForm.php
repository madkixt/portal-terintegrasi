<?php

class AssignConnUserForm extends CFormModel {
	public $userID;
	
	public function rules() {
		return array(
			array('userID', 'required'),
		);
	}
	
	public function attributeLabels() {
		return array(
			'userID' => 'Username',
		);
	}
}

?>