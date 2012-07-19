<?php

class AssignConnAllForm extends CFormModel {
	public $userIDs;
	public $connIDs;
	
	public function rules() {
		return array(
			
		);
	}
	
	public function attributeLabels() {
		return array(
			
		);
	}
	
	public function getUsers() {
		return new CActiveDataProvider('User', array(
			'criteria' => array(
				'condition' => 'role = :r1 OR role = :r2',
				'params' => array(
					':r1' => 1,
					':r2' => 2
				),
				'order' => 'username',
			),
			'pagination' => false
		));
	}
	
	public function getConnections() {
		return new CActiveDataProvider('Connection', array(
			'criteria' => array(
				'order' => 'IPAddress, username'
			),
			'pagination' => false
		));
	}
}

?>