<?php

class UserController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'admin - view, changePassword',
			'accessID + view, edit',
			'selfAdmin + edit, delete',
			'assign + assignQuery, assignConnection'
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('deny',  // deny all users
				'users'=>array('?'),
			)
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id = null) {
		if ($id == null)
			$id = Yii::app()->user->id;
		
		$this->render('view', array(
			'model' => $this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionAdd()
	{
		$model = new User;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['User'])) {
			$model->attributes=$_POST['User'];
			if ($model->save())
				$this->redirect(array('view', 'id' => $model->userID));
		}

		$model->password = '';
		$this->render('add',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionEdit($id = null) {
		if ($id == null)
			$id = Yii::app()->user->id;
			
		$model = $this->loadModel($id);
		$password = $model->password;
		
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		
		if(isset($_POST['User'])) {
			$model->attributes = $_POST['User'];
			$model->password = $password;
			$model->password_repeat = $model->password;
			if ($model->save())
				$this->redirect(array('view','id'=>$model->userID));
		}

		$this->render('edit',array(
			'model'=>$model,
		));
	}
	
	/*action change password*/
	public function actionChangePassword($id) {
		$model = new ChangePasswordForm;
		$user = $this->loadModel($id);
		$model->user = User::model()->findByPk($id);
		
		if(isset($_POST['ChangePasswordForm'])) {
			$model->attributes = $_POST['ChangePasswordForm'];
			$user->password = $user->encrypt($model->newpwd);
			
			if (($model->validate()) && ($user->save(false)))
				$this->redirect(array('view', 'id' => $user->userID));
		}
		
		$this->render('changePassword', array('model' => $model));
	}
	

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		if(Yii::app()->request->isPostRequest) {
			if ($id == Yii::app()->user->getId()) {
				throw new CHttpException(403, "You are not authorized to do this action.");
			}
			
			// we only allow deletion via POST request
			$this->loadModel($id)->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('manage'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request.');
	}

	/**
	 * Lists all models.
	 */
	/*public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('User');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}*/

	/**
	 * Manages all models.
	 */
	public function actionManage()
	{
		$model=new User('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['User'])) {
			$model->attributes=$_GET['User'];
			echo $model->userID;
		}

		$this->render('manage',array(
			'model'=>$model,
		));
	}

	public function actionAssignQuery($id) {
		$user = $this->loadModel($id);
		
		$model = new AssignQueryForm;
		
		if(isset($_POST['AssignQueryForm'])) {
			$model->attributes = $_POST['AssignQueryForm'];
			if ($model->validate()) {
				try {
					$user->assignQuery($model->queryID);
					Yii::app()->user->setFlash('success', 'Query <b>' . Query::model()->findByPk($model->queryID)->title . '</b> has been assigned to <b>' . $user->username . '</b>');
				} catch (Exception $e) {
					Yii::app()->user->setFlash('error', 'Query <b>' . Query::model()->findByPk($model->queryID)->title . '</b> has already been assigned to <b>' . $user->username . '</b>');
				}
			}
		}
		
		$this->render('assignQuery', array('user' => $user, 'model' => $model));
	}
	
	public function actionAssignConnection($id) {
		$user = $this->loadModel($id);
		
		$model = new AssignConnectionForm;
		
		if(isset($_POST['AssignConnectionForm'])) {
			$model->attributes = $_POST['AssignConnectionForm'];
			if ($model->validate()) {
				try {
					$user->assignConnection($model->connectionID);
					Yii::app()->user->setFlash('success', 'Connection <b>' . Connection::model()->findByPk($model->connectionID)->name . '</b> has been assigned to <b>' . $user->username . '</b>');
				} catch (Exception $e) {
					Yii::app()->user->setFlash('error', 'Connection <b>' . Connection::model()->findByPk($model->connectionID)->name . '</b> has already been assigned to <b>' . $user->username . '</b>');
				}
			}
		}
		
		$this->render('assignConnection', array('user' => $user, 'model' => $model));
	}
	
	public function actionAssignQueryAll() {
		$model = new AssignQueryAllForm;
		
		if (!empty($_POST)) {
			User::assignQueries($_POST['user'], $_POST['query']);
			$users = User::model()->findAllByAttributes(array(
				'userID' => $_POST['user']
			));
			$conns = Query::model()->findAllByAttributes(array(
				'queryID' => $_POST['query']
			));
			
			$msg = 'Users <br />';
			foreach ($users as $user) {
				$msg .= '<strong>' . $user->username . '</strong>, ';
			}
			$msg = substr($msg, 0, strlen($msg) - 2);
			
			$msg .= '<br />have been assigned queries <br />';
			
			foreach ($conns as $conn) {
				$msg .= '<strong>' . $conn->title . '</strong>, ';
			}
			$msg = substr($msg, 0, strlen($msg) - 2);
			
			Yii::app()->user->setFlash('success', $msg);
		}
		
		$this->render('assignQueryAll', array('model' => $model));
	}
	
	public function actionAssignConnAll() {
		$model = new AssignConnAllForm;
		
		if (!empty($_POST)) {
			User::assignConnections($_POST['user'], $_POST['conn']);
			$users = User::model()->findAllByAttributes(array(
				'userID' => $_POST['user']
			));
			$conns = Connection::model()->findAllByAttributes(array(
				'connectionID' => $_POST['conn']
			));
			
			$msg = 'Users <br />';
			foreach ($users as $user) {
				$msg .= '<strong>' . $user->username . '</strong>, ';
			}
			$msg = substr($msg, 0, strlen($msg) - 2);
			
			$msg .= '<br />have been assigned connections <br />';
			
			foreach ($conns as $conn) {
				$msg .= '<strong>' . $conn->name . '</strong>, ';
			}
			$msg = substr($msg, 0, strlen($msg) - 2);
			
			Yii::app()->user->setFlash('success', $msg);
		}
		
		$this->render('assignConnAll', array('model' => $model));
	}
	
	public function actionRemoveQueryAll() {
		$model = new AssignQueryAllForm;
		
		if (!empty($_POST)) {
			User::removeQueries($_POST['user'], $_POST['query']);
			$users = User::model()->findAllByAttributes(array(
				'userID' => $_POST['user']
			));
			$conns = Query::model()->findAllByAttributes(array(
				'queryID' => $_POST['query']
			));
			
			$msg = 'Users <br />';
			foreach ($users as $user) {
				$msg .= '<strong>' . $user->username . '</strong>, ';
			}
			$msg = substr($msg, 0, strlen($msg) - 2);
			
			$msg .= '<br />have been unassigned queries <br />';
			
			foreach ($conns as $conn) {
				$msg .= '<strong>' . $conn->title . '</strong>, ';
			}
			$msg = substr($msg, 0, strlen($msg) - 2);
			
			Yii::app()->user->setFlash('success', $msg);
		}
		
		$this->render('removeQueryAll', array('model' => $model));
	}
	
	public function actionRemoveConnAll() {
		$model = new AssignConnAllForm;
		
		if (!empty($_POST)) {
			User::removeConnections($_POST['user'], $_POST['conn']);
			$users = User::model()->findAllByAttributes(array(
				'userID' => $_POST['user']
			));
			$conns = Connection::model()->findAllByAttributes(array(
				'connectionID' => $_POST['conn']
			));
			
			$msg = 'Users <br />';
			foreach ($users as $user) {
				$msg .= '<strong>' . $user->username . '</strong>, ';
			}
			$msg = substr($msg, 0, strlen($msg) - 2);
			
			$msg .= '<br />have been unassigned connections <br />';
			
			foreach ($conns as $conn) {
				$msg .= '<strong>' . $conn->name . '</strong>, ';
			}
			$msg = substr($msg, 0, strlen($msg) - 2);
			
			Yii::app()->user->setFlash('success', $msg);
		}
		
		$this->render('removeConnAll', array('model' => $model));
	}
	
	public function actionRemoveQuery($id, $qid) {
		if (!isset($_GET['ajax']) || !Yii::app()->request->isPostRequest)
			throw new CHttpException(400, 'Bad request.');
		
		$user = $this->loadModel($id);
			
		$user->removeQuery($qid);
	}
	
	public function actionRemoveConnection($id, $cid) {
		if (!isset($_GET['ajax']) || !Yii::app()->request->isPostRequest)
			throw new CHttpException(400, 'Bad request.');
		
		$user = $this->loadModel($id);
			
		$user->removeConnection($cid);
	}
	
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=User::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='user-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	public function filterSelfAdmin($filterChain) {
		if (!isset($_GET['id'])) {
			$filterChain->run();
			return;
		}
		
		$user = $this->loadModel($_GET['id']);
		
		if (($user->role == User::ROLE_ADMINISTRATOR) && (Yii::app()->user->getId() != $user->userID)) {
			if (Yii::app()->controller->action->id == 'edit')
				throw new CHttpException(403, "You are not authorized to view this page.");
			else if (Yii::app()->controller->action->id == 'delete')
				throw new CHttpException(403, "You are not authorized to do this action.");
		}
		
		$filterChain->run();
	}
	
	/* Checks whether the current user can access the User specified by $id */
	public function filterAccessID($filterChain) {
		if ($this->isAdmin()) {
			$filterChain->run();
			return;
		}
		
		if (!isset($_GET['id'])) {
			$filterChain->run();
			return;
		}
		
		if ($this->userID != $_GET['id'])
			throw new CHttpException(403, 'You are not authorized to view this page.');
		
		$filterChain->run();
	}
	
	public function filterAssign($filterChain) {
		if (!isset($_GET['id']))
			throw new CHttpException(404, 'The requested page does not exist.');
		
		if (!$this->isAdmin())
			throw new CHttpException(403, 'You are not authorized to view this page.');
		
		$user = $this->loadModel($_GET['id']);
		if ($user->role == User::ROLE_ADMINISTRATOR)
			throw new CHttpException(404, 'The requested page does not exist.');
		
		$filterChain->run();
	}
}
