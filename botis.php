<?php

/**
 * A amazing GLaDOS, that we all love.
 * 
 * CLI GLaDOS (Command-line Interface Genetic Lifeform and Disk Operating System)
 * Botis say hi!
 * Hi!
 * (we love you botis <3)
 *
 * @param (array) $argv = How should we else talk?
 * @example Check out readme.txt
 * @author Sony? aka Sawny
 */
class botis
{
	public $debug; //All botis error messages will be found here
	public $help;  //All help goes here, format: $help['verb']['verb'][...]['command'] = phpDoc;
	
    private $alias;
	
	
	/**
	 * Init all config files, constants, run the specific command or the main loop.
	 */
    function __construct($argv)
	{
	    //Config
		define("BASEDIR",           dirname(__FILE__));
	    define("CONFIGFILE",        BASEDIR ."/config.txt");
		define("ERROR_LVL_ERROR",   1);
        define("ERROR_LVL_WARNING", 2);
        define("ERROR_LVL_DEBUG",   3);
		define("nl",                PHP_EOL, true);
		
		
		//TODO: En kommentar
		$this->alias = $this->config();
		$argv = $this->aliasFix(array($argv[0]), $this->alias);
		$this->initCommands(BASEDIR . FUNCTION_FOLDER);
		debug(ERROR_LVL_DEBUG, "Syntax errors", $this->debug);
		
		
		//Main loop
		if($argv[1] == "start" OR !isset($argv[1]))
		{			
			$this->mainLoop();
		}
		else //One command
		{
		    //Läs in kommandot
		    //Kör det
		}
	}
	
	
    /**
	 * Main loop, check for new commands to execute
	 */
	public function mainloop()
	{
		while(1)
		{
		    echo nl . INPUT;
		    $usrinp = trim(fgets(STDIN));
			
			if(!empty($usrinp))
			{
			    $argv = argvFix($usrinp);
			    $argv = $this->aliasFix($argv, $this->alias);
				
				if($argv[0] == "help") {
				    array_shift($argv);
				    $this->help($argv);
				}
				elseif($argv[0] == "exit") {
				    break;
				}
				elseif($argv[0] == "clearscreen") {
				    $this->clearScreen();
				}
			    ELSE {
			        $this->run($argv, $this->help, BASEDIR . FUNCTION_FOLDER);
				}
			}
		}
	}
	
	
	/**
	 * Run an program and its arguments
	 *
	 * This function will also remove "small words" (and, plz, a, the, etc). It remove all words that aren't a folder or a file in 'function_folder' (const).
	 *
	 * @TODO: Make a new function for the "small words" remover. Prio: ~high
	 * @param (array) $argv     Args..
	 * @param (ref)   $helpRef  Just want to check the last key i $this->help, read the code to understand.  //TODO: Better comment...
	 * @param (array) $path     Path so I know where the command are, else I can't load it.
	 * @return void
	 */
	private function run($argv, &$helpRef, $path = '')
	{
	    $argv[0] = array_shift($this->aliasFix(array($argv[0]), $this->alias));
		
		//Remove words that don't exist in $help, Allow you to use "small words" E.g. boits plz make an table {{data1, data2}, {row2col1, row2col2}, ...,}
	    while(!isset($helpRef[$argv[0]]) AND !empty($argv)) {
		    array_shift($argv);
		}

		
		if(is_array($helpRef[$argv[0]]) AND !empty($argv)) //Verb (folder)
		{
		    $folder = array_shift($argv);
			
		    $this->run($argv, $helpRef[$folder], $path .'/'. $folder);
		}
		elseif(empty($argv)) //Dont exist
		{
		    echo "Kommandot finns inte! Se `Help` för att se alla kommandon.".nl;
		}
		else //Command / program
		{
			$command = array_shift($argv);

		    require_once($path .'/'. $command .'.php');
			
		    $reflect   = new ReflectionClass($command);
			//TODO: better solution, may require @param? Then count *, @param * could be if u use func-get_params..
			@$instance = $reflect->newInstanceArgs($argv) OR debug(ERROR_LVL_ERROR, "Something happend while trying to call '". $command ."::__construct((array) \$args)'. Maybe to few args. On line ". __LINE__ ." in botis.php.");
		}
	}
	
	
	//-------------------------------------------------------------------------------------------------
	// Init commands, etc
    //-------------------------------------------------------------------------------------------------    
    
