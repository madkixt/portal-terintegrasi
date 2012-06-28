<?php

/**
 * This is the model class for table "tbl_query".
 *
 * The followings are the available columns in table 'tbl_query':
 * @property integer $queryID
 * @property string $judulQuery
 * @property string $isiQuery
 * @property string $databaseName
 * @property string $notes
 * @property string $creationDate
 * @property string $modifiedDate
 * @property string $notesModifiedDate
 * @property integer $createdBy
 * @property integer $lastModifiedBy
 * @property integer $lastNotesEditor
 *
 * The followings are the available model relations:
 * @property User $createdBy0
 * @property User $lastModifiedBy0
 * @property User $lastNotesEditor0
 * @property User[] $tblUsers
 */
class Query extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Query the static model class
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
		return 'tbl_query';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('judulQuery, isiQuery, databaseName', 'required'),
			array('createdBy, lastModifiedBy, lastNotesEditor', 'numerical', 'integerOnly'=>true),
			array('judulQuery', 'length', 'max'=>50),
			array('databaseName', 'length', 'max'=>30),
			array('modifiedDate, notesModifiedDate', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('queryID, judulQuery, isiQuery, databaseName, notes, creationDate, modifiedDate, notesModifiedDate, createdBy, lastModifiedBy, lastNotesEditor', 'safe', 'on'=>'search'),
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
			'lastNotesEditor0' => array(self::BELONGS_TO, 'User', 'lastNotesEditor'),
			'tblUsers' => array(self::MANY_MANY, 'User', 'tbl_user_query(queryID, userID)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'queryID' => 'ID',
			'judulQuery' => 'Judul Query',
			'isiQuery' => 'Isi Query',
			'databaseName' => 'Database Name',
			'notes' => 'Notes',
			'creationDate' => 'Creation Date',
			'modifiedDate' => 'Modified Date',
			'notesModifiedDate' => 'Notes Modified Date',
			'createdBy' => 'Created By',
			'lastModifiedBy' => 'Last Modified By',
			'lastNotesEditor' => 'Last Notes Editor',
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

		$criteria->compare('queryID',$this->queryID);
		$criteria->compare('judulQuery',$this->judulQuery,true);
		$criteria->compare('isiQuery',$this->isiQuery,true);
		$criteria->compare('databaseName',$this->databaseName,true);
		$criteria->compare('notes',$this->notes,true);
		$criteria->compare('creationDate',$this->creationDate,true);
		$criteria->compare('modifiedDate',$this->modifiedDate,true);
		$criteria->compare('notesModifiedDate',$this->notesModifiedDate,true);
		$criteria->compare('createdBy',$this->createdBy);
		$criteria->compare('lastModifiedBy',$this->lastModifiedBy);
		$criteria->compare('lastNotesEditor',$this->lastNotesEditor);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}