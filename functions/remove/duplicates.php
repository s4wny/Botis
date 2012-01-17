<?php

/**
 * Remove identical lines from a file.
 *
 * If you need to work with binary files edit the
 * script and specifc the b flag for fopen.
 *
 * @author Sony? aka Sawny
 * @example remove duplicates from C:\...\example.txt
 * @example remove duplicates from C:\...\hugeFile.txt fast!
 */
class duplicates
{
    /**
     * Remove identical lines from a file.
     *
     * @param $from     (null)     Does nothing. Just so you can wirte 'remove duplicates _from_ x'
     * @param $file     (string)   The file to remove duplicates from. Will be overridden.
     * @param $fastMode (anything) Anything except false will use fast mode.
                                   *Note:* Fastmode will _sort_ your data (ASC) in order to work with a fast algorithm.
     */
    function __construct($from, $file, $fastMode = false)
    {
        clearstatcache();
        
        if(file_exists($file))
        {
            //Read file and prepare it
            $fh   = fopen($file, 'r') OR debug(ERROR_LVL_ERROR, "Can't open '$file' for reading! Dunno why.\n");   
            $data = stream_get_contents($fh);
            fclose($fh);
            
            
            $data    = explode(nl, $data);
            $newData = ($fastMode === false) ? $this->slowAlgorithm($data) : $this->fastAlgorithm($data);
            
            
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
     * *about the algoithm*
     *
     * @param $data (array) array('row1', 'row2', 'row3', ...)
     * @return $data with no duplicated lines.
     */
    private function slowAlgorithm($data)
    {
        $newData    = array(); //The new data with no duplicates!
        $duplicates = array(); //The removed duplicates
        $rows       = count($data);
        
        debug(ERROR_LVL_DEBUG, "Slow algorithm.");
        
        //Remove duplicates
        foreach($data as $pos => $row)
        {
            echo "Status, line: ". $pos ." / $rows (". round($pos / $rows * 100) ."%) \r";
            
            if(!in_array($row, $newData)) {
                $newData[] = $row;
            }
            else {
                $duplicates[] = $row;
            }
        }
        
        
        //Print the result
        echo str_repeat(" ", strlen($mess)) . "\n";
        echo $this->finishStatus($rows, count($newData));
        
        
        return implode(nl, $newData);
    }
    
    
    /**
     * Sort the data and then just check the prev item. Much faster. 
     *
     * The algorithm:
     * 1) First sort the data. (This way all duplicates will be in order and the search will just check the prev item)
     * 2) Loop through all items
     *     2.1) Check if prev was the same
     *     2.2) If true, skip the item.
     *
     * @param $data (array) array('row1', 'row2', 'row3', ...)
     * @return $data with no duplicated lines.
     */
    private function fastAlgorithm($data)
    {
        $newData = array();      //The new data with no duplicates!
        $rows    = count($data); //Num of rows in old data
        $i       = 0;
        
        debug(ERROR_LVL_DEBUG, "Fast algorithm.");
        
        sort($data);
        
        //Remove duplicates
        foreach($data as $pos => $row)
        {
            echo "Status, line: ". $pos ." / $rows (". round($pos / $rows * 100) ."%) \r";
            
            if($row !== $newData[$i]) { //Check ONE item.
                $newData[++$i] = $row;
            }
        }
        
        
        //Print the result
        echo str_repeat(" ", strlen($mess)) . "\n"; //Override the last \r
        echo $this->finishStatus($rows, count($newData));

        
        return implode(nl, $newData);
    }
    
    
    /**
     * Return the finish status, ie: Removed x lines on x seconds.
     *
     * @param $orginalRows (int) ...
     * @param $newRows     (int) ...
     * @TODO: Tiden det tog
     */
    private function finishStatus($orginalRows, $newRows)
    {
        return "Removed   ". ($orginalRows - $newRows) ." duplicates.\n".
               "Old file: ". $orginalRows ." lines\n".
               "New file: ". $newRows ." lines\n";
    }
    
    
}


//(we love you botis <3)
?>