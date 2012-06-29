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

	public function checkOld($attribute,$params)
	{
		$record=User::model()->findByAttributes(array('newpwd'=>$this->attributes['oldpwd']));
		if($record===null){
			$this->addError($attribute, 'Invalid password');
		}
		else {
				$this->addError('oldpwd',"Invalid Password");
		}
	}
	
	

*/
}