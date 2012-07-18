<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/column1';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();
	
	public $defaultAction = 'view';
	
	public function filterAdmin($filterChain) {
		if (!$this->isAdmin()) {
			throw new CHttpException(403, "You are not authorized to view this page.");
		}
	
		$filterChain->run();
	}
	
	public function filterManage($filterChain) {
		if ($this->isAdmin() || !isset($_GET['id'])) {
			$filterChain->run();
			return;
		}
			
		if ($_GET['id'] != $this->userID)
			throw new CHttpException(403, 'You are not authorized to view this page.');
		
		$filterChain->run();
	}
	
	public function filterUser($filterChain) {
		if ($this->isUser())
			throw new CHttpException(403, "You are not authorized to view this page.");
		$filterChain->run();
	}
	
	public static function getUserID() {
		return Yii::app()->user->getId();
	}
	
	public static function userRole() {
		return Yii::app()->user->getState('role');
	}
	
	public static function isAdmin() {
		return (!Yii::app()->user->isGuest && (Yii::app()->user->getState('role') == User::ROLE_ADMINISTRATOR));
	}
	
	public static function isOperator() {
		return (!Yii::app()->user->isGuest && (Yii::app()->user->getState('role') == User::ROLE_OPERATOR));
	}
	
	public static function isUser() {
		return (!Yii::app()->user->isGuest && (Yii::app()->user->getState('role') == User::ROLE_USER));
	}
}