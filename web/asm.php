<?php
$descriptorspec = array(
    0 => array("pipe", "r"),
    1 => array("pipe", "w"),
    2 => array("file", "/tmp/error-output.txt", "a")
);

$cwd = 'NULL';
$env = array('IN_PHP' => 'true');

if(!file_exists('gasm')) {
	system('g++ gsqasm.cpp -o gasm');
}

system('ulimit -Sv 50000'); //Save most computers from annihilation in case of error.

$process = proc_open('./gasm', $descriptorspec, $pipes, $cwd, $env);

if (!isset($_POST['input'])) {
    echo "Input is empty.";
    die;
}

if (is_resource($process)) {
    fwrite($pipes[0], $_POST['input']);
    fclose($pipes[0]);
    echo stream_get_contents($pipes[2]);
     fclose($pipes[2]);
    echo "-----------<br>";
    echo stream_get_contents($pipes[1]);
    fclose($pipes[1]);
    $return_value = proc_close($process);
    if($return_value == 137)
        echo "Compiler was running for too long and was killed. Please try compiling your code on your own machine.";
}
