<?php
session_start(); // Start session

/*// Get the JSON input
$input = json_decode(file_get_contents('php://input'), true);
$prompt = $input['prompt'] ?? '';

// Function to make a request to the OpenAI API
function getOpenAIResponse($prompt) {
    global $apiKey;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://api.openai.com/v1/chat/completions');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $apiKey,
        'Content-Type: application/json',
    ]);

    $data = json_encode([
        'model' => 'gpt-3.5-turbo',
        'messages' => [
            ['role' => 'user', 'content' => $prompt]
        ],
        'max_tokens' => 150,
    ]);

    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        return json_encode(['response' => 'Error: ' . curl_error($ch)]);
    }

    curl_close($ch);

    $responseData = json_decode($response, true);

    if (isset($responseData['error'])) {
        return json_encode(['response' => 'Error: ' . $responseData['error']['message']]);
    }

    return json_encode(['response' => $responseData['choices'][0]['message']['content'] ?? 'No response']);
}

// Output the API response
header('Content-Type: application/json');
echo getOpenAIResponse($prompt);*/



// Include API Key
$api = include 'api_keys.php';
$apiKey = $api['openai_api_key'];

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);
$userMessage = $input['userMessage'] ?? ''; // User's message from input field
$questionContext = $input['questionContext'] ?? ''; // The question, just as context

// Format the prompt to include context but emphasize the user input
$finalPrompt = "Context: The following is some information that might be useful but is not the main topic. Feel free to ignore it unless necessary.\n\n" .
               "Question Context: $questionContext\n\n" .
               "User Input: $userMessage\n\n" .
               "Please focus on the user's input, but if the context is relevant, use it to provide a better response.";

// Function to send request to OpenAI API
function getOpenAIResponse($prompt) {
    global $apiKey;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://api.openai.com/v1/chat/completions');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $apiKey,
        'Content-Type: application/json',
    ]);

    $data = json_encode([
        'model' => 'gpt-3.5-turbo',
        'messages' => [
            ['role' => 'system', 'content' => "You are a helpful AI. Consider the context if it is relevant, but focus on answering the user's input. Focus on question supplied to you,focus on Question Context "],
            ['role' => 'user', 'content' => $prompt] // Send formatted prompt
        ],
        'max_tokens' => 150,
    ]);

    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        return json_encode(['response' => 'Error: ' . curl_error($ch)]);
    }

    curl_close($ch);

    $responseData = json_decode($response, true);

    if (isset($responseData['error'])) {
        return json_encode(['response' => 'Error: ' . $responseData['error']['message']]);
    }

    return json_encode(['response' => $responseData['choices'][0]['message']['content'] ?? 'No response']);
}

// Return API response
header('Content-Type: application/json');

if (!empty($finalPrompt)) {
    echo getOpenAIResponse($finalPrompt);
} else {
    echo json_encode(['response' => 'No question available']);
}
?>