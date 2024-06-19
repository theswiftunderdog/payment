<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = $_POST['amount'];
    $card_number = $_POST['card_number'];
    $exp_month = $_POST['exp_month'];
    $exp_year = $_POST['exp_year'];
    $cvv = $_POST['cvv'];

    // Your Xendit API Key
    $apiKey = 'xnd_public_development_rDAejaYzesFztStDED2eWxQMpBRmXW4WdKZ22HaihOStuib6ZDc77BHocd4CJi';

    // Generate a unique external_id for the transaction
    $external_id = 'order-' . time();

    try {
        // Get the card token
        $token_id = getCardToken($card_number, $exp_month, $exp_year, $cvv, $apiKey);

        if (!$token_id) {
            throw new Exception('Failed to get card token.');
        }

        // Prepare the data for the payment request
        $data = [
            'external_id' => $external_id,
            'amount' => $amount,
            'payment_method' => 'CREDIT_CARD',
            'credit_card' => [
                'token_id' => $token_id
            ]
        ];

        // Make the request to create an invoice
        $response = makeRequest('https://api.xendit.co/v2/invoices', $data, $apiKey);

        // Display the response
        echo '<pre>';
        print_r($response);
        echo '</pre>';

    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage();
    }
}

/**
 * Get the card token from Xendit
 *
 * @param string $card_number The card number
 * @param int $exp_month The card expiry month
 * @param int $exp_year The card expiry year
 * @param int $cvv The card CVV
 * @param string $apiKey The Xendit API key
 * @return string The token ID
 */
function getCardToken($card_number, $exp_month, $exp_year, $cvv, $apiKey) {
    // Prepare the data for the token request
    $data = [
        'card_number' => $card_number,
        'exp_month' => $exp_month,
        'exp_year' => $exp_year,
        'cvv' => $cvv
    ];

    // Make the request to get the card token
    $response = makeRequest('https://api.xendit.co/credit_card_tokens', $data, $apiKey);

    // Debugging: Print the response to understand its structure
    echo '<pre>Response from getCardToken:</pre>';
    echo '<pre>';
    print_r($response);
    echo '</pre>';

    // Check if the response is valid and contains the id
    if (isset($response['id'])) {
        return $response['id'];
    } else {
        throw new Exception('Token ID not found in the response.');
    }
}

/**
 * Make a request to the given URL with the given data
 *
 * @param string $url The URL to make the request to
 * @param array $data The data to send in the request
 * @param string $apiKey The API key to authenticate the request
 * @return array The response data
 */
function makeRequest($url, $data, $apiKey) {
    // Initialize cURL session
    $ch = curl_init($url);

    // Set cURL options
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Basic ' . base64_encode($apiKey . ':')
    ]);

    // Execute the cURL request
    $response = curl_exec($ch);

    // Check for errors
    if (curl_errno($ch)) {
        throw new Exception(curl_error($ch));
    }

    // Close the cURL session
    curl_close($ch);

    // Decode and return the response
    return json_decode($response, true);
}
?>
