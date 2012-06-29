<?php

/*change Password Form class*/

class changePasswordForm extends CFormModel
{
	public $oldpwd;
	public $newpwd;
	public $repeatnew;
	
	/**rules**/
	public function rules()
	{
		return array(
			array('oldpwd, newpwd, repeatnew','required'),
			array('oldpwd','authenticate'),
			array('oldpwd,newpwd,repeat_new','safe'),
			array('newpwd','compare'),
			array('old_pwd','checkOld'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'oldpwd' => 'oldpwd',
			'newpwd' => 'newpwd',
			'repeatnew' => 'repeatnew',
		);
	}
	
	/**
	 * Authenticates the password.
	 */
	public function authenticate($attribute,$params)
	{
		if(!$this->hasErrors())
		{
			$this->_identity=new UserIdentity($this->oldpwd);
			if(!$this->_identity->authenticate())
				$this->addError('oldpwd','Incorrect username or password.');
		}
	}
	
	public function checkOld($attribute,$params)
	{
		$record=User::model()->findByAttributes(array('newpwd'=>$this)) 
	}
	
	public function changePassword()
	{
		$model=$this->loadModel(Yii::app()->user->id);
		if(isset($_POST['oldpwd'], $_POST['newpwd'], $_POST['repeatnew']))
		{
			if($model->validatePwd($_POST['oldpwd']))
			{
				$a = $_POST['newpwd'];
				$model->password=md5($model->$a);
				$model->save(false);
					$this->redirect(array('view','id'=>$model->id));
			}
		}
		
		$this->render('reset',array('model'=>$model));
	}
	
	protected function afterValidate()
	{
		$this->password = $this->encrypt($this->password);
	}
	
	public function encrypt($pwd) {
		return md5($pwd);
	}
}
