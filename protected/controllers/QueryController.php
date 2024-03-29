<?php

class QueryController extends Controller
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
	public function actionAdd() {
		$model = new Query;
		$statements = null;
		$notes = null;
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Query'])) {
			$statements = $_POST['statement'];
			$notes = $_POST['notes'];
			$this->checkStatements($statements, $model);
				
			$model->attributes = $_POST['Query'];
			if ($model->validate(null, false) && $model->save(false)) {
				$model->insertStatements($statements, $notes);
				Yii::app()->user->setFlash('success', 'Query <b>' . $model->title . '</b> has been added.');
				$this->redirect(array('view', 'id' => $model->queryID));
			}
		}

		$this->render('add', array(
			'model' => $model,
			'statements' => $statements,
			'notes' => $notes
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionEdit($id)
	{
		$model = $this->loadModel($id);
		
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Query'])) {
			$statements = $_POST['statement'];
			$notes = $_POST['notes'];
			$this->checkStatements($statements, $model);
		
			$model->attributes=$_POST['Query'];
			if ($model->validate(null, false) && $model->save(false)) {
				$model->editStatements($statements, $notes);
				
				Yii::app()->user->setFlash('success', 'Query <b>' . $model->title . '</b> has been edited.');
				$this->redirect(array('view', 'id' => $model->queryID));
			}
		} else {
			$statements = $model->loadStatements();
			$notes = $model->loadNotes();
		}

		$this->render('edit', array(
			'model' => $model,
			'statements' => $statements,
			'notes' => $notes
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
			$model = $this->loadModel($id);
			$title = $model->title;
			$model->delete();
			
			Yii::app()->user->setFlash('success', 'Query <b>' . $title . '</b> has been deleted.');

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
		$dataProvider=new CActiveDataProvider('Query');
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
			
		$model = new Query('search');
		$model->unsetAttributes();  // clear any default values
		
		if (isset($_GET['Query']))
			$model->attributes = $_GET['Query'];
		
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
			'model' => $model,
			'id' => $id,
			'username' => $username,
			'template' => $this->getVisibleButtons($id)
		));
	}

	public function actionAssign($id = null) {
		// if ($id === null) {
			// Yii::import('UserController');
			// $control = new UserController;
			// $control->actionAssignQuery($id);
		// }
			
		$query = $this->loadModel($id);
		
		$model = new AssignQueryUserForm;
		if(isset($_POST['AssignQueryUserForm'])) {
			$model->attributes = $_POST['AssignQueryUserForm'];
			if ($model->validate()) {
				$user = User::model()->findByPk($model->userID);
				
				try {
					$user->assignQuery($id);
					Yii::app()->user->setFlash('success', 'Query <b>' . $query->title . '</b> has been assigned to <b>' . $user->username . '</b>');
				} catch (Exception $e) {
					Yii::app()->user->setFlash('error', 'Query <b>' . $query->title . '</b> has already been assigned to <b>' . $user->username . '</b>');
				}
			}
		}
		
		$this->render('assign', array('query' => $query, 'model' => $model));
	}
	
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model = Query::model()->findByPk($id);
		if ($model === null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='query-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	/* Checks whether the current user can access the query specified by $id */
	public function filterAccessID($filterChain) {
		if ($this->isAdmin() || !isset($_GET['id'])) {
			$filterChain->run();
			return;
		}
		
		if (!Yii::app()->db->createCommand()
			->select('*')
			->from('tbl_user_query')
			->where('queryID = :queryID AND userID = :userID', array(':queryID' => $_GET['id'], ':userID' => Yii::app()->user->id))
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
	
	/* Validates the query statements */
	public function checkStatements($statements, $model) {
		if ($this->isAnyEmpty($statements))
			$model->addError('', 'All statements must not be empty.');
		// if ($this->isAnyDuplicate($statements))
			// $model->addError('', 'Variable names in each statement must be unique.');
	}
	
	/* Checks whether the given statements are all empty */
	public function isAnyEmpty($statements) {
		foreach ($statements as $statement) {
			if (preg_replace('/\s+/', '', $statement) === '')
				return true;
		}
		
		return false;
	}
	
	/* Checks whether there are duplicate variable names in any statement */
	public function isAnyDuplicate($statements) {
		$arr = array();
		foreach ($statements as $statement) {
			$idxQ = -1;
			while (($idxQ = stripos($statement, '?', $idxQ + 1)) !== false) {
				$terminIdx = $this->preg_pos(substr($statement, $idxQ + 1), '\W');
				$varname = '';
				if ($terminIdx !== false)
					$varname = substr($statement, $idxQ + 1, $terminIdx);
				else
					$varname = substr($statement, $idxQ + 1);
				$arr[] = $varname;
			}
		}
		
		for ($i = 0; $i < count($arr); $i++) {
			for ($j = $i + 1; $j < count($arr); $j++) {
				if (strcasecmp($arr[$j], $arr[$i]) === 0)
					return true;
			}
		}
		return false;
	}
	
	private function preg_pos( $subject, $regex ) {
		if (preg_match('/\W/', $subject, $matches)) {
			return strpos($subject, $matches[0]);
		}
		
		return false; 
	}
}
