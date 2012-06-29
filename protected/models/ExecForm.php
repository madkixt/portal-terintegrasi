<?php

/** halaman eksekusi query */

class ExecForm extends CFormModel
{
	public $queryID;
	public $isiQuery;
	public $database;
	public $connection;
	public $queries;
	
	/*rules */
	public function rules()
	{
		return array(
			array('isiQuery, database, koneksi','required'),
		//	array('database,mesin','authenticate'),
		);
	}
	
	/*relation*/
	public function relations()
	{
		return array(
			'judulQuery'=>array(self::HAS_MANY,'ExecForm','queryID'),
		);
	}
	
	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'queryID' => 'Judul Query',
			'isiQuery' => 'Query',
			'database' => 'Database',
			'mesin' => 'Koneksi'
		);
	}
	
	/*memeperoleh mesin*/
	public static function getConnection()
	{
		$user = User::model()->findByPk(Yii::app()->user->getId());
		$mesinArray= CHtml::listData($user->tblConnections, 'connectionID', 'name');
		return $mesinArray;
	}
	
	public function loadModel() {
		$this->queries = User::model()->findByPk(Yii::app()->user->getId())->tblQueries;
		$this->database = $this->queries[0]->databaseName;
		$this->isiQuery = $this->queries[0]->isiQuery;
	}
	
	public function exec()
	{
		
	}
}