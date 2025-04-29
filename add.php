<?php
// Configuration
$apiKey = 'TFZNe5onqZ97HNmY3T6OwVzvypbZ99daMiRx5Pax'; // Replace with your Firebase API Key
$projectId = 'codingmania-4fec0'; // Replace with your Firebase Project ID
$collection = 'users'; // Firestore collection name
$baseURL = "https://firestore.googleapis.com/v1/projects/$projectId/databases/(default)/documents/$collection";

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Function to find user by email
function findUser($baseURL, $email) {
    global $apiKey;
    $url = "$baseURL?key=$apiKey";
    $options = ['http' => ['method' => 'GET']];
    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);

    if ($result === FALSE) {
        echo "Error fetching data from Firebase.";
        return false;
    }

    $documents = json_decode($result, true);

    // Check if 'documents' exists in the response
    if (isset($documents['documents'])) {
        foreach ($documents['documents'] as $document) {
            $fields = $document['fields'];
            $docEmail = $fields['email']['stringValue'] ?? '';

            if ($docEmail === $email) {
                return true;
            }
        }
    }

    return false;
}

// Function to add a user to Firestore
function addUser($email, $hashedPassword,$fullname) {
    global $baseURL, $apiKey;

    // Prepare user data
    $userData = [
        'fields' => [
            'email' => ['stringValue' => $email],
            'password' => ['stringValue' => $hashedPassword],// Store the hashed password
            'fullname' => ['stringValue' => $fullname],
        ]
    ];

    // Initialize cURL to send POST request
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "$baseURL?key=$apiKey"); // Add API key as a query parameter
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($userData));

    $response = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($response, true);

    // Return user ID if successfully added, otherwise null
    return isset($data['name']) ? $data['name'] : null;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $fullname=$_POST['fullname'];

    // Hash the password before saving
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    if (findUser($baseURL, $email)) {
        echo "<script>alert('Email already exists. Please use a different email.'); window.location.href = 'register.php';</script>";
        exit();
    } else {
        $userId = addUser($email, $hashedPassword,$fullname); // Use the hashed password
        if ($userId) {
            echo "<script>alert('User registered successfully!'); window.location.href = 'default.php';</script>";
        } else {
            echo "Failed to register user.";
        }
        exit();
    }
}
?>
