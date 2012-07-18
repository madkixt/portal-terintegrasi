<?php

/**
 * This is the model class for table "tbl_statement".
 *
 * The followings are the available columns in table 'tbl_statement':
 * @property integer $queryID
 * @property integer $queryNum
 * @property string $queryStatement
 * @property string $notes
 *
 * The followings are the available model relations:
 * @property Query $query
 */
class Statement extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Statement the static model class
	 */
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_statement';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('queryID, queryNum, queryStatement', 'required'),
			array('queryID, queryNum', 'numerical', 'integerOnly'=>true),
			array('notes', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('queryID, queryNum, queryStatement', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations() {
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'query' => array(self::BELONGS_TO, 'Query', 'queryID'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'queryID' => 'Query',
			'queryNum' => 'Query #',
			'queryStatement' => 'Statement',
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
		$criteria->compare('queryNum',$this->queryNum);
		$criteria->compare('queryStatement',$this->queryStatement,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}