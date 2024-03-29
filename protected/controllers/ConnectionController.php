<?php

class ConnectionController extends Controller
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
			'admin - manage, view',
			'manage + manage',
			'accessID + view'
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
			array('deny',  // deny all anonymous users
				'users'=>array('?'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionAdd()
	{
		$model=new Connection;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Connection']))
		{
			$model->attributes=$_POST['Connection'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->connectionID));
		}

		$this->render('add',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionEdit($id)
	{
		$model=$this->loadModel($id);
		
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Connection']))
		{
			$model->attributes = $_POST['Connection'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->connectionID));
		}

		$this->render('edit',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$this->loadModel($id)->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('manage'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all models.
	 */
	/*public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Connection');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}*/

	/**
	 * Manages all models.
	 */
	public function actionManage($id = null) {
		if ($id == null && !$this->isAdmin())
			$id = Yii::app()->user->id;
		
		$model = new Connection('search');
		$model->unsetAttributes();  // clear any default values
		
		if (isset($_GET['Connection']))
			$model->attributes=$_GET['Connection'];

		$username = null;
		if ($id !== null) {
			$user = User::model()->findByPk($id);
			if ($user === null)
				throw new CHttpException(404, "The requested page does not exist.");
			if ($user->role == User::ROLE_ADMINISTRATOR)
				throw new CHttpException(404, "The requested page does not exist.");
				
			$username = $user->username;
		}
			
		$this->render('manage',array(
			'model'=> $model,
			'id' => $id,
			'username' => $username,
			'template' => $this->getVisibleButtons($id)
		));
	}
	
	public function actionAssign($id) {
		$conn = $this->loadModel($id);
		
		$model = new AssignConnUserForm;
		if(isset($_POST['AssignConnUserForm'])) {
			$model->attributes = $_POST['AssignConnUserForm'];
			if ($model->validate()) {
				$user = User::model()->findByPk($model->userID);
				
				try {
					$user->assignConnection($id);
					Yii::app()->user->setFlash('success', 'Connection <b>' . $conn->name . '</b> has been assigned to <b>' . $user->username . '</b>');
				} catch (Exception $e) {
					Yii::app()->user->setFlash('error', 'Connection <b>' . $conn->name . '</b> has already been assigned to <b>' . $user->username . '</b>');
				}
			}
		}
		
		$this->render('assign', array('conn' => $conn, 'model' => $model));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Connection::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='connection-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	/* Checks whether the current user can access the Connection specified by $id */
	public function filterAccessID($filterChain) {
		if ($this->isAdmin()) {
			$filterChain->run();
			return;
		}
		
		if (!isset($_GET['id'])) {
			$filterChain->run();
			return;
		}
		
		if (!Yii::app()->db->createCommand()
			->select('*')
			->from('tbl_user_connection')
			->where('connectionID = :connectionID AND userID = :userID', array(':connectionID' => $_GET['id'], ':userID' => Yii::app()->user->id))
			->queryRow())
			throw new CHttpException(403, 'You are not authorized to view this page.');
		
		$filterChain->run();
	}
	
	/* Returns the template of visible buttons for the user specified by $id */
	public function getVisibleButtons($id) {
		if ($this->isAdmin()) {
			if (($id != null) && (($user = User::model()->findByPk($id)) != null) && ($user->role != User::ROLE_ADMINISTRATOR))
				return '{view} {update} {remove} {delete}';
			
			return '{view} {update} {delete}';
		}
		
		return '{view}';
	}
}
