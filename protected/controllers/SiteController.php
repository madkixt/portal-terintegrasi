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
		$this->render('exec',array('model' => $model,
		'statements' => $statements
		));
	}	
	
	public function actionDinamik()
	{
		$data = Query::model()->findByPk($_POST['queryID']);	
		$statements = $data->loadStatements();
		
		$enableEditing = CHtml::checkBox('enable editing',false,array(
				'id' => 'enableediting',
				'name'=> 'aa',
				'onclick'=>'javascript: changeedit();'		
			));
		
		
		$tarea = CHtml::textArea('isiquery1','',array('id'=>'isiquery', 'cols'=>60,'rows'=>5, 'readonly'=>"readonly" ));
		echo "database";
		$stt = CHtml::textField('database', $data->databaseName);
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
			
			$str .= '<b>  Statement</b>';
			$str .= $stmt;
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

}
 
?>


  <script type="text/javascript">
    function coba(chk){
		var id = chk.id.substr(chk.id.length-1);
		if (chk.checked) {
			splitQuery(id);
		} else {
			$('#vars' + id).html('');
		}
		setText();
	}
	
	function setText() {
		var txt = '';
		for(var i =1; i <= $('#campur textarea').length; i++) {
			if  ($('#checkbox' + i).is(':checked')) {
				var arr = parseVariable($('#statement' + i).text());
				for (varname in arr) {
					var x = $('input[name="vari'+i+ varname+'"]');
					arr[varname] = x[0].value;
				}
				txt += assignVariable($('textarea[name="statement' + i + '"]').text(), arr) + ";\n";
			} 
		}
		$('#isiquery').text(txt);
	}
	
	function splitQuery(i) {
		if  ($('#checkbox' + i).is(':checked')) {
			str = $('textarea[name="statement' + i + '"]').text();
			var variables = parseVariable(str);
			for (varname in variables) {
				$('#vars' + i).html($('#vars' + i).html() + "<tr><td width='30px'>"+varname + "</td><td><input name='vari"+i+ varname + "' class= 'required' type='text' onchange='setText()' /></td></tr>");
			}
		}
	}
	
	function parseVariable(text) {
		var ret = new Array();
		var txt = text;
	
		var idxQ = -1;
		while ((idxQ = txt.indexOf('?', idxQ + 1)) != -1) {
			var terminIdx = txt.substr(idxQ + 1).search(/\W/);
			var varname = '';
			if (terminIdx != -1)
				varname = txt.substr(idxQ + 1, terminIdx);
			else
				varname = txt.substr(idxQ + 1);
			ret[varname] = idxQ + 1;
		}
		
		return ret;
	}

	function printVariable(arr) {
		var str = '';
		for (i in arr)
			str += i + ': ' + arr[i] + "\n";
		return str;
	}

	function assignVariable(text, arr) {
		for (varname in arr) {
			if (arr[varname] != '')
				text = text.replace('?' + varname, "'" + arr[varname] + "'");
		}
		return text;
	}
	
	function changeedit() {
		if  ($('#enableediting').is(':checked')) {
			$('#isiquery').attr('readonly',false);
			$('input[name^="vari"]').attr('readonly', true);
		}
		else
		{
			$('#isiquery').attr('readonly',true);
			$('input[name^="vari"]').attr('readonly', false);
		}
	}
	
</script>