	/**
	 * Läser in alla kommandon från (const) FUNCTION_FOLDER.
	 *
	 * @param (string) $dir = Directory with commands (functions) to init.
	 * @return void
	 */
	private function initCommands($dir)
	{		
		foreach(glob($dir . '/*') as $file)
		{
			if(is_dir($file)) {
			    $this->initCommands($file);
			}
			else
			{
				$pathinfo = pathinfo($file);
				
				if($pathinfo['extension'] == "php")
				{
				    if($this->checkSyntax($file)) //Good syntax
					{ 
						//Add file to the help ($this->help).
						$dir     = explode("/", substr($pathinfo['dirname'], strlen(BASEDIR . FUNCTION_FOLDER) + 1));
					    $helpRef =& $this->help; //Reference, all changes to $helpRef change $this->help too.
 
                        //$dir can be null, skip the loop
                        if(!empty($dir[0])) {
                            foreach($dir as $folder) {
                                $helpRef =& $helpRef[$folder];
                            }
                        }
                        
						
                        $helpRef[$pathinfo['filename']] = $this->getClassPhpDoc($file);
					}
					else {
					    //Bad syntax, skip the file.
						$this->debug['syntaxErrorIn'][] = $file;
						debug(ERROR_LVL_WARNING, $file ." has a syntax error, skiped it.");
					}
				}
				
			}
			
		}
	}
	
	
	/**
	 * Check for right syntax, like PHPDoc, comments etc.
	 *
	 * @param (string) $file Full path to a file.
	 * @return true if correct syntax, else false
	 */
	private function checkSyntax($file)
	{
	    $error = 0;
		
	    $data = $this->getFile($file);
		
	    //we love botis
		if(!preg_match("#//\(we love you botis <3\)[\s]+\?>[\s]*$#", $data)) {
		    $this->debug['syntaxError'][] = "Invalid EOF data. Stop hating botis! >:(";
		    $error++;	
		}
		

	    $tokensIWant = $this->getTokensIWant($data);
		$filename    = pathinfo($file, PATHINFO_FILENAME); //TODO: Check if class == $filename, then people can use multi classess.
		
		$prevWasDoc = 0; //0 = not at least a single line, 1 = at least a single line, 2 = 1 + @author and @example
		$classess   = 0;
		
		//At least one class and function. At least one line PHP doc before every class and function.
		//Classess require @author och @example
		
		if($tokensIWant !== false)
		{
		    foreach($tokensIWant as $key => $token)
		    {
		    	if(isset($token['class']) AND $prevWasDoc > 1)
		    	{
		    		$classess++;
		    	    $prevWasDoc = 0;
		    	}
		    	elseif(isset($token['class']) AND $prevWasDoc < 2) {
		    	    $this->debug['wrongDoc'][] = "Doc for classes require @author and an @example, and ofc a line about what it does.";
		    		$error++;
		    	}
		    	elseif(isset($token['function']) AND $prevWasDoc) {
		    	    $prevWasDoc = 0;
		    	}
		    	elseif(isset($token['function']) AND !$prevWasDoc) {
		    		$this->debug['noDoc'][] = "Document before every class and function!";
		    		$error++;
		    	}
		    	elseif(isset($token['doc']))
		    	{
		    		$doc = $token['doc'];
            
		    		//Remove PHPDoc's *, easier to match things then.
		    		$doc = preg_replace("#\n[ \t\v]*\*[ \t\v]*#", "\n", $doc);
		    		$doc = preg_replace("#^[ \t\v]*/\*\*#",       null, $doc);
		    		$doc = preg_replace("#[ \t\v]*/#",            null, $doc);
		    		
		    		//Bra dokumenterat?
		    		if(preg_match("#^". NL ."(.{10,70})". NL ."#", $doc))
		    		{
		    		    $prevWasDoc = 1;
		    			
		    		    if(preg_match("#\n@example (.+)\n#", $doc) AND
		    			    preg_match("#\n@author (.+)\n#",  $doc)) {
		    			    $prevWasDoc = 2;
		    			}
		    		}
		    		else {
		    		    $error++;
		    		    $this->debug['wrongDoc'][] = "One line (min 10, max 70 chars) about what the script does.";
		    		}
		    	}
		    }
		}
		else {
		    $this->debug['noDoc'][] = "Document before every class and function!";
			$error++;
		}
		
		if($classess !== 1) {
			$error++;
			$this->debug['2manyClasses'][] = "Found ". $classess . " except 1!";
		}
		

		return !$error;
	}
	
	
	/**
	 * Fetch specific PHP tokens.
	 *
	 * @param (string) $data = PHP source code to fetch tokens from
	 * @return (array) An 2d array with the tookens. Ie array(1 => array('doc' => '/** ... * /')2 => array('class' => 'make'));
	 */
	private function getTokensIWant($data)
	{
	   	$tokens      = token_get_all($data);
		$isClass     = false;
		$isFunction  = false;
		$i           = 0;
		$tokensIWant = false;
		
		//Get all tokens I want
		foreach($tokens as $key => $token)
		{
		    if(is_array($token))
			{
			    switch($token[0])
				{
				    case T_CLASS:       $isClass                      = true;      break;
				    case T_FUNCTION:    $isFunction                   = true;      break;
				    case T_DOC_COMMENT: $tokensIWant[$i++]['doc']     = $token[1]; break;
				    case T_COMMENT:     $tokensIWant[$i++]['comment'] = $token[1]; break;
				    case T_STRING:
					    if($isClass === true) {
				            $tokensIWant[$i++]['class'] = $token[1];
				            $isClass = false;
						}
						else if($isFunction === true) {
						    $tokensIWant[$i++]['function'] = $token[1];
					        $isFunction = false;
						}
					break;
				}
			}
		}
		
		return $tokensIWant;
	}
	
	
	/**
	 * Get the phpDoc for the first class in $file.
	 *
	 * @param (string) $file = File to get phpDoc from..
	 * @return (string) phpDoc _without_ the stars/asteriks (*).
	 */
	private function getClassPhpDoc($file)
	{
	    $data   = $this->getFile($file);
		$tokens = $this->getTokensIWant($data);
		
		$doc = null;
		
		foreach($tokens as $token)
		{
			if(isset($token['doc']))
			{
				$doc = $token['doc'];
			}
			elseif(isset($token['class']) AND $doc !== null)
			{
			    //Remove the starting /* *, the * before every line, and the ending */
				$doc = preg_replace("#\n[ \t\v]*\*[ \t\v]*#", "\n", $doc);
				$doc = preg_replace("#^[ \t\v]*/\*\*#",       null, $doc);
				$doc = preg_replace("#[ \t\v]*/#",            null, $doc);
				
				return $doc;
			}
		}
		
	}
	 
	
	/**
	 * Returns the content of $file
	 */
	private function getFile($file)
	{
	    $fh   = fopen($file, 'r') OR die(debug(ERROR_LVL_ERROR, "I can't haz '". $file ."' :( !!"));
		$data = stream_get_contents($fh);
		fclose($fh);
		
		return $data;
	}
	
	
	
