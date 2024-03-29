<?php

/**
 * This is the model class for table "tbl_user".
 *
 * The followings are the available columns in table 'tbl_user':
 * @property integer $userID
 * @property integer $role
 * @property string $username
 * @property string $password
 * @property string $description
 * @property string $creationDate
 * @property string $modifiedDate
 * @property integer $createdBy
 * @property integer $lastModifiedBy
 *
 * The followings are the available model relations:
 * @property Connection[] $connections
 * @property Connection[] $connections1
 * @property Query[] $queries
 * @property Query[] $queries1
 * @property Query[] $queries2
 * @property User $createdBy0
 * @property User[] $users
 * @property User $lastModifiedBy0
 * @property User[] $users1
 * @property Connection[] $tblConnections
 * @property Query[] $tblQueries
 */
class User extends BaseEntity
{
	const ROLE_ADMINISTRATOR = 0;
	const ROLE_OPERATOR = 1;
	const ROLE_USER = 2;
	
	public $password_repeat;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return User the static model class
	 */
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName() {
		return 'tbl_user';
	}
	
	/*return array user atau admin*/
	public static function getUserRoles() {
		return array(
			self::ROLE_ADMINISTRATOR => 'Administrator',
			self::ROLE_OPERATOR => 'Operator',
			self::ROLE_USER => 'User',
		);
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('username, password', 'required'),
			array('username','unique'),
			array('role', 'numerical', 'integerOnly'=>true),
			array('username', 'length', 'max'=>20),
			array('password', 'length', 'max'=>32),
			array('password','compare'),
			array('description, password_repeat', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('userID, role, username, description, creationDate, modifiedDate, createdBy, lastModifiedBy', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'connections' => array(self::HAS_MANY, 'Connection', 'createdBy'),
			'connections1' => array(self::HAS_MANY, 'Connection', 'lastModifiedBy'),
			'queries' => array(self::HAS_MANY, 'Query', 'createdBy'),
			'queries1' => array(self::HAS_MANY, 'Query', 'lastModifiedBy'),
			'queries2' => array(self::HAS_MANY, 'Query', 'lastNotesEditor'),
			'createdBy0' => array(self::BELONGS_TO, 'User', 'createdBy'),
			'users' => array(self::HAS_MANY, 'User', 'createdBy'),
			'lastModifiedBy0' => array(self::BELONGS_TO, 'User', 'lastModifiedBy'),
			'users1' => array(self::HAS_MANY, 'User', 'lastModifiedBy'),
			'tblConnections' => array(self::MANY_MANY, 'Connection', 'tbl_user_connection(userID, connectionID)'),
			'tblQueries' => array(self::MANY_MANY, 'Query', 'tbl_user_query(userID, queryID)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'userID' => 'ID',
			'role' => 'Role',
			'username' => 'Username',
			'password' => 'Password',
			'description' => 'Description',
			'creationDate' => 'Creation Date',
			'modifiedDate' => 'Modified Date',
			'createdBy' => 'Created By',
			'lastModifiedBy' => 'Last Modified By',
		);
	}

	
	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search() {
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;
		$criteria->with = array('createdBy0', 'lastModifiedBy0');
		$criteria->together = true;

		$criteria->compare('t.userID',$this->userID);
		$criteria->compare('t.role', $this->array_search_ci($this->role, User::getUserRoles()));
		$criteria->compare('t.username',$this->username,true);
		$criteria->compare('t.description',$this->description,true);
		$criteria->compare('t.creationDate',$this->creationDate,true);
		$criteria->compare('t.modifiedDate',$this->modifiedDate,true);
		$criteria->compare('createdBy0.username', $this->createdBy, true);
		$criteria->compare('lastModifiedBy0.username', $this->lastModifiedBy, true);
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => false
		));
	}
	
	
	protected function afterValidate() {
		if ($this->isNewRecord)
			$this->password = $this->encrypt($this->password);
	}
	
	protected function afterSave()
	{
		if($this->hasEventHandler('onAfterSave'))
			$this->onAfterSave(new CEvent($this));
		
		if (($this->role == self::ROLE_ADMINISTRATOR) && $this->isNewRecord) {
			$this->adminAssignQueries();
			$this->adminAssignConnections();
		}
	}
	
	/* Assigns all existing Queries to the newly inserted admin */
	public function adminAssignQueries() {
		$queries = Query::model()->findAll();
		$cmd = Yii::app()->db->createCommand();
		$cmd->text = 'INSERT INTO tbl_user_query(userID, queryID) VALUES (:userID, :queryID)';
		$cmd->bindValue(':userID', $this->userID, PDO::PARAM_INT);
		
		foreach ($queries as $query) {
			$cmd->bindValue(':queryID', $query->queryID, PDO::PARAM_INT);
			$cmd->execute();
		}
	}
	
	/* Assigns all existing Connections to the newly inserted admin */
	public function adminAssignConnections() {
		$connections = Connection::model()->findAll();
		$cmd = Yii::app()->db->createCommand();
		$cmd->text = 'INSERT INTO tbl_user_connection(userID, connectionID) VALUES (:userID, :connectionID)';
		$cmd->bindValue(':userID', $this->userID, PDO::PARAM_INT);
		
		foreach ($connections as $connection) {
			$cmd->bindValue(':connectionID', $connection->connectionID, PDO::PARAM_INT);
			$cmd->execute();
		}
	}
	
