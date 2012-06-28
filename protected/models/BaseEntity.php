<?php

abstract class BaseEntity extends CActiveRecord {
	protected function beforeValidate() {
		if ($this->isNewRecord) {
			$this->creationDate = new CDbExpression('NOW()');
			$this->createdBy = Yii::app()->user->id;
		} else {
			$this->modifiedDate = new CDbExpression('NOW()');
			$this->lastModifiedBy = Yii::app()->user->id;
		}
		
		return parent::beforeValidate();
	}
}

?>