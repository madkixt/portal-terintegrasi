<?php

class PortalHtml extends CHtml {
	public static function customListData($models,$valueField,$textField,$groupField='')
	{
		$listData=array();
		if($groupField==='')
		{
			foreach($models as $model)
			{
				$value=self::value($model,$valueField);
				$text = '';
				foreach ($textField as $textf) {
					if ($textf === '')
						continue;
						
					if ((substr($textf, 0, 1) === '{') && (substr($textf, strlen($textf) - 1, 1) === '}')) {
						$textf = substr($textf, 1, strlen($textf) - 2);
						$text .= self::value($model, $textf);
					} else
						$text .= $textf;
				}
				$listData[$value] = $text;
			}
		}
		else
		{
			foreach($models as $model)
			{
				$group=self::value($model,$groupField);
				$value=self::value($model,$valueField);
				$text=self::value($model,$textField);
				$listData[$group][$value]=$text;
			}
		}
		return $listData;
	}
}

?>