	//-------------------------------------------------------------------------------------------------
	// Config
	//-------------------------------------------------------------------------------------------------
	
	/**
	 * Init the configfile.
	 *
	 * @TODO: A better explanation here..
	 *
	 * @global Define everything in section @@Main config. I.e: define("input", "$_");
	 * @global $color (array) Colors to use. array('red' => *ascii escape code*, ..., 'bg' => array('red' => *ascii escape code*, ...));
	 * @return $alias, ie $alias[??] = help;
	 */
    private function config()
	{
	    //Open the configfile
	    $fh      = fopen(CONFIGFILE, 'r');
		$iniData = stream_get_contents($fh);
		fclose($fh);
		
		
		//Botis ini to normal ini
		$iniData = preg_replace("~\r\n~", "\n", $iniData);                           //\r\n => \n
		$iniData = preg_replace("~^[^\.]+\.~", "", $iniData);                        //Remove the header
		$iniData = preg_replace("~\n@@([^\n]+)\n~", "[$1]\n", $iniData);             //@@Main config => [Main config]
		$iniData = preg_replace("~^#~m", ";", $iniData);                             //# => ;
		$iniData = preg_replace("~^([^=\n]+)=\s([^\n]+)$~m", '$1 = "$2"', $iniData); //x = y => x = "y"
		
		//Must have some love
		if(!preg_match("~\(we love you botis <3\)$~", $iniData)) {
		    exit("Config gile broken, not just syntax.. :(");
		}
		
		$iniData = preg_replace("~\(we love you botis <3\)$~", "", $iniData);
		$iniData = trim($iniData);
		
		
		$ini = parse_ini_string($iniData, true);
		
		//@@Main config
		foreach($ini['Main config'] as $key => $val)
		{
			$key = strtoupper(trim($key));
			$val = trim($val);
			
			//Add an space to output and input, a nicer look, an ugly hack.
			if($key == "OUTPUT" OR $key == "INPUT") {
			    $val .= ' ';
			}
			
			//Booleans
			if($val == "true") {
			    $val = true;
			}
			elseif($val == "false") {
			    $val = false;
			}
			
			
			define($key, $val);
		}
		
		
        //@@Alias
		foreach($ini['Alias'] as $realCmnd => $val)
		{
			foreach(explode(", ", trim($val)) as $aliasByUsr) {
			    $alias[$aliasByUsr] = $realCmnd;
			}
		}
		
		//@@Colors
		global $color;
		
		foreach($ini['Colors'] as $color2 => $code)
		{
		    $color[$color2] = chr(27) ."[". $code ."m";
		}
		
		foreach($ini['BGColors'] as $color2 => $code)
		{
		    $color["bg"][$color2] = chr(27) ."[". $code ."m";
		}
		
		
		return $alias;
	}
	
	
	/**
	 * Replace alias with the real word. I.g: ?? => help, cya => exit, bai => exit.
	 *
	 * @param  (array) $argv   = Args to replace
	 * @param  (array) $alias  = Alias (the replacment), e.g array('??' => 'help', '*from*' => '*to*')
	 * @return (array) Riktiga kommandona
	 */
	private function aliasFix($argv, $alias)
	{
	    //Escape regex characters.
	    $regexpMeta = array('\\' => '\\\\',
		                    '^'  => '\^',
                            '$'  => '\$',
                            '.'  => '\.', 
                            '['  => '\[', 
                            ']'  => '\]',
                            '|'  => '\|',
                            '('  => '\(',
                            ')'  => '\)',
                            '?'  => '\?',
                            '*'  => '\*',
                            '+'  => '\+',
                            '{'  => '\{',
                            '}'  => '\}');
							
        foreach($alias as $ali => $real)
		{
		    $ali    = "/^". trim(strtr($ali, $regexpMeta)) ."$/";
            $from[] = $ali;
			$to[]   = $real;
        }
		
		
		//Replace alias with real word
	    foreach($argv as $key => $val) {
		    $argv[$key] = trim(preg_replace($from, $to, $argv[$key]));
		}
		
		return $argv;
    }

	
	
