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
			'connection'=>array(SELF::HAS_MANY,'ExecForm','ConnectionID'),
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
	
	public static function getJudul()
	{
		$user = User::model()->findByPk(Yii::app()->user->getId());
		$judulArray= CHtml::listData($user->tblQueries, 'queryID', 'judulQuery');
		return $judulArray;
	}
	
	public static function getDatabaseBy($queryID)
	{	
	/*	$db = Query::model()->findByPk('queryID=:queryID order by databaseName', array('queryID'=>$queryID)); */
		$db[] = Query::model()->findByPk($queryID);
		$dbArray = CHtml::listData($db, 'queryID','databaseName');
		//print_r($db);
		
		return $dbArray;
		
	}
	
	
	public function exec()
	{
		
	}
}