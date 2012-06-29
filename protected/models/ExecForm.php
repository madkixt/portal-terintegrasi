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
		$user = User::model()->findByPk(Yii::app()->user->getId());
		$mesinArray= CHtml::listData($user->tblConnections, 'connectionID', 'name');
		return $mesinArray;
	}
	

	/*memperoleh judul query*/
	public static function getJudulQueryOptions()
	{
		$user = User::model()->findByPk(Yii::app()->user->getId());
		$judulArray= CHtml::listData($user->tblQueries, 'queryID', 'judulQuery');
		return $judulArray;
	}
	
	public function exec()
	{
	}

}