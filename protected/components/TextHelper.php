<?php

class TextHelper {
	public $rowsPerWrite = 5000;

	public function toText($dp, $inborder = false, $withHeader = true, $char = ' ', $spacing = 1, $hborder = '-', $vborder = '|') {
		if ($dp instanceof CDataProvider)
			$dp = $dp->data;
		
		$lengths = $this->maxLengths($dp);
		
		if ($withHeader)
			$str = $this->headerText($dp, $lengths, $char, $spacing, $hborder, $vborder);
		else
			$str = $this->line($lengths, $hborder, $spacing, $vborder);
		
		$str .= $this->bodyText($dp, $lengths, $inborder, $char, $spacing, $hborder, $vborder);
		
		if (!$inborder)
			$str .= $this->line($lengths, $hborder, $spacing, $vborder);
		
		return $str;
	}
	
	public function headerText($dp, $lengths = array(), $char = ' ', $spacing = 1, $hborder = '-', $vborder = '|') {
		if (count($lengths) === 0)
			$lengths = $this->maxLengths($dp);
			
		$str = $this->line($lengths, $hborder, $spacing, $vborder);
		
		foreach ($lengths as $key => $length) {
			$str .= $this->padCol($key, $length, $char, $spacing, $vborder);
		}
		$str .= $vborder . "\n";
		
		$str .= $this->line($lengths, $hborder, $spacing, $vborder);
		
		return $str;
	}
	
	public function bodyText($dp, $lengths = array(), $inborder = false, $char = ' ', $spacing = 1, $hborder = '-', $vborder = '|') {
		if (count($lengths) === 0)
			$lengths = $this->maxLengths($dp);
			
		$str = '';
		
		foreach ($dp as $data) {
			$data = $this->colsToString($data);
			$str .= $this->pad($data, $lengths, $inborder, $char, $spacing, $hborder, $vborder);
		}
		
		return $str;
	}
	
	public function line($lengths, $char = '-', $spacing = 1, $vborder = '|') {
		$str = "";
		
		foreach ($lengths as $length) {
			$str .= $this->padCol('', $length, $char, $spacing, $vborder);
		}
		$str .= $vborder . "\n";
		
		return $str;
	}
	
	public function maxLengths($dp) {
		if ($dp instanceof CDataProvider)
			$dp = $dp->data;
	
		$lengths = array();
		foreach ($dp[0] as $key => $value) {
			$lengths[$key] = $this->length($key);
		}
		
		foreach ($dp as $data) {
			foreach ($data as $key => $value) {
				$len = $this->length($value);
				if ($len > $lengths[$key])
					$lengths[$key] = $len;
			}
		}
		
		return $lengths;
	}
	
	public function length($value) {
		$value .= '';
		$values = explode("\n", $value);
		
		$maxlen = 0;
		foreach ($values as $val) {
			$len = strlen($val);
			if ($len > $maxlen)
				$maxlen = $len;
		}
		
		return $maxlen;
	}
	
	
	public function pad($data, $lengths, $inborder = false, $char = ' ', $spacing = 1, $hborder = '-', $vborder = '|') {
		$done = array();
		$str = '';
		
		foreach ($data as $key => $value) {
			$done[$key] = false;
		}
		
		while (!$this->isDone($done)) {
			foreach ($done as $key => $value) {
				if ($value) {
					$str .= $this->padCol('', $lengths[$key], $char, $spacing, $vborder);
					continue;
				}
				
				$nlinepos = strpos($data[$key], "\n");
				if ($nlinepos === false) {
					$done[$key] = true;
					$str .= $this->padCol($data[$key], $lengths[$key], $char, $spacing, $vborder);
				} else {
					$str .= $this->padCol(substr($data[$key], 0, $nlinepos - 1), $lengths[$key], $char, $spacing, $vborder);
					$data[$key] = substr($data[$key], $nlinepos + 1);
				}
			}
			$str .= $vborder . "\n";
		}
		
		if ($inborder)
			$str .= $this->line($lengths, $hborder, $spacing, $vborder);
		return $str;
	}
	
	public function padCol($val, $length, $char = ' ', $spacing = 1, $vborder = '|') {
		$init = strlen($val);
		
		$space = str_repeat($char, $spacing);
		
		$val = $vborder . $space . $val . str_repeat($char, $length - strlen($val)) . $space;
		
		return $val;
	}
	
	public function colsToString($data) {
		foreach ($data as $key => $val)
			$data[$key] = $val . '';
		
		return $data;
	}
	
	public function isDone($done) {
		foreach ($done as $val) {
			if (!$val)
				return false;
		}
		return true;
	}
	
	public function write($dp, $fname, $inborder = false, $withHeader = true, $char = ' ', $spacing = 1, $hborder = '-', $vborder = '|') {
		if ($dp instanceof CDataProvider)
			$dp = $dp->data;
		
		if (count($dp) <= $this->rowsPerWrite) {
			$file = fopen($fname, 'w');
			fwrite($file, $this->toText($dp, $inborder, $withHeader, $char, $spacing, $hborder, $vborder));
			return;
		}
		
		// Write the header
		$file = fopen($fname, 'w');
		$lengths = $this->maxLengths($dp);
		fwrite($file, ($withHeader) ? $this->headerText($dp, $lengths, $char, $spacing, $hborder, $vborder) : '');
		fclose($file);
		
		// Write the body, part by part
		$file = fopen($fname, 'a');
		$iter = ceil(count($dp) / $this->rowsPerWrite);
		for ($i = 0; $i < $iter; $i++) {
			$data = array_slice($dp, $i*$this->rowsPerWrite, $this->rowsPerWrite);
			fwrite($file, $this->bodyText($data, $lengths, $inborder, $char, $spacing, $hborder, $vborder));
		}
		
		// Write the final line
		fwrite($file, $this->line($lengths, $hborder, $spacing, $vborder));
	}
	
	public function partText($dp, $partno = 0, $lengths = array(), $inborder = false, $withHeader = true, $char = ' ', $spacing = 1, $hborder = '-', $vborder = '|') {
		if (count($lengths) === 0)
			$lengths = $this->maxLengths($dp);
			
		$data = array_slice($dp, $partno*$this->rowsPerWrite, $this->rowsPerWrite);
		
		return $this->bodyText($data, $lengths, $inborder, $char, $spacing, $hborder, $vborder);
	}
}

?>