<?php

/**
 * This is the model class for table "tbl_connection".
 *
 * The followings are the available columns in table 'tbl_connection':
 * @property integer $connectionID
 * @property string $serverName
 * @property string $IPAddress
 * @property string $username
 * @property string $password
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
	public $password_repeat;

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
			array('serverName, IPAddress, username, password, password_repeat', 'required'),
			array('serverName, username', 'length', 'max'=>20),
			array('IPAddress', 'length', 'max'=>15),
			array('password', 'length', 'max'=>32),
			array('password', 'compare'),
			array('description, modifiedDate', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('connectionID, serverName, IPAddress, username, description, creationDate, modifiedDate, createdBy, lastModifiedBy', 'safe', 'on'=>'search'),
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
			'connectionID' => 'Connection',
			'serverName' => 'Server Name',
			'IPAddress' => 'IP Address',
			'username' => 'Username',
			'password' => 'Password',
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
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('connectionID',$this->connectionID);
		$criteria->compare('serverName',$this->serverName,true);
		$criteria->compare('IPAddress',$this->IPAddress,true);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('creationDate',$this->creationDate,true);
		$criteria->compare('modifiedDate',$this->modifiedDate,true);
		$criteria->compare('createdBy',$this->createdBy);
		$criteria->compare('lastModifiedBy',$this->lastModifiedBy);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}