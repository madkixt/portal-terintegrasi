<?php

class SiteController extends Controller
{
	public $defaultAction = 'exec';
	public $filename = 'export';
	
	public function filters()
	{
		return array(
			'accessControl + exec', // perform access control for CRUD operations
			'dinamik + dinamik',
			'queryID + exec'
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
		// if(isset($_POST['ContactForm'])) {
			// $model->attributes=$_POST['ContactForm'];
			// if ($model->validate()) {
				Yii::import('application.extensions.phpgmailer.*');
				$mail = new PHPGmailer;
				$mail->Username = 'prmps.adm@gmail.com'; 
				$mail->Password = 'prmps.adm';
				$mail->From = 'prmps.adm@gmail.com'; 
				$mail->FromName = 'Test';
				$mail->Subject = 'Subject';
				$mail->AddAddress('okiriza.wibisono@gmail.com');
				$mail->Body = 'Hey buddy';
				$mail->Send();
				
				// $headers="From: {$model->email}\r\nReply-To: {$model->email}";
				// mail(Yii::app()->params['adminEmail'],$model->subject,$model->body,$headers);
				// Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				// $this->refresh();
			// }
		// }
		// $this->render('contact',array('model'=>$model));
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='exec-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		} 
		
		// collect user input data
		if(isset($_POST['ExecForm'])) {
			$model->attributes = $_POST['ExecForm'];
			if ($model->validate() && $model->exec())
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
	
	public function actionDinamik($id = null) {
		if (($id === null) && ($_POST['queryID'] == ''))
			return;
	
		if ($id === null)
			$id = $_POST['queryID'];
		
		$data = Query::model()->findByPk($id);
		if ($data === null)
			throw new CHttpException(404, "The requested page does not exist.");
		
		$statements = $data->loadStatements();
		$notes = $data->loadNotes();
		
		if (!$this->isUser()) {
			$enableEditing = CHtml::checkBox('enable editing',false,array(
					'id' => 'enableediting',
					'name'=> 'aa',
					'onclick'=>'javascript: changeedit();'		
				));
			
			$tarea = CHtml::textArea('isiquery1','', array(
				'id'=>'isiquery', 
				'cols'=>30,
				'rows'=>10,
				'readonly'=>"readonly"
			));
			echo "<b>&nbsp; Database</b>";
			$stt = "&nbsp;" . CHtml::textField('database', $data->databaseName);
			$stt .= CHtml::tag('br');
			$stt .= CHtml::tag('br');
			echo CHtml::tag('div', array('id' => 'database'), $stt);
			
			$i = 1;
			$str = '';
			foreach ($statements as $stmt => $statement) {
				$str .= "<tr border = '1' width = '590px' style ='max-width: 590px'><td width='380px'><div id='my" . $i . "'>";
				$str .= CHtml::checkBox('checkbox',false,array(
					'id' => 'checkbox'.$i ,
					'onclick'=>'javascript: coba(checkbox'.$i.');'		
				));
				
				$str .= "<b>  Statement $stmt</b>";
				$str .= CHtml::tag('br');
				$str .= CHtml::textArea('statement' .$i, $statement, array('id' => 'statement' . $i, 'cols'=>40,'rows'=>5, 'readonly'=>"readonly"));
				$str .= CHtml::tag('br');
				$str .= CHtml::tag('br');
				$str .= CHtml::tag('br');
				$str .= "</div></td><td width= '180px' style ='max-width: 180px'>";
				$str .= CHtml::tag('div', array('id' => 'vars' . $i));

				$str .= "</td>";
				$i++;
			}
			
			$str1 = CHtml::tag('table', array('id'=>'stts'),  $str);
			$str2 = CHtml::tag('td', array('id'=>'stts'),  $str1);
			
			$std =  $enableEditing;
			$std .= "  Enable Editing";
			$std .=  CHtml::tag('br');
			$std .=  $tarea;
			$std .= CHtml::tag('br');
			$std .= CHtml::submitButton('Execute',array('id'=> 'Exec'));
			$std1 = CHtml::tag('div', array('id' => 'enableedit'), $std);
			$std2 = CHtml::tag('td', array(
			
				'style' => 'vertical-align: top; right: 0px',
			), $std1);
			$str2 .=  $std2;
			$std3 = CHtml::tag('table', array('id' => 'gabung'), $str2);
			echo $std3;
			
			$shownote = CHtml::link('Show Notes', '#notes', array('id'=>'shownote', 'onclick'=>'javascript: shownote();'));
			$shownote .= "<br />";
			$shownote .= CHtml::textArea('textnotes', $data->notes, array(
				'id' => 'textnotes',
				'style' => 'opacity: 0;', 
				'cols'=>30,'rows'=>5, 'readonly'=>"readonly" 
			));
			echo CHtml::tag('div', array('id' => 'notes', 'width' => '300px'), $shownote);
		} else {
			$ret = '';
			
			$str = '';
			foreach ($notes as $i => $note) {
				$str .= "<tr border='1' width='530px' style='max-width: 530px'>
				<td width='370px' style='max-width: 370px'><div id='my" . $i . "' width='355px' style='max-width: 355px'>";
				$str .= CHtml::checkBox('checkbox', false, array(
					'id' => 'checkbox' . $i ,
					'onclick'=>'javascript: coba(checkbox' . $i . ');'
				));
				
				$str .= "<b>  Statement " . $i . "</b>";
				$str .= CHtml::tag('br');
				$str .= CHtml::textArea('statement' . $i, $statements[$i], array('id' => 'statement' . $i, 'cols' => 0, 'rows' => 0, 'style' => 'visibility: hidden; width: 0px; height: 0px'));
				$str .= CHtml::textArea('notes' . $i, $notes[$i], array('id' => 'notes' . $i, 'cols' => 40, 'rows' => 5, 'readonly' => "readonly"));
				$str .= "</div></td><td width='460px' style='max-width: 460px'>";
				$str .= CHtml::tag('div', array('id' => 'vars' . $i, 'width' => '440px'));

				$str .= "</td>";
				$i++;
			}
			
			$database= CHtml::textField('database', $data->databaseName, array('id' => 'db2','style' => 'visibility: hidden;'));
			echo $database;

			$tarea = CHtml::textArea('isiquery1','', array(
				'id'=>'isiquery',
				'cols' => 0, 'rows' => 0, 'style' => 'visibility: hidden; width: 0px; height: 0px'
			));
			echo $tarea;
			$notebtn = CHtml::link('Show Notes', '#notes', array('id'=>'shownote', 'onclick'=>'javascript: shownote();'));
			$notearea = CHtml::textArea('textnotes', $data->notes, array(
				'id' => 'textnotes',
				'style' => 'opacity: 0;', 
				'cols'=>30,'rows'=>5, 'readonly'=>"readonly")
			);
			$notediv = CHtml::tag('div', array('id' => 'notes'), $notebtn . "<br />" . $notearea);
			
			$btn = CHtml::submitButton('Execute',array('id'=> 'Exec'));
			
			$table = CHtml::tag('table', array(), $str);
			
			$ret = $table . $btn . "<br /><br />" . $notediv;
			echo $ret;
		}
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
		if ((($cmd = Yii::app()->user->getState('conn')) == null) && !isset($_POST['isiquery1'])) {
			throw new CHttpException(403, 'No query found.');
		}
		
		$error = '';
		$data = array();
		
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
			
			try {
				$dbCon = new CDbConnection($dsn, $username, $password);
				$cmd = $dbCon->createCommand($_POST['isiquery1']);
			} catch (Exception $e) {
				$error = 'Query failed. Please check the <strong>connection</strong>.';
				$this->render('result', array('data' => $data, 'query' => '', 'error' => $error));
				return;
			}
		}
		
		try {
			$data = $this->queryAll($cmd->connection, $cmd->text);
		} catch (Exception $e) {
			$error = 'Query failed. Please check the <strong>query syntax</strong>.';
			$this->render('result', array('data' => $data, 'query' => '', 'error' => $error));
			return;
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
		$rtrim = rtrim($text);
		if (substr($rtrim, strlen($rtrim) - 1, 1) !== ";")
			$text .= ";";
			
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
	
	public function filterQueryID($filterChain) {
		if ($this->isAdmin()) {
			$filterChain->run();
			return;
		}
		
		$id = null;
		if (isset($_POST['queryID']))
			$id = $_POST['queryID'];
		elseif (isset($_GET['id']))
			$id = $_GET['id'];
		
		if ($id === null) {
			$filterChain->run();
			return;
		}
		
		$user = User::model()->findByPk(Yii::app()->user->getId());
		
		$broken = false;
		foreach ($user->tblQueries as $query) {
			if ($query->queryID == $id) {
				$broken = true;
				break;
			}
		}
		
		if (!$broken)
			throw new CHttpException(403, "You are not authorized to view this page.");
		
		$filterChain->run();
	}
	
	public function actionTest() {
		throw new CHttpException(404, 'Page not found.');
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
	
	public function filterDinamik($filterChain) {
		if (!Yii::app()->request->isAjaxRequest) {
			$this->redirect(array('/site'));
			return;
		}
		
		$filterChain->run();
	}
}

?>