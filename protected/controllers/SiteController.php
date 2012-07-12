<?php

class SiteController extends Controller
{
	public $defaultAction = 'exec';
	public $filename = 'export';
	
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
	public function actionExec($id = null) {
		$model = new ExecForm;		
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
			if($model->validate() && $model->exec())
				$this->redirect(Yii::app()->user->returnUrl);
		} 
		$statements = null;
		// display the exec form
		$this->render('exec',array(
			'model' => $model,
			'statements' => $statements,
			'id' => $id
		));
	}
	
	public function actionDinamik() {
		if ($_POST['queryID'] == "")
			return;
		
		$data = Query::model()->findByPk($_POST['queryID']);
		$statements = $data->loadStatements();
		
		$enableEditing = CHtml::checkBox('enable editing',false,array(
				'id' => 'enableediting',
				'name'=> 'aa',
				'onclick'=>'javascript: changeedit();'		
			));
		
		
		$tarea = CHtml::textArea('isiquery1','',array('id'=>'isiquery', 'cols'=>60,'rows'=>5, 'readonly'=>"readonly" ));
		echo "<b>&nbsp; Database</b>";
		$stt = "&nbsp;" . CHtml::textField('database', $data->databaseName);
		$stt .= CHtml::tag('br');
		$stt .= CHtml::tag('br');
		echo CHtml::tag('div', array('id' => 'database'), $stt);
		
		$i = 1;
		$str = '';
		foreach ($statements as $stmt => $statement) {
			$str .= "<tr border = '10'><td width='200px'><div id='my" . $i . "'>";
			$str .= CHtml::checkBox('checkbox',false,array(
				'id' => 'checkbox'.$i ,
				'onclick'=>'javascript: coba(checkbox'.$i.');'		
			));
			
			$str .= "<b>  Statement $stmt</b>";
			$str .= CHtml::tag('br');
			$str .= CHtml::textArea('statement' .$i, $statement, array('id' => 'statement' . $i, 'cols'=>30,'rows'=>5, 'readonly'=>"readonly"));
			$str .= CHtml::tag('br');
			$str .= CHtml::tag('br');
			$str .= CHtml::tag('br');
			$str .= "</div></td><td >";
			$str .= CHtml::tag('table', array('id' => 'vars' . $i));

			$str .= "</td>";
			$i++;
		}
		
		echo CHtml::tag('table', array(),  $str);
		echo $enableEditing;
		echo "  Enable Editing";
		echo CHtml::tag('br');
		echo $tarea;
	}
		
	public function autoGen($id) {
		$data = Query::model()->findByPk($id);	
		$statements = $data->loadStatements();
		
		$enableEditing = CHtml::checkBox('enable editing',false,array(
				'id' => 'enableediting',
				'name'=> 'aa',
				'onclick'=>'javascript: changeedit();'		
			));
		
		
		$tarea = CHtml::textArea('isiquery1','',array('id'=>'isiquery', 'cols'=>60,'rows'=>5, 'readonly'=>"readonly" ));
		$retval = '';
		$retval .= '<b>&nbsp; Database</b>';
		$stt = "&nbsp;" . CHtml::textField('database', $data->databaseName);
		$stt .= CHtml::tag('br');
		$stt .= CHtml::tag('br');
		$retval .= CHtml::tag('div', array('id' => 'database'), $stt);
		
		$i = 1;
		$str = '';
		foreach ($statements as $stmt => $statement) {
			$str .= "<tr border= '10'><td width ='200px'><div id='my " . $i . "'>";
			$str .= CHtml::checkBox('checkbox',false,array(
				'id' => 'checkbox'.$i ,
				'onclick'=>'javascript: coba(checkbox'.$i.');'		
			));
			
			$str .= "<b> Statement $stmt</b>";
			$str .= CHtml::tag('br');
			$str .= CHtml::textArea('statement' .$i, $statement, array('id' => 'statement' . $i, 'cols'=>30,'rows'=>5, 'readonly'=>"readonly"));
			$str .= CHtml::tag('br');
			$str .= CHtml::tag('br');
			$str .= CHtml::tag('br');
			$str .= "</div></td><td>";
			$str .= CHtml::tag('div', array('id' => 'vars' . $i));
			$str .= "</td></tr>";
			$i++;
		}
		$retval .= CHtml::tag('table', array('id'=>'vi2'), $str) . "</br>";
		$std = $enableEditing;
		$std .= '  Enable Editing';
		$std .= CHtml::tag('br');
		$std .= $tarea;
		$stx = CHtml::tag('table', array(), $std);
		$retval .= $stx;
		return $retval;
	}	
		
	public function actionTest() {
		// $dsn = 'sqlsrv:server=10.204.35.92;database=MPS';
		// $username = 'sa';
		// $password = 'm4nd1r1db';
		$dsn = 'sqlsrv:server=WIBI-PC;database=AdventureWorks';
		$username = '';
		$password = '';
		// $dsn = 'mysql:host=localhost;dbname=wdshop';
		// $username = 'root';
		
		// $sql = "select cardno, productid,  INTauthamount AS denom, renewalStatus, cardbalance, cardbalanceoncard , ModifiedOn, ModifiedBy from dbo.MPS_CardMaster with (nolock) where cardno in ('6032981019317189')";
		// $sql = "SELECT * FROM barang";
		$sql = "SELECT TOP 100 * FROM Production.Product; SELECT TOP 100 * FROM Person.Person";
		
		$con = new CDbConnection($dsn, $username, $password);
		$con->active = true;
		
		$cmd = $con->createCommand($sql);
		$data = $cmd->queryAll();
		
		$con->active = false;
		
		Yii::app()->user->setState('result', $data);
		$this->render('result', array('data' => $data));
	}
	

	public function actionDownload($type = 'xls') {
		if (($cmd = Yii::app()->user->getState('conn')) == null)
			throw new CHttpException(403, 'No query result found.');
		
		$data = $this->queryAll($cmd->connection, $cmd->text);
		if ($type === 'xls') {
			$this->generateExcel($data);
		}
		elseif ($type === 'txt') {
			$this->generateText($data);
		}
	}
	
	public function actionResult() {
		if ((($cmd = Yii::app()->user->getState('conn')) == null) && !isset($_POST['isiquery1']))
			throw new CHttpException(403, 'No query found.');
		
		if (isset($_POST['isiquery1'])) {
			if ($_POST['ExecForm']['connection'] === 'other') {
				$IPAddress = $_POST['IP'];
				$username = $_POST['username'];
				$password = $_POST['password'];
				$dbms = $_POST['dbms'];
			} else {
				$con = Connection::model()->findByPk($_POST['ExecForm']['connection']);
				$IPAddress = $con->IPAddress;
				$username = $con->username;
				$password = $con->password;
				$dbms = $con->dbms;
			}
			
			// $dsn = 'sqlsrv:server=10.204.35.92;database=MPS';
			// $username = 'sa';
			// $password = 'm4nd1r1db';
			
			$dsn = Connection::getDsn($dbms, $IPAddress, $_POST['database']);
			$username = $username;
			$password = $password;
			$dbCon = new CDbConnection($dsn, $username, $password);
			$cmd = $dbCon->createCommand($_POST['isiquery1']);
		}
		
		$error = '';
		$data = array();
		try {
			$data = $this->queryAll($cmd->connection, $cmd->text);
		} catch (Exception $e) {
			$error = 'Query failed. Please check the connection and/or the statements.';
		}
		Yii::app()->user->setState('conn', $cmd);
		$this->render('result', array('data' => $data, 'query' => $cmd->text, 'error' => $error));
	}
	
		/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}

	private function generateExcel($data) {
		Yii::import('application.extensions.phpexcel.JPhpExcel');
	    $xls = new JPhpExcel('UTF-8', false, 'mandiri');
		
		$j = 1;
		foreach ($data as $datum) {
			$tb = array();
			if (count($data) > 1)
				$tb[] = array('Statement ' . $j++);
				
			$header = array();
			
			foreach ($datum[0] as $property => $value) {
				$headr[] = $property;
			}
			
			$tb[] = $headr;
			foreach ($datum as $i) {
				$tb[] = $i;
			}
			
			if (count($data) > 1)
				$tb[] = array('');
			
			$xls->addArray($tb);
		}
		
	    $xls->generateXML($this->filename);
	}
	
	private function generateText($data) {
		$th = new TextHelper;
		
		header("Content-Disposition: attachment; filename=\"" . $this->filename . ".txt\"");
		header("Content-Type: application/force-download");
		header("Connection: close");
		
		$i = 1;
		foreach ($data as $datum) {
			if (count($data) > 1)
				echo "Statement " . $i++ . "\n";
				
			if (count($datum) <= $th->rowsPerWrite) {
				echo $th->toText($datum);
			} else {
				$lengths = $th->maxLengths($datum);
				
				echo $th->headerText($datum, $lengths);
				$iter = ceil(count($datum) / $th->rowsPerWrite);
				for ($i = 0; $i < $iter; $i++) {
					echo $th->partText($datum, $i, $lengths);
				}
			}
			
			if (count($data) > 1)
				echo "\n\n";
		}
	}
	
	private function queryAll($conn, $text) {
		$data = array();
		
		$conn->active = true;
		
		$queries = explode(";", $text);
		for ($i = 0; $i < count($queries) - 1; $i++) {
			$cmd = $conn->createCommand($queries[$i]);
			$data[] = $cmd->queryAll();
		}
		
		$conn->active = false;
		return $data;
	}
	
	private function getDsn($dbms) {
		
	}
}

?>