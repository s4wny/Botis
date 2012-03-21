<?php

/**
 * Create a plain-text table.
 *
 * @package make
 * @example make a table {{row1col1, row1col2}, {r2c1, r2c2} {r3c1}, {r4c1, r4c2, r4c3, r4c4}}
 *          make a table {{row1col1, row1col2}, {r2c1, r2c2} {r3c1}, {r4c1, r4c2, r4c3, r4c4}} --margin=10 --padding=5
 *          make a table {{row1col1, row1col2}, {r2c1, r2c2} {r3c1}, {r4c1, r4c2, r4c3, r4c4}} -m=10 -p=5
 * @author Sony? aka Sawny
 */
 
class table
{
    
    /**
     * Create a text table.
     *
     * @param (string) $tbldata = Ie {row1Col1, row1Col2} {row2}
     * @param (string) optional $opt1    = margin:x
     * @param (string) optional $opt2    = /padding:x
     */
#   function table($tbldata, $argv?)
    function table($tbldata, $opt1 = null, $opt2 = null)
    {
        //Init vars
        $tableRow = 0;
        $nl       = chr(10); //New line
        $tblsep;             //Separatorn (+------+-----+)
        $tblSize;            //Längsta ordet i tabellen (10, rakabasjai)
        $tblWidth;           //Antal ord i en rad som förekommer mäst (3, hund, rakabasjai, fågel)
        $margin;             //Antal space
    
        //Argumenten
        //--------------------------------------------
        if(!isset($tbldata)) {
            echo "Usages: \"{rubrik, rubrik 2} {kolum 1, kolum 2} {kolum} ...\" [ /margin:int][ /padding:int]";
            break 1;
        }
        
        unset($argv[0]);
        $argv = explode(" /", implode(" ", $argv));
    
        //Margin och padding
        if(isset($argv[1]))
        {
            $argv[1] = trim($argv[1]);
            preg_match("/(margin:(?P<m>[\d]+))|(padding:(?P<p>[\d]+))/", $argv[1], $m);
            if(!empty($m["p"])) { define("PADDING", $m["p"]); }
            if(!empty($m["m"])) { define("MARGIN",  $m["m"]); }
        }
    
        if(isset($argv[2]))
        {
            $argv[2] = trim($argv[2]);
            preg_match("/(margin:(?P<m>[\d]+))|(padding:(?P<p>[\d]+))/", $argv[2], $m);
            if(!empty($m["p"])) { define("PADDING", $m["p"]); }
            if(!empty($m["m"])) { define("MARGIN",  $m["m"]); }
        }
    
        define("PADDING", 2);
        define("MARGIN", 5);
    
    
        //--------------------------------------------    
        //Tar ut datan vi vill ha
        
        preg_match_all("/(?P<th>[^,}{]+)|(}(?P<space> ){)/", $argv[0], $matches, PREG_SET_ORDER);
      
        //Gör om $matches till en smartare array
        for($i=0; $matches[$i] !== null; $i++)
        {
            krsort($matches[$i]);
            
            if(current($matches[$i]) == " ") {
                $tableRow++;
            }
            else {
                $table[$tableRow][] = current($matches[$i]);
            }
        }
        
        //Tar ut längden på det längsta ordet ($tblSize) och
        //tar reda på hur många ord som mäst förekommer på en rad ($tblWidth)
        if(!function_exists("my_strlen")) {
            /**
             * Strl av $iaaa
             */
            function my_strlen($i, $k) {
                global $tblSize, $tblWidth;
    
                $tblSize[]  = strlen($i);
                $tblWidth[] = $k;
            }
        }
        
        
        global $tblSize, $tblWidth;
        array_walk_recursive($table, 'my_strlen');
    
        
        rsort($tblSize);
        rsort($tblWidth);
    
        //Margin
        for($i=0; $i <= MARGIN; $i++) {
            $margin .= " ";
        }
    
        //Skapar +-----+-----+ delen
        $tblsep = $margin;
        for($i=0; $i <= $tblWidth[0]; $i++) {
            $tblsep .= str_pad("+", $tblSize[0] + PADDING + 1, "-");
        }
        $tblsep .= "+" . $nl;
    
    
        //Skapar tabelen
        for($i=0; $table[$i] !== null; $i++)
        {
            for($j=0; $j <= $tblWidth[0]; $j++) {
                $buf .= str_pad($table[$i][$j], $tblSize[0] + PADDING, " ", STR_PAD_BOTH) ."|";
            }
            
            $buf   = $margin . '|' . $buf . $nl . $tblsep;
            $buf2 .= $buf;
            $buf   = "";
        }
    
        $buf2 = $tblsep . $buf2 . $nl;
    
        echo $nl;
        echo $buf2;
    }
}

//(we love you botis <3)
?>
