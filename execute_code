<?php
header('Content-Type: application/json');

// Retrieve the JSON input
$input = json_decode(file_get_contents('php://input'), true);
$language = $input['language'];
$code = $input['code'];
$output = '';

// Set file paths for each language
switch ($language) {
    case 'php':
        $filePath = 'temp.php';
        file_put_contents($filePath, $code);
        $command = "php $filePath";
        break;
    case 'python':
        $filePath = 'temp.py';
        file_put_contents($filePath, $code);
        $command = "python3 $filePath";
        break;
    case 'cpp':
        $filePath = 'temp.cpp';
        $executablePath = 'temp.out';
        file_put_contents($filePath, $code);
        $command = "g++ $filePath -o $executablePath && ./$executablePath";
        break;
    default:
        echo json_encode(['output' => 'Unsupported language']);
        exit;
}

// Execute the command and capture the output
exec($command . ' 2>&1', $outputLines, $returnVar);
$output = implode("\n", $outputLines);

// Clean up temporary files
unlink($filePath);
if ($language == 'cpp') unlink($executablePath);

// Return the output as JSON
echo json_encode(['output' => $output]);
?>
