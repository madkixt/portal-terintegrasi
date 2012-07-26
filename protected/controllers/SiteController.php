<?php

class SiteController extends Controller
{
	public $defaultAction = 'exec';
	public $filename = 'export';
	
	const MAX_ROWS = 10000;
	const DISPLAYED_ROWS = 100;
	
	const WORKSHEET_LENGTH = 50000;
	
	public function filters() {
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
		$error = null;
		
		if(isset($_POST['ContactForm'])) {
			$model->attributes=$_POST['ContactForm'];
			if ($model->validate()) {
				Yii::import('application.extensions.phpgmailer.*');
				$mail = new PHPGmailer;
				$mail->Username = Yii::app()->params['adminEmail']; 
				$mail->Password = Yii::app()->params['adminEmailPass'];
				$mail->From = 'prmps.adm@gmail.com'; 
				$mail->FromName = 'Portal Reporting MPS';
				$mail->Subject = $model->subject;
				$mail->AddAddress('okiriza.wibisono@gmail.com');
				$mail->Body = "Portal Reporting Mandiri Prepaid System\n" . $model->name . " (" . $model->email . ")" . " wrote:\n\n" . $model->body;
				
				try {
					$mail->Send();
					Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
					$this->refresh();
				} catch (Exception $e) {
					$error = "Mail not sent. For the time, please contact us at <strong>prmps.adm@gmail.com</strong>. We are sorry for this incovenience.";
				}
			}
		}
		
		$this->render('contact',array('model'=>$model, 'error' => $error));
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
				$str .= "</div></td><td width= '180px' style ='max-width: 180px; vertical-align: top'>";
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
				$str .= "<tr border='1' width='530px' style='max-width: 530px'>";
				$str .= "<td width='120px' style='max-width: 120px; vertical-align: top'>";
				$str .= CHtml::tag('div', array('id' => 'vars' . $i, 'width' => '120px', 'style' => 'vertical-align: top; padding-top: 0px'));
				echo "<script type='text/javascript'>splitQuery($i);</script>";
				$str .= "</td>";
				
				$str .= "<td width='370px' style='max-width: 370px'><div id='my" . $i . "' width='355px' style='max-width: 355px'>";
				$str .= CHtml::checkBox('checkbox', true, array(
					'id' => 'checkbox' . $i ,
					'checked' => 'checked',
					'style' => 'visibility: hidden;' , 
					'onchange'=>'javascript: coba(checkbox' . $i . ');'
				));
				
				$str .= "<b>Notes</b>";
				$str .= CHtml::tag('br');
				$str .= CHtml::textArea('statement' . $i, $statements[$i], array('id' => 'statement' . $i, 'cols' => 0, 'rows' => 0, 'style' => 'visibility: hidden; width: 0px; height: 0px'));
				$str .=CHtml::textArea('Notes' . $i, $notes[$i], array('id' => 'notes' . $i, 'cols' => 40, 'rows' => 10, ));
				
				
				$str .= "</div></td>";
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
			echo '<script type="text/javascript">setText();</script>';
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
	
	public function actionDownload($type) {
		if (($cmd = Yii::app()->user->getState('conn')) == null)
			throw new CHttpException(403, 'No query result found.');
		
		$queries = $this->getQueries($cmd->text);
		$cmd->connection->active = true;
		
		if ($type === 'xls') {
			$this->generateExcel($cmd, $queries);
		} elseif ($type === 'mdb') {
			$this->generateAccess($cmd, $queries);
		} elseif ($type === 'txt') {
			$this->generateText($data);
		}
		
		$cmd->connection->active = false;
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
			$data = $this->queryAll($cmd->connection, $cmd->text, $dbms);
		} catch (Exception $e) {
			echo $e;
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

	private function generateExcel($cmd, $queries) {
		Yii::import('application.extensions.phpexcel.JPhpExcel');
	    $xls = new JPhpExcel('UTF-8', false, 'mandiri');
		
		header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
        header("Content-Disposition: inline; filename=\"" . $this->filename . ".xls\"");

        echo stripslashes (sprintf("<?xml version=\"1.0\" encoding=\"%s\"?\>\n<Workbook xmlns=\"urn:schemas-microsoft-com:office:spreadsheet\" xmlns:x=\"urn:schemas-microsoft-com:office:excel\" xmlns:ss=\"urn:schemas-microsoft-com:office:spreadsheet\" xmlns:html=\"http://www.w3.org/TR/REC-html40\">", "UTF-8"));
		
		$j = 1;		// Statement count
		foreach ($queries as $query) {
			$stop = false;
			$offset = 0;
			$rowPrinted = 0;
			$sheet = 1;
			
			while (!$stop) {
				echo $rowPrinted ."<br />";
				$data = $this->query($cmd->connection, $query, 0, self::MAX_ROWS, $offset);
				$n = count($data);
				
				if ($n === 0)
					break;
				
				if ($n < self::MAX_ROWS) {
					$stop = true;
				}
				
				$xls->setArray(array());
				if ($rowPrinted === 0) {
					if ($this->isUser())
						echo "\n<Worksheet ss:Name=\"" . $j . ". Rows " . (($sheet - 1)*self::WORKSHEET_LENGTH + 1) . "-" . ($sheet*self::WORKSHEET_LENGTH) . "\">\n<Table>\n";
					else
						echo "\n<Worksheet ss:Name=\"Rows " . (($sheet - 1)*self::WORKSHEET_LENGTH + 1) . "-" . ($sheet*self::WORKSHEET_LENGTH) . "\">\n<Table>\n";
						
					$header = array();
					foreach ($data[0] as $property => $value) {
						$header[] = $property;
					}
					$xls->addRow($header);
				}
				
				foreach ($data as $datum)
					$xls->addRow($datum);
				
				foreach ($xls->getLines() as $line)
					echo $line;
						
				$rowPrinted += $n;
				if ($rowPrinted === self::WORKSHEET_LENGTH || $stop) {
					echo "</Table>\n</Worksheet>";
					$rowPrinted = 0;
					$sheet++;
				}
				
				$offset += self::MAX_ROWS;
			}
			
			$j++;
		}
		
		echo "</Workbook>";
	}
	
	private function generateAccess($cmd, $queries) {
	
	}
	
	private function excelTest() {
		if (($cmd = Yii::app()->user->getState('conn')) == null)
			throw new CHttpException(403, 'No query result found.');
		
		$text = $cmd->text;
		$rtrim = rtrim($text);
		if (substr($rtrim, strlen($rtrim) - 1, 1) !== ';')
			$text .= ';';
		
		$queries = explode(";", $text);
		$conn = $cmd->connection;
		$conn->active = true;
		
		for ($i = 0; $i < count($queries) - 1; $i++) {
			$stop = false;
			$offset = 0;
			
			while (!$stop) {
				$data = $this->query($conn, $queries[$i], 0, self::MAX_ROWS, $offset);
				$n = count($data);
				
				if ($n === 0)
					break;
					
				if ($n < self::MAX_ROWS) {
					$stop = true;
				}
				
				echo ($offset + 1) . "-" . ($offset + $n) . "<br />";
				foreach ($data as $row)
					echo $row['FirstName'] . " " . $row['MiddleName'] . " " . $row['LastName'];
				echo "<br /><br />";
				$offset += self::MAX_ROWS;
			}
		}
		
		$conn->active = false;
	}
	
	private function generateText($data) {
		$th = new TextHelper;
		
		header("Content-Disposition: attachment; filename=\"" . $this->filename . ".txt\"");
		header("Content-Type: application/force-download");
		header("Connection: close");
		
		$j = 1;
		foreach ($data as $datum) {
			if ((count($data) > 1) && !$this->isUser())
				echo "Statement " . $j++ . "\r\n";
			
			if (count($datum) === 0)
				continue;
			
			if (count($datum) <= $th->rowsPerWrite) {
				echo $th->toText($datum);
			} else {
				$lengths = $th->maxLengths($datum);
				
				echo $th->headerText($datum, $lengths);
				$iter = ceil(count($datum) / $th->rowsPerWrite);
				for ($i = 0; $i < $iter; $i++) {
					echo $th->partText($datum, $i, $lengths);
				}
				echo $th->line($lengths);
			}
			
			if (count($data) > 1)
				echo "\r\n\r\n";
		}
	}
	
	private function queryAll($conn, $text, $dbms) {
		$rtrim = rtrim($text);
		if (substr($rtrim, strlen($rtrim) - 1, 1) !== ';')
			$text .= ';';
		
		$queries = explode(";", $text);
		$data = array();
		for ($i = 0; $i < count($queries) - 1; $i++) {
			$data[$i] = $this->query($conn, $queries[$i], $dbms, self::DISPLAYED_ROWS, 0);
		}
		
		return $data;
	}
	
	private function query($conn, $query, $dbms, $count = self::MAX_ROWS, $offset = 0) {
		$data = $conn->createCommand($this->limitResult($query, $dbms, $count, $offset))->queryAll();
		
		for ($i = 0; $i < count($data); $i++) {
			unset($data[$i]['RowNumber']);
			unset($data[$i]['__dummy__']);
		}
		
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
	
	private function limitResult($query, $dbms = Connection::DBMS_MSSQL, $count = 100, $offset = 0) {
		if ($dbms === null)
			return $query;
		
		switch ($dbms) {
			case Connection::DBMS_MSSQL:
				// $query = "WITH __dummy__ AS (SELECT __xx__ = '') " .
					// "SELECT * FROM (SELECT ROW_NUMBER() OVER (ORDER BY __xx__) AS RowNumber, __original__.* FROM ".
					// "(" . $query . ") AS __original__, __dummy__) AS __limited__ ".
					// "WHERE RowNumber BETWEEN " . ($offset + 1) . " AND " . ($offset + $count);
				 $query = "SELECT * FROM (SELECT ROW_NUMBER() OVER (ORDER BY __dummy__) AS RowNumber, * FROM " .
					"(SELECT *, __dummy__ = '' FROM (" . $query . ") AS __original__) AS __inner__) AS __outer__ ".
					"WHERE RowNumber BETWEEN " . ($offset + 1) . " AND " . ($offset + $count);
				break;
			case Connection::DBMS_MYSQL:
				$query .= " LIMIT 0, 100";
				break;
		}
		
		return $query;
	}
	
	public function actionAccessDel() {
		$file = 'D:/Downloads/Database21.accdb';
		// if (file_exists($file)) {
			// header('Content-Description: File Transfer');
			// header('Content-Type: application/octet-stream');
			// header('Content-Disposition: attachment; filename=' . basename($file));
			// header('Content-Transfer-Encoding: binary');
			// header('Expires: 0');
			// header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			// header('Pragma: public');
			// header('Content-Length: ' . filesize($file));
			// ob_clean();
			// flush();
			// readfile($file);
			// exit;
		// }
		unlink($file);
	}
	
	private function getQueries($text) {
		$rtrim = rtrim($text);
		if (substr($rtrim, strlen($rtrim) - 1, 1) !== ';')
			$text .= ';';
		
		$queries = explode(";", $text);
		unset($queries[count($queries) - 1]);
		return $queries;
	}
}

?>