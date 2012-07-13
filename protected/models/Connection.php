<?php

/**
 * This is the model class for table "tbl_connection".
 *
 * The followings are the available columns in table 'tbl_connection':
 * @property integer $connectionID
 * @property string $IPAddress
 * @property integer $dbms
 * @property string $username
 * @property string $password
 * @property string $serverName
 * @property string $description
 * @property string $creationDate
 * @property string $modifiedDate
 * @property integer $createdBy
 * @property integer $lastModifiedBy
 *
 * The followings are the available model relations:
 * @property User $createdBy0
 * @property User $lastModifiedBy0
 * @property User[] $tblUsers
 */
class Connection extends BaseEntity
{
	const DBMS_MSSQL = 0;
	const DBMS_MYSQL = 1;

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Connection the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_connection';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('IPAddress, dbms', 'required'),
			array('serverName, username', 'length', 'max'=>20),
			array('IPAddress', 'length', 'max'=>15),
			array('password', 'length', 'max'=>32),
			array('dbms', 'numerical', 'integerOnly' => true),
			array('description', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('connectionID, serverName, IPAddress, username, dbms, description, creationDate, modifiedDate, createdBy, lastModifiedBy', 'safe', 'on'=>'search'),
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
			'createdBy0' => array(self::BELONGS_TO, 'User', 'createdBy'),
			'lastModifiedBy0' => array(self::BELONGS_TO, 'User', 'lastModifiedBy'),
			'tblUsers' => array(self::MANY_MANY, 'User', 'tbl_user_connection(connectionID, userID)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'connectionID' => 'ID',
			'IPAddress' => 'IP Address',
			'dbms' => 'DBMS',
			'username' => 'Username',
			'password' => 'Password',
			'serverName' => 'Server Name',
			'description' => 'Description',
			'creationDate' => 'Creation Date',
			'modifiedDate' => 'Modified Date',
			'createdBy' => 'Created By',
			'lastModifiedBy' => 'Last Modified By',
			'password_repeat' => 'Repeat Password'
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search($id = null)
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;
		$criteria->with = array('createdBy0', 'lastModifiedBy0');
		
		if ($id != null) {
			$criteria->with['tblUsers'] = array(
				'condition' => 'tblUsers.userID = :userID',
				'params' => array(
					':userID' => $id
				)
			);
		}
		
		$criteria->together = true;

		$criteria->compare('t.connectionID', $this->connectionID);
		$criteria->compare('t.serverName', $this->serverName,true);
		$criteria->compare('t.IPAddress', $this->IPAddress,true);
		$criteria->compare('t.username', $this->username,true);
		$criteria->compare('t.dbms', $this->array_search_ci($this->dbms, Connection::getDbmsOptions()));
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
	
	public function getName() {
		if ($this->username === '')
			return $this->IPAddress;
			
		return $this->IPAddress . ':' . $this->username;
	}
	
	protected function afterSave()
	{
		if($this->hasEventHandler('onAfterSave'))
			$this->onAfterSave(new CEvent($this));
		
		if ($this->isNewRecord) {
			if (Yii::app()->user->getState('admin'))
				$this->insertAdmin();
			else {
				$cmd = Yii::app()->db->createCommand();
				$cmd->insert('tbl_user_connection', array('userID' => Yii::app()->user->getID(), 'connectionID' => $this->connectionID));
			}
		}
	}
	
	/* Assigns the newly inserted Connection to all admins */
	private function insertAdmin() {
		$admins = User::model()->findAllByAttributes(array(
			'admin' => 1
		));
		
		$cmd = Yii::app()->db->createCommand();
		$cmd->text = 'INSERT INTO tbl_user_connection(userID, connectionID) VALUES (:userID, :connectionID)';
		$cmd->bindValue(':connectionID', $this->connectionID, PDO::PARAM_INT);
		
		foreach ($admins as $admin) {
			$cmd->bindValue(':userID', $admin->userID, PDO::PARAM_INT);
			$cmd->execute();
		}
	}
	
	public static function getDbmsOptions() {
		return array(
			self::DBMS_MSSQL => 'Microsoft SQL Server',
			self::DBMS_MYSQL => 'MySQL'
		);
	}
	
	public static function getDsn($dbms, $server, $dbname) {
		switch ($dbms) {
			case self::DBMS_MSSQL:
				return "sqlsrv:server=$server;database=$dbname";
			case self::DBMS_MYSQL:
				return "mysql:host=$server;dbname=$dbname";
		}
	}
}
