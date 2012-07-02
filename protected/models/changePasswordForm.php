<?php

/*change Password Form class*/


class ChangePasswordForm extends CFormModel
{
	public $user;
	public $oldpwd;
	public $newpwd;
	public $newpwd_repeat;
	
	/**rules**/
	public function rules()
	{
		return array(
			array('oldpwd, newpwd, newpwd_repeat','required'),
			array('oldpwd','checking'),
			array('newpwd', 'compare'),
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
			'newpwd_repeat' => 'Repeat Password',
		);
	}
	
	/**
	 * Authenticates the password.
	 */
	 
	public function checking($attribute,$params)
	{
		$old= md5($this->oldpwd);
		print_r($old);
		print_r('    ');
	
		$useridd = $this->user->userID;
	//	print_r($useridd);
		$username = $this->user->username;
	//	print_r($username);
		$user = User::model()->findByAttributes(array('username'=>$username, 'password'=>$old));
		
		
		if($user===null){
			$this->addError($attribute, 'Invalid password');
		}
		
		
	}	
	
	
/*
    $record=User::model()->findByAttributes(array('password'=>$this->attributes['oldpwd']));

	
    if($record===null){
        $this->addError($attribute, 'Invalid password');
    }
	*/

			
	
	

}