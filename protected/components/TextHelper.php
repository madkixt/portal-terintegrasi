<?php

class TextHelper {
	public function toText($dp) {
		$lengths = $this->maxLengths($dp);
		return ($this->headerText($dp, $lengths) . $this->bodyText($dp, $lengths) . $this->footerText($lengths));
	}
	
	public function headerText($dp, $lengths) {
		$str = "";
		
		foreach ($lengths as $length) {
			$str .= '|--' . $this->pad('', $length, '-') . '--';
		}
		$str .= "|\n";
		
		foreach ($lengths as $key => $length) {
			$str .= '|  ' . $this->pad($key, $length) . '  ';
		}
		$str .= "|\n";
		
		foreach ($lengths as $length) {
			$str .= '|--' . $this->pad('', $length, '-') . '--';
		}
		$str .= "|\n";
		
		return $str;
	}
	
	public function bodyText($dp, $lengths) {
		$str = '';
		
		foreach ($dp->data as $data) {
			foreach ($data as $key => $value) {
				$str .= '|  ' . $this->pad($value, $lengths[$key]) . '  ';
			}
			$str .= "|\n";
		}
		return $str;
	}
	
	public function footerText($lengths) {
		$str = "";
		
		foreach ($lengths as $length) {
			$str .= '|--' . $this->pad('', $length, '-') . '--';
		}
		$str .= "|";
		
		return $str;
	}
	
	public function maxLengths($dp) {
		$lengths = array();
		foreach ($dp->data[0] as $key => $value) {
			$lengths[$key] = $this->length($key);
		}
		
		foreach ($dp->data as $data) {
			foreach ($data as $key => $value) {
				if ($this->length($value) > $lengths[$key])
					$lengths[$key] = $this->length($value);
			}
		}
		
		return $lengths;
	}
	
	public function length($value) {
		return strlen($value . '');
	}
	
	public function pad($string, $length, $char = ' ', $maxlen = 0) {
		$init = strlen($string);
		for ($i = $init; $i < $length; $i++)
			$string .= $char;
		return $string;
	}
	
	public function write($text, $fname = 'D:/TextHelperTest.txt') {
		$file = fopen($fname, 'w');
		fwrite($file, $text);
	}
}

?>