	/* Assigns the Query specified by $queryID to this user */
	public function assignQuery($queryID) {
		Yii::app()->db->createCommand()->insert('tbl_user_query', array('userID' => $this->userID, 'queryID' => $queryID));
	}
	
	/* Assigns the Connection specified by $connectionID to this user */
	public function assignConnection($connectionID) {
		Yii::app()->db->createCommand()->insert('tbl_user_connection', array('userID' => $this->userID, 'connectionID' => $connectionID));
	}
	
	/* Encrypts password with md5 */
	public function encrypt($pwd) {
		return md5($pwd);
	}
	
	/* Returns whether another user can click this user's edit link */
	public function getEditClickable() {
		if (!Controller::isAdmin())
			return false;
			
		if ($this->userID == Yii::app()->user->getId())
			return true;
		
		if ($this->role == self::ROLE_ADMINISTRATOR)
			return false;
			
		return true;
	}
	
	/* Returns whether another user can click this user's delete link */
	public function getDeleteClickable() {
		return (($this->role != self::ROLE_ADMINISTRATOR) && Controller::isAdmin());
	}
	
	/* Returns whether another user can click this user's assign links */
	public function getAssignable() {
		return (($this->role != self::ROLE_ADMINISTRATOR) && Controller::isAdmin());
	}
	
	/* Returns queries not yet assigned to this user */
	public function getAssignableQueries() {
		return Yii::app()->db->createCommand()
			->select('queryID, title')
			->from('tbl_query')
			->where('queryID NOT IN (SELECT queryID FROM tbl_user_query WHERE userID = :userID)', array(':userID' => $this->userID))
			->order('title')
			->queryAll();
	}
	
	/* Returns connections not yet assigned to this user */
	public function getAssignableConnections() {
		return Yii::app()->db->createCommand()
			->select('connectionID, IPAddress, username')
			->from('tbl_connection')
			->where('connectionID NOT IN (SELECT connectionID FROM tbl_user_connection WHERE userID = :userID)', array(':userID' => $this->userID))
			->order(array('IPAddress', 'username'))
			->queryAll();
	}
	
	/* Unassigns the Query specified by $id from this user */
	public function removeQuery($id) {
		Yii::app()->db->createCommand()
			->delete('tbl_user_query', 'userID = :userID AND queryID = :queryID', array(':userID' => $this->userID, ':queryID' => $id));
	}
	
	/* Unassigns the Connection specified by $id from this user */
	public function removeConnection($id) {
		Yii::app()->db->createCommand()
			->delete('tbl_user_connection', 'userID = :userID AND connectionID = :connectionID', array(':userID' => $this->userID, ':connectionID' => $id));
	}
	
	public static function assignQueries($users, $queries) {
		$cmd = Yii::app()->db->createCommand('INSERT INTO tbl_user_query(userID, queryID) VALUES (:userID, :queryID)');
		foreach ($users as $userID) {
			$cmd->bindValue(':userID', $userID, PDO::PARAM_INT);
			foreach ($queries as $queryID) {
				$cmd->bindValue(':queryID', $queryID, PDO::PARAM_INT);
				try {
					$cmd->execute();
				} catch (Exception $e) { }
			}
		}
	}
	
	public static function removeQueries($users, $queries) {
		$cmd = Yii::app()->db->createCommand('DELETE FROM tbl_user_query WHERE userID = :userID AND queryID = :queryID');
		foreach ($users as $userID) {
			$cmd->bindValue(':userID', $userID, PDO::PARAM_INT);
			foreach ($queries as $queryID) {
				$cmd->bindValue(':queryID', $queryID, PDO::PARAM_INT);
				try {
					$cmd->execute();
				} catch (Exception $e) { }
			}
		}
	}
	
	public static function assignConnections($users, $conns) {
		$cmd = Yii::app()->db->createCommand('INSERT INTO tbl_user_connection(userID, connectionID) VALUES (:userID, :connectionID)');
		foreach ($users as $userID) {
			$cmd->bindValue(':userID', $userID, PDO::PARAM_INT);
			foreach ($conns as $connID) {
				$cmd->bindValue(':connectionID', $connID, PDO::PARAM_INT);
				try {
					$cmd->execute();
				} catch (Exception $e) { }
			}
		}
	}
	
	public static function removeConnections($users, $conns) {
		$cmd = Yii::app()->db->createCommand('DELETE FROM tbl_user_connection WHERE userID = :userID AND connectionID = :connectionID');
		foreach ($users as $userID) {
			$cmd->bindValue(':userID', $userID, PDO::PARAM_INT);
			foreach ($conns as $connID) {
				$cmd->bindValue(':connectionID', $connID, PDO::PARAM_INT);
				try {
					$cmd->execute();
				} catch (Exception $e) { }
			}
		}
	}
	
	public static function getAssignableUsers() {
		return User::model()->findAll(
			'role = :r1 OR role = :r2',
			array(
				':r1' => self::ROLE_OPERATOR,
				':r2' => self::ROLE_USER
			)
		);
	}
}
