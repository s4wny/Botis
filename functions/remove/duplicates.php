<?php

/**
 * Remove identical lines from a file.
 *
 * Can remove milions duplicates under one second (intel core i3).
 * If you need to work with binary files edit the
 * script and specifc the b flag for fopen.
 *
 * @author     Sony? aka Sawny
 * @package    remove
 * @example    remove duplicates from C:\...\example.txt
 * @example    remove duplicates from C:\...\hugeFile.txt
 */
class duplicates
{
    /**
     * Remove identical lines from a file.
     *
     * @param $from     (null)     Does nothing. Just so you can wirte 'remove duplicates _from_ x'
     * @param $file     (string)   The file to remove duplicates from. Will be overridden.
     */
    function __construct($from, $file, $slow = false)
    {
        clearstatcache();
        
        if(file_exists($file))
        {
            //Read file and prepare it
            $fh   = fopen($file, 'r') OR debug(ERROR_LVL_ERROR, "Can't open '$file' for reading! Dunno why.\n");   
            $data = stream_get_contents($fh);
            fclose($fh);
            
            
            $data    = explode(nl, $data);
            $newData = $this->fastAlgorithm($data);
            
            
            //Save the new data
            $fh = fopen($file, 'w') OR debug(ERROR_LVL_ERROR, "Can't open '$file' for writing! Dunno why.\n"); 
            fwrite($fh, $newData) OR debug(ERROR_LVL_ERROR, "Can't write to '$file'! :(");
            fclose($fh);
        }
        else
        {
            debug(ERROR_LVL_ERROR, "Can't find '$file'!\n Protip: use absolute path if relative path don't work.");
        }
    }
    
    
    /**
     * Remove duplicates.
     *
     * Big thanks to http://www.puremango.co.uk/2010/06/fast-php-array_unique-for-removing-duplicates/ and the comments.
     *
     * @author John @link http://www.puremango.co.uk/2010/06/fast-php-array_unique-for-removing-duplicates/#comment-14638
     * @param $data (array) array('row1', 'row2', 'row3', ...)
     * @return $data with no duplicated lines.
     */
    private function fastAlgorithm($data)
    {
        $rows      = count($data); //Num of rows in old data
        $startTime = microtime(true);

        $data = array_keys(array_flip($data));
        
        //Print the result
        echo $this->finishStatus($rows, count($data), $startTime);

        return implode(nl, $data);
    }
    
    
    /**
     * Return the finish status, ie: Removed x lines on x seconds.
     *
     * @param $orginalRows (int) ...
     * @param $newRows     (int) ...
     * @TODO: Tiden det tog
     */
    private function finishStatus($orginalRows, $newRows, $startTime)
    {
        $duration = microtime(true) - $startTime;
        
        return "Time:     ". $duration                 ." micro seconds.\n".
               "       = ~". round($duration)          ." seconds.\n".
               "Removed   ". ($orginalRows - $newRows) ." duplicates.\n".
               "Old file: ". $orginalRows              ." lines\n".
               "New file: ". $newRows                  ." lines\n";
    }
    
    
}


//(we love you botis <3)
?>