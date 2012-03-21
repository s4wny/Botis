<?php

/**
 * Convert dec to hex, binary, octal and character.
 *
 * @author Sony? aka Sawny
 * @package convert
 * @example convert dec "60 51" to chr
 */
class dec
{
    /**
     * Run the convert
     *
     * @TODO copy to clipbord (exec + botis $this | clip)
     *       raw output
     * @param (int)    $val/([0-9]+)/ = xxx.
     * @param (string) $to            = xxx.
     */
    function __construct($val, $nullWord, $to)
    {
       
       //TODO: If isset
       //TODO: regexp, is dec?
       $to  = str_replace("text", "chr", $to); //Alias
       $val = trim($val);
       
       print_r(explode(" ", $val));
       
       echo $this->$to($val);
    }
    
    
    /**
     * Convert dec to hex
     */
    public function hex($dec)
    {
        for($i = 0, $decLen = strlen($dec); $i < $decLen; $i++)
        {
            $delimiter = (($i + 1) % 4  === 0 AND $i !== 0) ? "    ":" ";        //Tab or space
            $delimiter = (($i + 1) % 16 === 0 AND $i !== 0) ? "\n"  :$delimiter; //New line after 4 colums (16/4=4)
            $result   .= sprintf("%02x", $dec[$i]) . $delimiter;
        }
        
        return trim($result);
    }
    
    
    /**
     * Convert dec to bin
     */
    public function bin($dec)
    {
           for($i = 0, $decLen = strlen($dec); $i < $decLen; $i = $i + 2)
        {
            $delimiter = ((($i / 2) + 1) % 4 === 0  AND $i !== 0) ? "    ":" ";       
            $delimiter = ((($i / 2) + 1) % 8 === 0  AND $i !== 0) ? "\n"  :$delimiter; 
            $result   .= sprintf("%08d", decbin(hexdec(substr($dec, $i, 2)))) . $delimiter;
        }
        
        return trim($result);
    }
    
    
    /**
     * Convert dec to chr
     */
    public function chr($dec)
    {
        for($i = 0, $decLen = strlen($dec); $i < $decLen; $i = $i + 2) {
            $result .= chr(hexdec(substr($dec, $i, 2)));
        }
        
        return $result;
    }
    
    
    /**
     * Convert dec to oct
     */
    public function oct($dec)
    {
        for($i = 0, $decLen = strlen($dec); $i < $decLen; $i = $i + 2)
        {
            $delimiter = ((($i / 2) + 1) % 4  === 0  AND $i !== 0) ? "    ":" ";       
            $delimiter = ((($i / 2) + 1) % 16 === 0  AND $i !== 0) ? "\n"  :$delimiter; 
            $result   .= sprintf("%03d", decoct(hexdec(substr($dec, $i, 2)))) . $delimiter;
        }
        
        return trim($result);
    }
}


//(we love you botis <3)
?>
