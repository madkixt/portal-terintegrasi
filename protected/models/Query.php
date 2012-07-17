<?php

/**
 * This is the model class for table "tbl_query".
 *
 * The followings are the available columns in table 'tbl_query':
 * @property integer $queryID
 * @property string $title
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
 * @property Statement[] $statements
 * @property User[] $tblUsers
 */
class Query extends BaseEntity
{
	public $queryString;
	
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
	public function tableName() {
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
			array('title, databaseName', 'required'),
			array('createdBy, lastModifiedBy, lastNotesEditor', 'numerical', 'integerOnly' => true),
			array('title', 'length', 'max'=>50),
			array('databaseName', 'length', 'max'=>30),
			array('notes', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('queryID, title, databaseName, notes, creationDate, modifiedDate, notesModifiedDate, createdBy, lastModifiedBy, lastNotesEditor, queryString', 'safe', 'on' => 'search'),
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
			'statements' => array(self::HAS_MANY, 'Statement', 'queryID'),
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
			'title' => 'Query Title',
			'databaseName' => 'Database Name',
			'notes' => 'Notes',
			'creationDate' => 'Creation Date',
			'modifiedDate' => 'Modified Date',
			'notesModifiedDate' => 'Notes Modified Date',
			'createdBy' => 'Created By',
			'lastModifiedBy' => 'Last Modified By',
			'lastNotesEditor' => 'Last Notes Editor',
			'queryString' => 'Query'
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search($id = null) {
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria = new CDbCriteria;
		$criteria->with = array('createdBy0', 'lastModifiedBy0', 'lastNotesEditor0', 'statements');
		
		if ($id != null) {
			$criteria->with['tblUsers'] = array(
				'condition' => 'tblUsers.userID = :userID',
				'params' => array(
					':userID' => $id
				)
			);
		}
		
		$criteria->compare('t.queryID',$this->queryID);
		$criteria->compare('t.title',$this->title,true);
		$criteria->compare('t.databaseName',$this->databaseName,true);
		$criteria->compare('t.notes',$this->notes,true);
		$criteria->compare('t.creationDate',$this->creationDate,true);
		$criteria->compare('t.modifiedDate',$this->modifiedDate,true);
		$criteria->compare('t.notesModifiedDate',$this->notesModifiedDate,true);
		$criteria->compare('createdBy0.username', $this->createdBy, true);
		$criteria->compare('lastModifiedBy0.username', $this->lastModifiedBy, true);
		$criteria->compare('lastNotesEditor0.username', $this->lastNotesEditor, true);
		$criteria->compare('statements.queryStatement', $this->queryString, true);
		
		$criteria->together = true;
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => false
		));
	}
	
	protected function afterValidate() {
		if (Yii::app()->controller->action->id == 'edit') {		
			$oldNotes = Query::model()->findByPk($this->queryID)->notes;
			if ($oldNotes !== $this->notes) {
				$this->notesModifiedDate = new CDbExpression('NOW()');
				$this->lastNotesEditor = Yii::app()->user->id;
			}
		}
	}
		
	public function getAssignableUsers() {
		return User::model()->with('tblQueries')->findAll(array(
			'condition' => 't.admin = :adm AND NOT EXISTS (SELECT * FROM tbl_user_query tuq WHERE tuq.userID = t.userID AND tuq.queryID = :qid)',
			'params' => array(
				':adm' => 0,
				':qid' => $this->queryID
			),
			'order' => 't.username'
		));
	}
	
	public function getNotesEditorUsername() {
		$user = $this->lastNotesEditor0;
		if (null == $user)
			return null;
		return $user->username;
	}
	
	public function getFullQuery($withBr = true) {
		$st = '';
		foreach ($this->statements as $statement) {
			$st .= $statement->queryStatement.';';
			if ($withBr) $st .= '<br />';
		}
		return $st;
	}
	
	protected function afterSave()
	{
		if($this->hasEventHandler('onAfterSave'))
			$this->onAfterSave(new CEvent($this));
		
		if ($this->isNewRecord)
			$this->insertUser();
	}
	
	public function editStatements($statements) {
		Yii::app()->db->createCommand()->delete(
			'tbl_statement',
			'queryID = :qid',
			array(':qid' => $this->queryID)
		);
		$this->insertStatements($statements);
	}
	
	/* Saves all the query's statements */
	public function insertStatements($statements) {
		$i = 1;
		foreach ($statements as $statement) {
			$model = new Statement;
			$model->queryID = $this->queryID;
			$model->queryNum = $i++;
			$model->queryStatement = $statement;
			$model->save();
		}
	}
	
	public function loadStatements() {
		$i = 1;
		foreach ($this->statements as $statement) {
			$st[$i++] = $statement->queryStatement;
		}
		return $st;
	}
	
	/* Assigns the newly created Query to the current user or all admins */
	private function insertUser() {
		if (!Yii::app()->user->getState('admin')) {
			$cmd = Yii::app()->db->createCommand();
			$cmd->insert('tbl_user_query', array('userID' => Yii::app()->user->getID(), 'queryID' => $this->queryID));
		}
		
		$admins = User::model()->findAllByAttributes(array('admin' => 1));
		$cmd = Yii::app()->db->createCommand();
		$cmd->text = 'INSERT INTO tbl_user_query(userID, queryID) VALUES (:userID, :queryID)';
		$cmd->bindValue(':queryID', $this->queryID, PDO::PARAM_INT);
		
		foreach ($admins as $admin) {
			$cmd->bindValue(':userID', $admin->userID, PDO::PARAM_INT);
			$cmd->execute();
		}
	}
}