    //-------------------------------------------------------------------------------------------------
	// Hard coded functions. / Basic function.
	//-------------------------------------------------------------------------------------------------

    /**
	 * @TODO: Comment dis
	 * @TODO: A table for all @phpdoc data. (Like Author    - Sony? aka Sawny
	 *                                            Copyright - ...
	 *                                            Example   - ...)
	 * @TODO: New format, see plannering.php
	 */
	private function help($args)
	{
	    echo nl ."    -- Help --". nl;

		$helpRef =& $this->help;
		
		//Get help about right thing
		foreach($args as $key => $func) {
		    if(isset($helpRef[$func])) {
		        $helpRef =& $helpRef[$func];
			}
		}
		
		//Get longest word to get nice indentation
		if(is_array($helpRef)) {
		    foreach($helpRef as $key => $val) {
		        $longestWord = ($longestWord < strlen($key)) ? strlen($key) : $longestWord;
		    }
		}
		
		//Print the help message
		if(is_array($helpRef))
		{
		    foreach($helpRef as $key => $val) 
			{
		        $buf = str_pad($key, $longestWord, ' ', STR_PAD_RIGHT);
		    	
		    	if(is_array($val))
				{
				    $buf .= " - ";
				
		    	    foreach($val as $key2 => $val2) {					    
						$key2 = (is_array($val2)) ? $key2 . '~' : $key2 .'°'; //Is folder? or file:
						
		    		    $buf .= $key2 .", ";
		    		}
					
					$buf = substr($buf, 0, -2);
		    	}
				else { //File
				    $buf = trim($buf) . '°';
				}
		    	
		    	echo $buf . nl;
		    }
		}
		else
		{
		    //Get the phpDoc content and the phpDoc tags.
		    $phpDoc        = explode("\n@", $helpRef);
			$phpDocContent = array_shift($phpDoc); //TODO: Better name, phpDocNoTag, phpDocText.. hmm
			
			foreach($phpDoc as $val) {
			    list($tag, $val)  = explode(" ", $val, 2);
			    $phpDocTags[$tag] = $val;
			}
			
			
			//Longest word so we can get nice indenting
			foreach($phpDocTags as $tag => $val) {
		        $longestWord = ($longestWord < strlen($tag)) ? strlen($tag) : $longestWord;
		    }
			
			$buf = '----------------~'. nl;
			
			foreach($phpDocTags as $tag => $val) {
			    $buf .= str_pad(ucfirst($tag), $longestWord, ' ', STR_PAD_RIGHT);
				$buf .= ' - '. ucfirst($val) . nl;
			}
			
		    echo $phpDocContent . $buf;
		}
	}
	

