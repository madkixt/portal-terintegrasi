<?php

/** halaman eksekusi query */

class ExecForm extends CFormModel
{
	public $judulQuery;
	public $isiQuery;
	public $database;
	public $mesin;
	
	
	/*rules */
	public function rules()
	{
		return array(
			array('database,mesin','required'),
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
			'rememberMe'=>'Remember me next time',
		);
	}
	
	/*memeperoleh mesin*/
	public static function getMesin()
	{
		$userID = Yii::app()->user->getId();
		$user = User::model()->findByPk($userID);
		$cons = $user->tblConnections;
		$mesinArray= CHtml::listData($cons, 'connectionID', 'IPAddress');
		return $mesinArray;
	}
	

	/*memperoleh judul query*/
	public static function getJudulQueryOptions()
	{
		$judulArray= CHtml::listData(query::model()->findAll(),'queryID','judulQuery');
		return $judulArray;
	}
	
	public function exec()
	{
	}

}