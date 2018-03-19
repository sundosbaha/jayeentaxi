<?php

class SystemController extends BaseController {


    public function system_command_unzip()
    {


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "hi s";
echo '<pre>';

// Outputs all the result of shellcommand "ls", and returns
// the last output line into $last_line. Stores the return value
// of the shell command in $retval.


//cd.. id used to before current directory
// && used for to pipe the next command
//cd ..
$last_line = system('cd .. && cd .. && ls', $retval);
//$last_line = system('cd .. && cd .. &&  unzip wallet20171011.zip -d ./taxiappz', $retval);
//$last_line = system('cd .. && cd .. && unzip wallet20171011.zip ', $retval);
//$last_line = system('cd .. && cd app && ls ', $retval);


echo '
</pre>
<hr />Last line of the output: ' . $last_line . '
<hr />Return value: ' . $retval;

    }
    public function system_command_zip()
    {


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "hi s";
echo '<pre>';

// Outputs all the result of shellcommand "ls", and returns
// the last output line into $last_line. Stores the return value
// of the shell command in $retval.


//cd.. id used to before current directory
// && used for to pipe the next command
//cd ..
        //$last_line = system('cd .. && cd .. && zip -r product20171103.zip  ./wallet ', $retval);
        $last_line = system('cd .. && cd .. && ls ', $retval);
//$last_line = system('cd .. && cd .. && mv ./taxiappz_old/taxiappz taxiappz ', $retval);
//$last_line = system('cd .. &&  cd app && zip -r lang_28_jun_2017.zip  ./lang ', $retval);
//$last_line = system('cd .. && ls && zip -r itaxi_jun.zip  ./itaxi', $retval);



echo '
</pre>
<hr />Last line of the output: ' . $last_line . '
<hr />Return value: ' . $retval;

    }





}