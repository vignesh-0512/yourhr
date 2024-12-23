<?php
$pythonPath = 'D:\php project\htdocs\hope_project\Python312\python.exe';
$command = "$pythonPath -c \"print('Hello, World!')\"";
$output = shell_exec("$command 2>&1");

echo "Test command: $command\n";
echo "Output: $output\n";

if ($output === null) {
    echo "Failed to execute the Python code.";
}
?>
