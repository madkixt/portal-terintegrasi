<?php

class UserTest extends CTestCase {
	public function testGetUserRoles() {
		$user = User::model()->findByAttributes(array('username' => 'wibi'));
		$roles = User::model()->userRoles;
		
		$this->assertEquals(2, count($roles));
		$this->assertEquals('User', $roles[User::ROLE_USER]);
		$this->assertEquals('Admin', $roles[User::ROLE_ADMIN]);
	}
}

?>