    /**
     * Clear the screen
	 *
	 * If the user have ESC codes (On windows ANSICON) the screen will be cleard like cls / clear.
	 * Else the script just output 500 new lines
     */	
    private function clearScreen()
	{		
		if(HAS_COLORS) { //Then they have ESC codes and can clear screen
		    echo chr(27) ."[2J";
		}
		else
		{
		    for($i = 0; $i < 500; $i++) {
		        echo nl;
	        }
		}
		
	}
}



//-------------------------------------------------------------------------------------------------
// Public helpers & variables
//-------------------------------------------------------------------------------------------------

//Color array
$color = array(); //boits::config will fill this array. 


/**
 * Debug something
 *
 * @example $debug(ERROR_LVL_DEBUG, "User array: ", $userArray);
 * @param (int)    $lvl  = Error_lvl
 * @param (string) $mess = Messages to output
 * @param (*)      $var* = Optional var to debug.
 * @TODO: Check if you can check resource type, and if its a file handler. Check if the file exist, can be writen to, readed etc.
 */
function debug($lvl, $mess, $var = null)
{
    global $color;
	
    if(ERROR_LVL >= $lvl)
	{
	    switch($lvl)
		{
			case 1: echo ((HAS_COLORS) ? $color['bg']['red']    :'') . "Error!".   nl; break;
		    case 2: echo ((HAS_COLORS) ? $color['bg']['yellow'] :'') . "Warning!". nl; break; 
			case 3: echo ((HAS_COLORS) ? $color['bg']['blue']   :'') . "Debug!".   nl; break;
		}
		
		echo (HAS_COLORS) ? $color['bg']['black']:'';
		echo $mess . nl;
		
		if($var !== null)
		{
		    switch(gettype($var))
			{
			    case "array":
				case "object":
				    print_r($var);
				break;
				
				case "integer":
				case "double":
				case "boolean":
				    var_dump($var);
				break;
				
				case "string":
				    echo $var;
				break;
				
				case "resource":
				    echo get_resource_type($var);
				break;
				
				default:
				    var_dump($var);
				break;
			}
			
			echo nl;
		}
	}
}


