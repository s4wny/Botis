<?php

/**
 * Convert hex to decimal, binary, octal and character.
 *
 * @author Sony? aka Sawny
 * @example convert hex 0x3C33 to chr
 */
class hex
{
    /**
	 * Run the convert
	 *
	 * @TODO copy to clipbord (exec + botis $this | clip)
	 *       raw output
	 */
    function __construct($val, $null, $to)
	{
	   //TODO: If isset
	   //TODO: regexp, is hex?
	   $to  = str_replace("text", "chr", $to); //Alias
	   $val = str_replace(array("0x" ," ", NL),  "", $val);
	   
	   echo $this->$to($val);
	}
	
	
	/**
	 * Convert hex to dec
	 */
	public function dec($hex)
	{
		for($i = 0, $hexLen = strlen($hex); $i < $hexLen; $i = $i + 2)
		{
		    $delimiter = ((($i / 2) + 1) % 4  === 0 AND $i !== 0) ? "    ":" ";        //Tab or space
		    $delimiter = ((($i / 2) + 1) % 16 === 0 AND $i !== 0) ? "\n"  :$delimiter; //New line after 4 colums (16/4=4)
			$result   .= sprintf("%-3s", hexdec(substr($hex, $i, 2))) . $delimiter;
		}
		
	    return trim($result);
	}
	
	
	/**
	 * Convert hex to bin
	 */
	public function bin($hex)
	{
	   	for($i = 0, $hexLen = strlen($hex); $i < $hexLen; $i = $i + 2)
		{
		    $delimiter = ((($i / 2) + 1) % 4 === 0  AND $i !== 0) ? "    ":" ";       
		    $delimiter = ((($i / 2) + 1) % 8 === 0  AND $i !== 0) ? "\n"  :$delimiter; 
			$result   .= sprintf("%08d", decbin(hexdec(substr($hex, $i, 2)))) . $delimiter;
		}
		
	    return trim($result);
	}
	
	
	/**
	 * Convert hex to chr
	 */
	public function chr($hex)
	{
	    for($i = 0, $hexLen = strlen($hex); $i < $hexLen; $i = $i + 2) {
			$result .= chr(hexdec(substr($hex, $i, 2)));
		}
		
	    return $result;
	}
	
	
	/**
	 * Convert hex to oct
	 */
	public function oct($hex)
	{
	    for($i = 0, $hexLen = strlen($hex); $i < $hexLen; $i = $i + 2)
		{
		    $delimiter = ((($i / 2) + 1) % 4  === 0  AND $i !== 0) ? "    ":" ";       
		    $delimiter = ((($i / 2) + 1) % 16 === 0  AND $i !== 0) ? "\n"  :$delimiter; 
			$result   .= sprintf("%03d", decoct(hexdec(substr($hex, $i, 2)))) . $delimiter;
		}
		
	    return trim($result);
	}
	
	
	//Expriment
	//--------------------------
	
	/**
	 * Allows u to write $hex->to->dec.
	 */
	public function to()
	{
	    return new hex();
	}
}


//(we love you botis <3)
?>