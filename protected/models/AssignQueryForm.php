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
	
	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'rememberMe'=>'Remember me next time',
		);
	}
	
	public static function getJudulQueryOptions()
	{
		return array(
		);
	}
	
	public function exec()
	{
	}

}