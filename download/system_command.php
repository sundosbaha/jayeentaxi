<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "hi ";
echo '<pre>';

// Outputs all the result of shellcommand "ls", and returns
// the last output line into $last_line. Stores the return value
// of the shell command in $retval.


//cd.. id used to before current directory
// && used for to pipe the next command

$last_line = system('cd .. && ls && zip -r itaxi_jun.zip  ./itaxi', $retval);


echo '
</pre>
<hr />Last line of the output: ' . $last_line . '
<hr />Return value: ' . $retval;
?>
