<?php
header('Content-Type: application/json');

// Enable error reporting and logging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error_log.txt');

// Include database connection configuration
include '../db.php'; // Adjust the path as per your actual setup

// Function to send JSON response
function sendResponse($success, $message, $output = '') {
    $response = array(
        'success' => $success,
        'message' => $message,
        'output' => $output
    );
    echo json_encode($response);
    exit;
}

// Get the raw POST data
$rawData = file_get_contents("php://input");
$data = json_decode($rawData, true);

// Check JSON decoding errors
if (json_last_error() !== JSON_ERROR_NONE || !is_array($data)) {
    sendResponse(false, 'Invalid JSON data');
}

// Validate input
if (!isset($data['questionId'], $data['code'], $data['language'])) {
    sendResponse(false, 'Invalid request');
}

$questionId = (int) $data['questionId'];
$code = $data['code'];
$language = $data['language'];

// Fetch test cases for the question
$sql = "SELECT testcase, output FROM challenge WHERE question_id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    sendResponse(false, 'Failed to prepare SQL statement');
}

$stmt->bind_param("i", $questionId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    sendResponse(false, 'No test cases found for the question');
}

$allPassed = true;
$executionOutput = '';

while ($row = $result->fetch_assoc()) {
    $expectedOutput = trim($row['output']);
    $input = trim($row['testcase']);
    $output = executeCode($code, $language, $input); // Replace with actual code execution function

    // Debugging output
    error_log("Test Case: Input=$input, Expected Output=$expectedOutput, Actual Output=$output");

    if ($output !== $expectedOutput) {
        $allPassed = false;
        $executionOutput = $output;
        break;
    }
}

if ($allPassed) {
    sendResponse(true, 'All test cases passed');
} else {
    sendResponse(false, 'Some test cases failed', $executionOutput);
}

// Function to execute code (replace with actual code execution logic)
function executeCode($code, $language, $input) {
    // Example: Using Python to execute code
    if ($language === 'Python') {
        // Execute Python code using a temporary file approach for large inputs
        $inputFile = tempnam(sys_get_temp_dir(), 'input_');
        file_put_contents($inputFile, $input);

        // Escaping the code properly for shell execution
        $escapedCode = escapeshellarg($code);

        // Example command to execute Python code from a file
        $output = shell_exec("python $escapedCode < $inputFile"); // Example command, replace with actual execution logic

        // Clean up temporary file
        unlink($inputFile);

        if ($output === null) {
            return ''; // Return empty string if output is null
        }

        return trim($output); // Return trimmed output
    }
    // Add support for other languages as needed

    return ''; // Return empty string if language not supported or execution fails
}
?>
