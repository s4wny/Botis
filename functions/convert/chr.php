<?php

/**
 * Convert chr to hex, binary, octal and dec.
 *
 * @author Sony? aka Sawny
 * @package convert
 * @example Convert chr "<3" to dec
 * @TODO: Rename to text
 */
class chr
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
       //TODO: regexp, is dec?
       $val = trim($val);
       
       echo $this->$to($val);
    }
    
    
    /**
     * Convert chr to hex
     */
    public function hex($chr)
    {
        for($i = 0, $chrLen = strlen($chr); $i < $chrLen; $i++)
        {
            $delimiter = (($i + 1) % 4  === 0 AND $i !== 0) ? "    ":" ";        //Tab or space
            $delimiter = (($i + 1) % 16 === 0 AND $i !== 0) ? "\n"  :$delimiter; //New line after 4 colums (16/4=4)
            $result   .= dechex(ord($chr[$i])) . $delimiter;
        }
        
        return trim($result);
    }
    
    
    /**
     * Convert chr to bin
     */
    public function bin($chr)
    {
           for($i = 0, $chrLen = strlen($chr); $i < $chrLen; $i++)
        {
            $delimiter = (($i + 1) % 4 === 0  AND $i !== 0) ? "    ":" ";       
            $delimiter = (($i + 1) % 8 === 0  AND $i !== 0) ? "\n"  :$delimiter; 
            $result   .= sprintf("%08d", decbin(hexdec($chr[$i]))) . $delimiter;
        }
        
        return trim($result);
    }
    
    
    /**
     * Convert chr to dec
     */
    public function dec($chr)
    {
        for($i = 0, $chrLen = strlen($chr); $i < $chrLen; $i++)
        {
            $delimiter = (($i + 1) % 4  === 0 AND $i !== 0) ? "    ":" ";        //Tab or space
            $delimiter = (($i + 1) % 16 === 0 AND $i !== 0) ? "\n"  :$delimiter; //New line after 4 colums (16/4=4)
            $result   .= sprintf("%-3d", ord($chr[$i])) . $delimiter;
        }
        
        return $result;
    }
    
    
    /**
     * Convert chr to oct
     */
    public function oct($chr)
    {
        for($i = 0, $chrLen = strlen($chr); $i < $chrLen; $i++)
        {
            $delimiter = (($i + 1) % 4  === 0 AND $i !== 0) ? "    ":" ";        //Tab or space
            $delimiter = (($i + 1) % 16 === 0 AND $i !== 0) ? "\n"  :$delimiter; //New line after 4 colums (16/4=4)
            $result   .= sprintf("%03d", decoct(hexdec($chr[$i]))) . $delimiter;
        }
        
        return trim($result);
    }
    
    
    //Expriment
    //--------------------------
    
    /**
     * Allows u to write $chr->to->dec.
     */
    public function to()
    {
        return new hex();
    }
}


//(we love you botis <3)
?>
