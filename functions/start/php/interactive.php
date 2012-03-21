<?php

/**
 * Executes PHP code. Like php -a but better.
 *
 * @package    start\php
 * @author     Sony aka Sawny
 * @example    `start php interactive`
               > echo "Hi";
               < Hi
               > <?php
               > for($i = 0; $i < 4; $i++) {
               >    echo $i . PHP_EOL;
               > }
               > echo "Cya!";
               > ?>
               > 0
               > 1
               > 2
               > 3
               > Cya!
 */
class interactive
{
    /**
     * Execute PHP code
     */
    function __construct()
    {
        $codeBuf = ""; //PHP code goes here.
        
        while(1)
        {
            echo ($codeBuf === "") ? nl . "> ": "| ";
            
            $usrinp = fgets(STDIN);

            
            if(trim($usrinp) == '?>' AND $codeBuf !== "") //Run the block of PHP code
            {
                eval($codeBuf);
                
                $codeBuf = "";
            }
            elseif($codeBuf !== "") { //Add PHP code to the block
                $codeBuf .= $usrinp;
            }
            elseif(trim($usrinp) == '<?php' OR trim($usrinp) == '<?') //Start a new block of PHP code
            {
                $codeBuf = "/* We <3 PHP */";
            }
            elseif(trim($usrinp) === 'exit' OR trim($usrinp) === 'cya') //Leave
            {
                break 1;
            }
            else //Run a single line of PHP code
            {
                eval($usrinp);
            }
        }
        
    }
}


//(we love you botis <3)
?>