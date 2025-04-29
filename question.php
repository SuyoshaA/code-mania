<?php
// Configuration
$apiKey = 'TFZNe5onqZ97HNmY3T6OwVzvypbZ99daMiRx5Pax'; // Replace with your Firebase API Key
$projectId = 'codingmania-4fec0'; // Replace with your Firebase Project ID
$collection = 'questions'; // Your Firestore collection name
$baseURL = "https://firestore.googleapis.com/v1/projects/$projectId/databases/(default)/documents/$collection";

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Function to retrieve a single question by ID
function retrieveQuestionById($apiKey, $baseURL, $id) {
    $url = "$baseURL?key=$apiKey";
    $options = ['http' => ['method' => 'GET']];
    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);

    if ($result === FALSE) {
        echo "Error fetching data from Firebase.";
        return null;
    }

    $documents = json_decode($result, true);

    // Check if 'documents' exists in the response
    if (isset($documents['documents'])) {
        foreach ($documents['documents'] as $document) {
            $fields = $document['fields'];
            $docId = $fields['id']['stringValue'] ?? ''; // Treat 'id' as a string
            $questionText = $fields['question']['stringValue'] ?? '';

            // Match the requested ID
            if ((string)$docId === (string)$id) {
                return [
                    'id' => $docId,
                    'question' => $questionText
                ];
            }
        }
    }

    return null; // If no match found
}

// Retrieve the question ID from the request
$currentId = isset($_GET['id']) ? (int)$_GET['id'] : 1;

// Fetch the question with the current ID
$question = retrieveQuestionById($apiKey, $baseURL, $currentId);

// Return the question as a response
if ($question) {
    echo '<div><strong>Question ID:</strong> ' . htmlspecialchars($question['id']) . '<br>';
    echo '<strong>Question:</strong> ' . htmlspecialchars($question['question']) . '</div>';
} else {
    echo '<div>No question found for ID ' . htmlspecialchars($currentId) . '.</div>';
}
?>