<?php

/** halaman eksekusi query */

function sortee($a, $b) {
	return strcasecmp($a->name, $b->name);
}

class ExecForm extends CFormModel {
	public $queryID;
	public $database;
	public $connection;
	public $queries;

	/*rules */
	public function rules()
	{
		return array(
			array('database, connection','required'),
		//	array('database,mesin','authenticate'),
		);
	}
	
	/*relation*/
	public function relations()
	{
		return array(
			'judulQuery'=>array(self::HAS_MANY,'ExecForm','queryID'),
			'connection'=>array(SELF::HAS_MANY,'ExecForm','ConnectionID'),
		);
	}
	
	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels() {
		return array(
			'queryID' => 'Query Title',
			'database' => 'Database',
			'mesin' => 'Connection'
		);
	}
	
	/*memeperoleh mesin*/
	public static function getConnection()
	{
		$user = User::model()->findByPk(Yii::app()->user->getId());
		$model = $user->tblConnections;
		
		uasort($model, "sortee");
		if ($this->isAdmin()) {
			$conn = new Connection;
			$conn->connectionID = 'other';
			$conn->IPAddress = 'New connection';
			$conn->username = '';
			$model[] = $conn;
		}
		
		return CHtml::listData($model, 'connectionID', 'name');
	}
	
	public static function getJudul()
	{
		$user = User::model()->findByPk(Yii::app()->user->getId());
		$judulArray= CHtml::listData($user->tblQueries, 'queryID', 'title');
		return $judulArray;
	}
}