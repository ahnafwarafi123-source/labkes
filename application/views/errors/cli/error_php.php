<?php
defined('BASEPATH') OR exit('No direct script access allowed');

echo "\nA PHP Error was encountered\n\n",
	'Severity:    ', $severity, "\n",
	'Message:     ', $message, "\n",
	'Filename:    ', $filepath, "\n",
	'Line Number: ', $line;

if (defined('SHOW_DEBUG_BACKTRACE') && SHOW_DEBUG_BACKTRACE === TRUE)
{
	echo "\n\nBacktrace:\n";
	foreach (debug_backtrace() as $error)
	{
		if (isset($error['file']) && strpos($error['file'], realpath(BASEPATH)) !== 0)
		{
			echo "\n\tFile: ", $error['file'], "\n\t\tLine: ", $error['line'], "\n\t\tFunction: ", $error['function'], "\n";
		}
	}
}

echo "\n\n";

