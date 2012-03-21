<?php

/**
 * A simple vocabulary program to help with homework.
 *
 * @author       Sony? aka Sawny
 * @package      start
 * @example      Start vocabulary test
 * @TODO:        One DB for everylanguage. Then u can choose wich languge.
 * @todo         Test on the X words you have answered most incorrectly on.
 * @todo         Test on the X words you have answered most incorrectly on in a specific category.
 */
class vocabulary
{
    private static $file;
    
    /**
     * Start the main loop, the menu. 
     *
     * @param (null) $nullWords - See botis FAQ / readme.
     */
    function __construct($nullWords = null)
    {
        $this->file = BASEDIR . DATA_FOLDER . "vocabulary/vocabularies.txt";
        
        echo nl. "    --- Vocabulary test ---".nl;
        
        while(1)
        {
            echo nl . '> ';
            $usrinp = trim(fgets(STDIN));
            $cmd    = argvFix($usrinp);

            switch($cmd[0])
            {
                case "add"   : $this->add($cmd[1], $cmd[2], $cmd[3]);                                          break;
                case "start" : $cmd[1] = (isset($cmd[1])) ? trim($cmd[1], '"\'') : '*'; $this->start($cmd[1]); break;
                case "cats"  : $this->cats();                                                                  break;
                case "help"  : $this->help();                                                                  break;
                
                case "exit"  : /*  TODO: Alias? */
                case "cya"   :
                case "bai"   :
                case "bye"   : break 2;
                
                default      : break;
            }
        }
    }   

    /**
     * Help for this function.
     */
    private function help()
    {
        echo "Kommandon\n\n".
             "    - add (*langYouDontKnow*, *langYouKnow*, kategori)\n".
             "    - start ([kategori])\n".
             "    - cats\n\n";
    }
    
    
    /**
     * Add some new vocabulary words.
     */
    private function add($eng, $sv, $cat)
    {
        $cat = trim($cat, '"\'');
        $eng = trim($eng, '"\'');
        $sv  = trim($sv, '"\'');
        
        //Skriv till filen
        $fh = fopen($this->file, 'a') OR debug(ERROR_LVL_ERROR, "Can't open '".  $this->file ."' in append mode!");
        if(fwrite($fh, "$sv | $eng | 0 | 0 | $cat | ". date("Y-m-d H:i:s") . "\n")) {
            echo "$sv - $eng tillagt i $cat!\n";
        }
        else {
            debug(ERROR_LVL_ERROR, "Couldn't write to '". $this->file ."'!");
        }
        
        fclose($fh);
        
    }
    
    
    /**
     * Start a vocabulary test
     *
     * @param (string) $cat What category?
     */
    private function start($cat = '*')
    {
        //Varz
        $right = 0;
        $wrong;
        $longestWord;
        
        //Alla kategorier?
        $cat = ($cat === '*') ? TRUE : $cat;
        
        //Läs i glosorna
        $fh      = fopen($this->file, 'r+') OR debug(ERROR_LVL_ERROR, "Can't open '". $this->file ."' in reading and wrting mode (r+)!");
        $data    = stream_get_contents($fh);
        $data    = explode("\n", rtrim($data));
        
        
        foreach($data as $key => $val) {
            unset($data[$key]);
            $data[] = explode(" | ", $val);
        }
        
        sort($data); //Återställer nycklarna (0, 1, 2, ...)
        shuffle($data);
        
        echo "\n\n";
        
        //Räknar ut längsta ordet
        for($i=0; $i < count($data); $i++) {
            if($cat == $data[$i][4]) {
                $longestWord = (strlen($data[$i][0]) > $longestWord) ? strlen($data[$i][0]) : $longestWord ;
            }    
        }
        
        //Skriv ut glosorna, svara
        for($i=0; $i < count($data); $i++) {
            
            $f_sv      = $data[$i][0];
            $f_eng     = $data[$i][1];
            $f_correct = $data[$i][2];
            $f_wrong   = $data[$i][3];
            $f_cat     = $data[$i][4];
            $f_date    = $data[$i][5];
            
            
            $newData .= $f_sv ." | ". $f_eng ." | ";
            
            //Är glosorna från rätt kategori?
            if($cat == $f_cat)
            {
                echo "> ". str_pad($f_sv, $longestWord) ." = ";
                $answer = trim(fgets(STDIN));
                
                //Kollar om svaret var rätt, uppdatera statistiken
                if($answer === $f_eng) {
                    $right++;
                    $newData .= ($f_correct + 1) ." | ". $f_wrong ." | ". $f_cat ." | ". $f_date ."\n";
                }
                else {
                    $wrong[$answer] = $f_eng;
                    $newData .= $f_correct ." | ". ($f_wrong + 1) ." | ". $f_cat ." | ". $f_date ."\n";
                }
            }
            else
            {
                $newData .= $f_correct ." | ". $f_wrong ." | ". $f_cat ." | ". $f_date ."\n";
            }
        }
        
        //Skriv till filen med det nya resultatet
        rewind($fh); //Till början av filen
        fwrite($fh, trim($newData)) OR debug(ERROR_LVL_ERROR, "Can't write to '". $this->file ."'.");
        fclose($fh);
        
        //Skriv ut resultatet
        echo "\n\n> Det var alla ord. Du fick ". $right ." rätt och ". count($wrong) ." fel. \n";
        
        if(count($wrong) !== 0)
        {
            echo "> Vill du se vilka ord du fick fel på?\n< ";
            $answer = trim(fgets(STDIN));
            
            if($answer == 'j' OR $answer == 'ja' OR $answer == 'y' OR $answer == 'yes') {
                foreach($wrong as $key => $val) {
                    echo "> '$key' ska vara '$val' \n";
                }
                echo "> Lycka till nästa gång! :) \n";
            }
        }
        else {
            echo "As bra ju! :D\n";
        }
        
    }
    
    
    /**
     * Get all available categories.
     */
    private function cats()
    {
        $fh   = fopen($this->file, 'r') OR debug(ERROR_LVL_ERROR, "Can't open file '". $this->file ."' in reading mode!");
        $data = stream_get_contents($fh);
        fclose($fh);
        
        $data = explode("\n", rtrim($data));
        
        foreach($data as $key => $val) {
            unset($data[$key]);
            $data[] = explode(" | ", $val);
        }
        
        //Reset the keys (0, 1, 2, ...)
        sort($data);
        
        for($i=0; $i < count($data); $i++) {
            $cats[] = $data[$i][4];
        }
        
        $cats = array_unique($cats);
        
        foreach($cats as $val) {
            echo $val . chr(10);
        }
    }

}

//(we love you botis <3)
?>