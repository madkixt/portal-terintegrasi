<?php

/*change Password Form class*/

class ChangePasswordForm extends CFormModel
{
	public $oldpwd;
	public $newpwd;
	public $repeatnew;
	
	/**rules**/
	public function rules()
	{
		return array(
<<<<<<< .mine
			array('oldpwd, newpwd, repeatnew','required'),
			array('oldpwd,newpwd,repeatnew','safe'),
			array('newpwd','compare'),
			array('oldpwd','checkOld'),
=======
			array('oldpwd, newpwd, repeatnew', 'required'),
			//array('oldpwd, newpwd, repeat_new','safe'),
			array('newpwd', 'compare'),
			//array('oldpwd','checkOld'),
>>>>>>> .r55
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'oldpwd' => 'Old Password',
			'newpwd' => 'New Password',
			'repeatnew' => 'Repeat Password',
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
<<<<<<< .mine
	
	
=======
>>>>>>> .r55
}