<?php

/**
 * Simple test function, to se if this workz.
 *
 * @author Sony? aka Sawny
 * @example test Sony "for security" cheese
 * @TODO: Make a advance test. Check all functions etc.. color support, blahblah.
 */
class test
{
    /**
	 * The main function own
	 */
    function __construct($name, $test, $things, $day = 'mondays')
	{
	    echo $this->getName($name) . " like to test ". $test ." with ". $things . "espacilly on ". $day;
	}
	
	/**
	 * Capilize first char in $name
	 *
	 * @return $n with first char cailized
	 */
	private function getName($n)
	{
	    return ucfirst($n);
	}
}

//(we love you botis <3)
?>