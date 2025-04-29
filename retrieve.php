<?php
require 'vendor/autoload.php';
error_reporting(E_ALL);
ini_set('display_errors', 1); // Enable error reporting for debugging

require 'vendor/autoload.php'; // Include Composer's autoloader

use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

// Use your service account credentials
$serviceAccount = ServiceAccount::fromJson([
    "type" => "service_account",
    "project_id" => "codingmania-4fec0",
    "private_key_id" => "2fdba2c5db5d9913b3fc42f8a064803aae33c553",
    "private_key" => "-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQCpTRDjh0HE4yWy\n5mBTeXsuhpFGYV0kp4JMYsfWQODntb6dfPu8Z2ClKoYsDRtZoxymqYYNTnZmY+Wc\nHrOzRjJMXbkcEhFqczHH91Kl6xqQ29ql+dIYXPB8bDvkAu1gfXVV1wQ3jaBhJswO\nshfOYZqUFMOB08W2GaoAfHrxfRw2M435o4Urc60gbPKUP+mm9xPccVu9vqOsd5OW\nVnuKYraDzFUYnIwyQGq2mzDSf5EABW2+oBamALv1z5ROVWm1Ig2N3J4bXJWNQpIp\n1BHIeAbZFntAKjgyueuC+OtGXnRVl0KsChnHMB0BGpn9zPrU/bfw73+KzLz4ohrz\nHpbd5593AgMBAAECggEABNXkqb/snqo7Is27OQ6E5MnpndyJIUl/hYnF92oEgndE\nvWnf/Y/oK4WpaO3rNtT5DoVAticLthFDchUCtxf3QLu+aBEmgZOxBWFDyfkU7aBr\nJ7Nd4tPyRifM7rq5wFXGPAQIn+Cm6WlL9iKRmfYNjea8/2fTDVv8I9IvSf7CfYV4\nXdi1JiY7e2xmXTkQVRyMMFAsHRsQWUhj0jTYKZdUKMw0IgLpVqjn6bh9OLqunbXw\n87vzfOzpJDP3QGSQ9hT3bu+BMCG/9LvWoIT8C1O1AZ+v3+GHDbgtHKI2wbOOlwuV\nq9oAHyHycHy2JcrzkTts4lrSgkrp+KHzxVyq6umU8QKBgQDtsu0wC0kwOWvCTzOY\nj9nMotTw46FWrqqupweNrZX2uW8A4JRdk0BOp7nVRGd43V4bjzwf1q0+OC13k0nd\nJGF5GFQ9y2ScJthfCt7Et5wQvpRVs0GT17q3xS9WlZJLdPuXHc5rfx1ZcKiKYyhS\nh89cH1FVJZRjs1bwRao8yFicSwKBgQC2VgJSKwCVYKKOah2Wtd73vhMnXlyEmJ7l\nYRat8ejvfjt+q5dIjz/ua3IXvj98zXfuac07IoIGDqf5FL6mcNf66zXWoX+nC2Et\nM6GLuVf4fQ0wG9Wpce5h20tWQQIm4VzZsL4NMAkfbk7F+josufjblfywD4RPnKoR\nP8thGyZ2BQKBgQDd9FHepus0iKnCy0oWj1yE5ReJyESDOJ5Qb89x0EFUHVs1qn9f\n0Xwe9idkqXclOTAC9ADkigVDMBnkqjgysyIBfWJMQXw1A2DPfsr9TaYBTLQeQkBd\n2PeVWh8V7pyosResyDxTzGKPfc8jSewBBKfwEZ5Ur76cSn5H5gVMtJdpeQKBgFJO\n4R1WbB0CUl8XZ+PwlYYgN2U+I2V3v8Kr4SEEYrI5uYGk09XdVG678fTl3KLp3Ymy\nGQLowOkbFJhL1QQtTFHoe5U3sfgmGufctr/sGtoBGULuTbT/ySxDYe69ycrjUJa0\nQhaR6IxXrxePrVMjYEM/oaGYX5HQlrTSp2xeZS5BAoGBAMRgQzaaNwZMoqzMdTXp\nBOI37j5S5t7Su0y0DWK2iHKFVqUTPRQn2wv7VmIIRXQXsBeHrc7YWe/ClmcyXpQQ\nfOZ8KMAnX6eGxmvnubyJlRUU61FDbV5HAnzPn9fXTi4PiUraE5cmRe1x6QB6ln/Z\n/Zx02B8imBE4Qlep3KfOomRh\n-----END PRIVATE KEY-----\n",
    "client_email" => "firebase-adminsdk-38n8d@codingmania-4fec0.iam.gserviceaccount.com",
    "client_id" => "113993425747712900806",
    "auth_uri" => "https://accounts.google.com/o/oauth2/auth",
    "token_uri" => "https://oauth2.googleapis.com/token",
    "auth_provider_x509_cert_url" => "https://www.googleapis.com/oauth2/v1/certs",
    "client_x509_cert_url" => "https://www.googleapis.com/robot/v1/metadata/x509/firebase-adminsdk-38n8d%40codingmania-4fec0.iam.gserviceaccount.com",
    "universe_domain" => "googleapis.com"
]);

// Create the Firebase factory
$firebase = (new Factory)
    ->withServiceAccount($serviceAccount)
    ->create();

$db = $firebase->getFirestore(); // Get Firestore instance

// Function to add a user
function addUser($email, $password) {
    global $db;
    try {
        $userRef = $db->collection('1/users')->add([
            'Email' => $email, // Field name for email
            'Password' => $password // Field name for password
        ]);
        return $userRef->id; // Return the ID of the added user
    } catch (\Exception $e) {
        echo 'Error adding user: ' . $e->getMessage(); // Display error if it occurs
        return null;
    }
}

// Function to get all users
function getAllUsers() {
    global $db;
    try {
        $users = $db->collection('1/users')->documents();
        foreach ($users as $user) {
            if ($user->exists()) {
                echo 'User ID: ' . $user->id() . ' - Email: ' . $user->get('Email') . '<br>'; // Display each user's ID and Email
            }
        }
    } catch (\Exception $e) {
        echo 'Error retrieving users: ' . $e->getMessage(); // Display error if it occurs
    }
}

// Example usage
$email = 'test@example.com'; // Change this to the email you want to add
$password = 'yourpassword'; // Change this to the password you want to set
$userId = addUser($email, $password);

if ($userId) {
    echo 'User added with ID: ' . $userId . '<br>';
}

getAllUsers(); // Retrieve and display all users

?>