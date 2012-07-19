<?php

class AssignQueryAllForm extends CFormModel {
	public $userIDs;
	public $queryIDs;
	
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
	
	public function getQueries() {
		return new CActiveDataProvider('Query', array(
			'criteria' => array(
				'order' => 'title'
			),
			'pagination' => false
		));
	}
}

?>