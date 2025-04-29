<?php
// Configuration
$apiKey = 'TFZNe5onqZ97HNmY3T6OwVzvypbZ99daMiRx5Pax'; // Replace with your Firebase API Key
$projectId = 'codingmania-4fec0'; // Replace with your Firebase Project ID
$baseURL = "https://firestore.googleapis.com/v1/projects/$projectId/databases/(default)/documents/questions";
require './code_format.php';
if (isset($_GET['id']) && !empty($_GET['id']) && isset($_GET['language']) && !empty($_GET['language'])) {
    $questionId = $_GET['id']; // Keep as string
    $language = $_GET['language']; // Keep as string
} else {
    die("❌ No question ID or language received.");
}

// echo "<script>alert('✅ Question ID: $questionId, Language: $language received');</script>";


// Function to find the correct Firestore document ID
function findQuestionDocumentId($baseURL, $questionId, $apiKey) {
    $url = "$baseURL?key=$apiKey";
    $options = ['http' => ['method' => 'GET']];
    $context = stream_context_create($options);
    $result = @file_get_contents($url, false, $context);

    if ($result === FALSE) return null;

    $documents = json_decode($result, true);
    if (isset($documents['documents'])) {
        foreach ($documents['documents'] as $document) {
            if ($document['fields']['id']['stringValue'] == $questionId) {
                return basename($document['name']); // Extract the Firestore document ID
            }
        }
    }
    return null;
}

// Step 1: Find the correct document ID
$correctDocId = findQuestionDocumentId($baseURL, $questionId, $apiKey);
if (!$correctDocId) die("No matching question found for ID: $questionId");

// Step 2: Fetch Java answer from answers subcollection
$answersURL = "$baseURL/$correctDocId/answers";

function retrieveAnswer($answersURL, $apiKey,$language) {
    $url = "$answersURL?key=$apiKey";
    $options = ['http' => ['method' => 'GET']];
    $context = stream_context_create($options);
    $result = @file_get_contents($url, false, $context);

    if ($result === FALSE) return "⚠️ No solution found for $language.";

    $documents = json_decode($result, true);
    if (isset($documents['documents'])) {
        foreach ($documents['documents'] as $document) {
            if (isset($document['fields'][$language]['stringValue'])) {
                return $document['fields'][$language]['stringValue'];
            }
        }
    }
    return "⚠️ No solution found for $language.";
}

// Step 3: Fetch the solution
$solution = retrieveAnswer($answersURL, $apiKey, $language);

if($language=='cpp' || $language =='c++'){
    $solution = code_format::cpp($solution);
}
else if($langugae=='python'){
    $solution = code_format::python($solution);
} else if($langugae=='php'){
    $solution = code_format::php($solution);
}

header("Content-Type: text/plain"); // Ensure plain text response
echo $solution; // Return only the solution text

?>
