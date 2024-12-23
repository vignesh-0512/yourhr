<?php
header('Content-Type: application/json');

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Log file to capture errors
$logFile = __DIR__ . '/log.txt';
function logMessage($message) {
    global $logFile;
    file_put_contents($logFile, $message . PHP_EOL, FILE_APPEND);
}

// Get the raw POST data
$rawData = file_get_contents("php://input");
$data = json_decode($rawData, true);

// Check JSON decoding errors
if (json_last_error() !== JSON_ERROR_NONE || !is_array($data)) {
    http_response_code(400);
    $response = array('success' => false, 'error' => 'Invalid JSON data');
    logMessage("Response: " . json_encode($response));
    echo json_encode($response);
    exit;
}

logMessage("Received data: " . print_r($data, true));

// Validate input
if (!isset($data['questionId'], $data['code'], $data['language'])) {
    http_response_code(400);
    $response = array('success' => false, 'error' => 'Invalid request');
    logMessage("Response: " . json_encode($response));
    echo json_encode($response);
    exit;
}

$questionId = $data['questionId'];
$code = $data['code'];
$language = $data['language'];

// Prepare sandbox directory and code file
$sandboxDir = __DIR__ . '/sandbox';
if (!is_dir($sandboxDir)) {
    mkdir($sandboxDir, 0777, true);
}

switch ($language) {
    case 'Python':
        $pythonPath = 'D:\\php project\\htdocs\\Python312\\python.exe'; 
        $fileExtension = 'py';
        $codeFile = $sandboxDir . "/main.$fileExtension";
        file_put_contents($codeFile, $code);
        $command = escapeshellarg($pythonPath) . ' ' . escapeshellarg($codeFile);
        break;

    case 'Python':
            $javaPath = 'D:\\php project\\htdocs\\Python312\\python.exe'; 
            $fileExtension = 'java';
            $codeFile = $sandboxDir . "/main.$fileExtension";
            file_put_contents($codeFile, $code);
            $command = escapeshellarg($pythonPath) . ' ' . escapeshellarg($codeFile);
            break;
    default:
        http_response_code(400);
        $response = array('success' => false, 'error' => 'Unsupported language');
        logMessage("Response: " . json_encode($response));
        echo json_encode($response);
        exit;
}

// Execute the command
logMessage("Executing Command: $command");
$output = shell_exec($command . " 2>&1");
logMessage("Output: $output");

// Clean up code file
unlink($codeFile);

// Respond with output or error
$response = array('success' => true, 'output' => $output);
logMessage("Response: " . json_encode($response));
echo json_encode($response);
?>
