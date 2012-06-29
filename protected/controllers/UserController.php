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
			'admin - view, edit',
			'admin2 + edit, delete'
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
			/*array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),*/
			array('deny',  // deny all users
				'users'=>array('?'),
			)
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		if (!Yii::app()->user->getState('admin') && ($id !== Yii::app()->user->getId()))
			throw new CHttpException(403, "You are not authorized to view this page.");
		
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
		$model=new User;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['User']))
		{
			$model->attributes=$_POST['User'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->userID));
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
		if (!Yii::app()->user->getState('admin') && ($id !== Yii::app()->user->getId()))
			throw new CHttpException(403, "You are not authorized to view this page.");
			
		$model=$this->loadModel($id);
		
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		
		if(isset($_POST['User']))
		{
			$model->attributes=$_POST['User'];
			$model->password_repeat = $model->password;
			if($model->save())
				$this->redirect(array('view','id'=>$model->userID));
		}

		$this->render('edit',array(
			'model'=>$model,
		));
	}
	
	/*action change password*/
	public function actionChangePassword()
	{
		$model = new ChangePasswordForm;
		$user = $this->loadModel(Yii::app()->user->getId());
		
		if(isset($_POST['ChangePasswordForm']))
		{
			$model->attributes = $_POST['ChangePasswordForm'];
			$user->password = $user->encrypt($model->newpwd);
			
			if ($user->save(false))
				$this->redirect(array('view', 'id' => $user->userID));
		}
		else {
			$this->render('changePassword', array('model' => $model));
		}
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
		$model=$this->loadModel($id);
		
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		$this->render('assignQuery',array(
			'model'=>$model,
		));
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
	
	public function filterAdmin2($filterChain) {
		$user = User::model()->findByPk($_GET['id']);
		
		if ($user->admin && (Yii::app()->user->getId() !== $user->userID)) {
			if (Yii::app()->controller->action->id == 'edit')
				throw new CHttpException(403, "You are not authorized to view this page.");
			else if (Yii::app()->controller->action->id == 'delete')
				throw new CHttpException(403, "You are not authorized to do this action.");
		}
		
		$filterChain->run();
	}
}
