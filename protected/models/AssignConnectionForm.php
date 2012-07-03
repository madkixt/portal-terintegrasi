<?php

class AssignConnectionForm extends CFormModel {
	public $connectionID;
	
	public function rules()
	{
		return array(
			array('connectionID', 'required'),
		);
	}
	
	public function attributeLabels() {
		return array(
			'connectionID' => 'Connection',
		);
	}
}

?>