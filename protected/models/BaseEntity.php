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
	
	public function getCreatorUsername() {
		$user = $this->createdBy0;
		if (null == $user)
			return null;
		return $user->username;
	}
	
	public function getEditorUsername() {
		$user = $this->lastModifiedBy0;
		if (null == $user)
			return null;
		return $user->username;
	}
	
	public function array_search_ci($needle, $haystack) {
		if ($needle == false)
			return null;
			
		for ($i = 0; $i < count($haystack); $i++) {
			$pos = stripos($haystack[$i], $needle);
			if ($pos !== false) {
				return $i;
			}
		}
		return -1;
	}
}

?>