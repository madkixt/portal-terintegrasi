<?php

class SiteController extends Controller
{
	public $defaultAction = 'exec';
	
	public function filters()
	{
		return array(
			'accessControl + exec', // perform access control for CRUD operations
		);
	}
	
	public function accessRules()
	{
		return array(
			array('deny',  // deny all anonymous users
				'users' => array('?'),
			),
		);
	}
	
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		$this->render('index');
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
	    if($error=Yii::app()->errorHandler->error)
	    {
	    	if(Yii::app()->request->isAjaxRequest)
	    		echo $error['message'];
	    	else
	        	$this->render('error', $error);
	    }
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$headers="From: {$model->email}\r\nReply-To: {$model->email}";
				mail(Yii::app()->params['adminEmail'],$model->subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model=new LoginForm;
		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}
	
	/**
	 * Displays the execution page
	 */
	public function actionExec()
	{
		if (!isset($_GET['id'])) {
			
		}
		$model = new ExecForm;
//		$model->loadModel();
	//	$queryID = $this->loadModel($id);
	//	$model->queryID=Query::model()->findByPk($id);
		
		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='exec-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		} 
		// collect user input data
		if(isset($_POST['ExecForm']))
		{
			$model->attributes=$_POST['ExecForm'];
			//$query = Query::model()->findByPk($_POST['ExecForm']['queryID']);
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->exec())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the exec form
		$this->render('exec',array('model' => $model));
	}	
	
	
	public function actionDinamik()
	{
		//echo 'zzz';
	//	echo $_POST['queryID'];
		//$model=Query::model()->findByPkfindByPk($_POST['queryID']);
	//	echo CHtml::dropDownList($model,'database', CHtml::listData(Query::model()->findByPk(4), 'queryID','databaseName'));
		$data = Query::model()->findByPk($_POST['queryID']);
//		$data = Query::model()->findByPk($_POST['queryID'])->database;
	//		$data = Location::model()->findAll('parentID=:parentID',array(':parentID'=>(int)$_POST['Current-Controller']['queryID']));
	
		
			echo CHtml::tag('option', array('value'=>$data->queryID), CHtml::encode($data->databaseName), true);
		
		//return;
		
	}
	
	public function actionTest() {
		// $dsn = 'sqlsrv:server=10.204.35.92;database=MPS';
		$dsn = 'sqlsrv:server=WIBI-PC;database=AdventureWorks';
		
		$username = '';
		$password = ''; //m4nd1r1db
		// $sql = "select cardno, productid,  INTauthamount AS denom, renewalStatus, cardbalance, cardbalanceoncard , ModifiedOn, ModifiedBy from dbo.MPS_CardMaster with (nolock) where cardno in ('6032981019317189')";
		$sql = "SELECT * FROM HumanResources.Employee";
		
		$con = new CDbConnection($dsn, $username, $password);
		$con->active = true;
		$cmd = $con->createCommand($sql);
		$data = $cmd->queryAll();
		
		$dp = new CArrayDataProvider($data, array(
			'pagination' => array(
				'pageSize' => 1000
			),
			'keyField' => false
		));
		
		Yii::app()->user->setState('result', $data);
		$this->render('result', array('res' => $dp));
	}
	
	public function actionDownload($type = 'xls') {
		if (Yii::app()->user->getState('result') == null)
			throw new CHttpException(403, 'No query result found.');
			
		if ($type === 'xls') {
			$this->generateExcel();
			return;
		}
		if ($type === 'txt') {
			$this->generateText();
		}
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
	
	private function generateExcel() {
		//GET DATA FROM PREVIOUS STATE
		$dataprov = Yii::app()->user->getState('result');
		
		$tb = array();
		$header = array();
		foreach ($dataprov[0] as $property=>$value) {
			$headr[] = $property;
		}
		
		$tb[] = $headr;
		foreach ($dataprov as $i)
		{
			$tb[] = $i;
		}
		
		Yii::import('application.extensions.phpexcel.JPhpExcel');
	    $xls = new JPhpExcel('UTF-8', false, 'test');
    	$xls->addArray($tb);
	    $xls->generateXML('export'); // bisa diganti dengan nama file sesuka hati
	}
	
	private function generateText() {
		$dataprov = Yii::app()->user->getState('result');
		$th = new TextHelper;
		
		header("Content-Type: application/text");
		header("Content-Disposition: inline; filename=\"export.txt\"");
		
		if (count($dataprov) <= $th->rowsPerWrite) {
			echo $th->toText($dataprov);
			return;
		}
		
		$lengths = $th->maxLengths($dataprov);
		
		echo $th->headerText($dataprov, $lengths);
		$iter = ceil(count($dataprov) / $th->rowsPerWrite);
		for ($i = 0; $i < $iter; $i++) {
			echo $th->partText($dataprov, $i, $lengths);
		}
		echo $th->line($lengths);
	}
}