/**
 * Split a string on spaces, but not in quotes or in curly brackets.
 *
 * @TODO: RENAME THE FUNCTION, Prio: High
 * @example argvFix('word1 word2 "a sentence" {{an, object} {or, something, like} that} ok');
            -> array('word1', 'word2', 'a sentecne', '{{an, object} {or, something, like} that}', 'ok');
 * @param (string) $usrinp
 * @return (array) I.e array('convert', ''
 */
function argvFix($usrinp)
{
	    //Split by space, but not in quotes. Thanks to Bart Kiers@http://stackoverflow.com/a/2202489/996028
		preg_match_all("~(\"(?:\\\\.|[^\\\\\"])*\")|('(?:\\.|[^\\'])*')|\S+~", $usrinp, $argv); //("(?:\\.|[^\\"])*")|('(?:\\.|[^\\'])*')|\S+
		$argv   = array_map(create_function('$a', 'return trim($a,  \'"\\\'\');'), $argv[0]);
		
		
		//..and dont split spaces in curly brackets.
		$curlyBrackets = 0;
		$buf           = '';
		$splitOn       = '%|youWillNeverTypeThisIHopeåäöÅÄÖ92'.chr(1).rand(); //Something random, to split on later.  TODO: better solution
		
		foreach($argv as $key => $val) {
		
			if($curlyBrackets <= 0) {
			    $buf .= $splitOn;
			}
			
			$buf .= $val . ' '; //preg_match_all removes spaces, TODO: this is an lazy fix
		
			$curlyBrackets += substr_count($val, "{"); //Easy to change if you like [] more then {}, or dont like it at all. (Change and try!)
			$curlyBrackets -= substr_count($val, "}");
		}
		
		$buf  = substr($buf, strlen($splitOn));
		$argv = explode($splitOn, $buf);
		$argv = array_map('trim', $argv); //Lazy fix //TODO: lazy fix 3 what?
		
		return $argv;
	}



//-------------------------------------------------------------------------------------------------
// Functions that may not exist
//-------------------------------------------------------------------------------------------------

/**
 * If parse_ini_string doesn't exist, create it.
 *
 * @author Sony? aka Sawny
 */
if(!function_exists('parse_ini_string'))
{
    function parse_ini_string($str, $process_sections = false) //Note: No $scanner_mode cuz this code will just be for PHP <5.3.0 users, and $scanner_mode was added in 5.3.0.
	{
	    //Create a temp file, so we can use parse_ini__file__
        $tmpFile = tempnam(sys_get_temp_dir(), "BTS");
		$fh      = fopen($tmpFile, 'w') OR print("Can't open '$tmpFile' for writing!\n");
		fwrite($fh, $str);
        fclose($fh);
		
		$ini = parse_ini_file($tmpFile, $process_sections);
		
		unlink($tmpFile) OR print("Can't delete '$tmpFile'.\n");
		
		return $ini;
    }
}



//-------------------------------------------------------------------------------------------------
// Botis, wake up! :D *bow*
//-------------------------------------------------------------------------------------------------

new botis($argv);

//(we love you botis <3)
?>