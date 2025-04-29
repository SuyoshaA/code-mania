<?php
// Configuration
$apiKey = 'TFZNe5onqZ97HNmY3T6OwVzvypbZ99daMiRx5Pax'; // Replace with your Firebase API Key
$projectId = 'codingmania-4fec0'; // Replace with your Firebase Project ID
$collection = 'users'; // Your Firestore collection name
$baseURL = "https://firestore.googleapis.com/v1/projects/$projectId/databases/(default)/documents/$collection";

// Function to create a document
function createDocument($apiKey, $baseURL, $email, $password) {
    $url = "$baseURL?key=$apiKey";
    
    $data = [
        'fields' => [
            'email' => ['stringValue' => $email],
            'password' => ['stringValue' => $password]
        ]
    ];

    $options = [
        'http' => [
            'header'  => "Content-type: application/json\r\n",
            'method'  => 'POST',
            'content' => json_encode($data),
        ],
    ];

    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);

    if ($result === FALSE) {
        die('Error creating document');
    }

    return json_decode($result);
}

// Function to read a document
function readDocument($apiKey, $baseURL, $documentId) {
    $url = "$baseURL/$documentId?key=$apiKey";

    $options = [
        'http' => [
            'method' => 'GET',
        ],
    ];

    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);

    if ($result === FALSE) {
        die('Error reading document');
    }

    return json_decode($result);
}

// Function to update a document
function updateDocument($apiKey, $baseURL, $documentId, $fields) {
    $url = "$baseURL/$documentId?key=$apiKey";

    $data = [
        'fields' => $fields
    ];

    $options = [
        'http' => [
            'header'  => "Content-type: application/json\r\n",
            'method'  => 'PATCH',
            'content' => json_encode($data),
        ],
    ];

    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);

    if ($result === FALSE) {
        die('Error updating document');
    }

    return json_decode($result);
}

// Function to delete a document
function deleteDocument($apiKey, $baseURL, $documentId) {
    $url = "$baseURL/$documentId?key=$apiKey";

    $options = [
        'http' => [
            'method' => 'DELETE',
        ],
    ];

    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);

    if ($result === FALSE) {
        die('Error deleting document');
    }

    return json_decode($result);
}

// Example usage
try {
    // Create a document
    $response = createDocument($apiKey, $baseURL, 'oawan@pawan.com', 'securepassword');
    echo "Document created: ";
    print_r($response);

    // Read a document
    $documentId = $response->name; // Get the document ID from the creation response
    $response = readDocument($apiKey, $baseURL, $documentId);
    echo "Document read: ";
    print_r($response);

    // Update a document
    $fields = [
        'password' => ['stringValue' => 'newpassword']
    ];
    $response = updateDocument($apiKey, $baseURL, $documentId, $fields);
    echo "Document updated: ";
    print_r($response);

    // Delete a document
    $response = deleteDocument($apiKey, $baseURL, $documentId);
    echo "Document deleted: ";
    print_r($response);
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}
?>
