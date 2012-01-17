<?php

//Convert all files from $charsetFrom to $charsetTo in $dir that has any extension listed in $convertExt.
//OBS! Notepad++ will show OEM-US files as ANSI, so you need to change the charset. See images/charset.png for how you do that.
//TODO: Make it to a botis function

$charsetFrom = "UTF-8";
$charsetTo   = "CP437"; //US-ascci, OEM-437 OEM-us (same thing).
$convertExt  = array('php'); //no dot


if(!isset($argv[1])) {
    exit("convertCharset *fulldir*\nConvert all .php files in *fullDir* from UTF-8 to CP437.\nE.g. converCharset X:/PHP/botis (No traling slash!)");
}

main($argv[1]);


function convertFile($file)
{
    global $charsetFrom, $charsetTo;
	
    $fh   = fopen($file, 'rb');
    $data = stream_get_contents($fh);
    fclose($fh);
    
    $data = iconv($charsetFrom, $charsetTo ."//IGNORE", $data);
    
    $fh = fopen($file, 'wb');
    fwrite($fh, $data);
    fclose($fh);
}

function main($dir)
{
    global $charsetFrom, $charsetTo, $convertExt;

	if(is_dir($dir))
	{
	    foreach(glob($dir . '/*') as $file) {
		    
			$ext  = pathinfo($file, PATHINFO_EXTENSION);
			$file = $file;
			
			
		    if(is_dir($file)) {
			    echo "Read dir '$file'.\n";
			    main($file);
			}
			elseif(in_array($ext, $convertExt))
			{
			    echo "Converting '$file' from $charsetFrom to $charsetTo.\n";
			    convertFile($file);  
			}
		}
	}
	else
	{
	    die("'". $dir ."' is not an dir!");
	}
	
}

?>