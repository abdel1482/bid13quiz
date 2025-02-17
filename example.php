<?php
function isValidPhoneNumber($phone_number, $customer_id, $api_key) {
    $api_url = "https://rest-ww.telesign.com/v1/phoneid/$phone_number";

    var_dump($api_url);

    $headers = [
        "Authorization: Basic " . base64_encode("$customer_id:$api_key"),
        "Content-Type: application/x-www-form-urlencoded"
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST , "POST");

    $response = curl_exec($ch);
    var_dump($response);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code !== 200) {
        var_dump("this is false here");
        return false; // API request failed
    }

    $data = json_decode($response, true);

    if (!isset($data['numbering']['phone_type'])) {
        return false; // Unexpected API response
    }

    $valid_types = ["FIXED_LINE", "MOBILE", "VALID"];
    return in_array(strtoupper($data['numbering']['phone_type']), $valid_types);
}

function isItValidPhoneNumber($phone_number, $customer_id, $api_key) {
    $api_url = "https://rest-ww.telesign.com/v1/phoneid/$phone_number";

    $headers = [
        "Authorization: Basic " . base64_encode("$customer_id:$api_key"),
        "Content-Type: application/x-www-form-urlencoded"
    ];

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $api_url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode([
            'consent' => [
                'method' => 1
            ]
        ]),
        CURLOPT_HTTPHEADER => [
            "Authorization: Basic " . base64_encode("$customer_id:$api_key"),
            "accept: application/json",
            "content-type: application/json"
        ],
    ]);

    $response = curl_exec($ch);
    var_dump($response);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code !== 200) {
        var_dump("this is false");
        return false; // API request failed
    }

    $data = json_decode($response, true);
    if (!isset($data['numbering']['phone_type'])) {
        return false; // Unexpected API response
    }

    $valid_types = ["FIXED_LINE", "MOBILE", "VALID"];
    return in_array(strtoupper($data['numbering']['phone_type']), $valid_types);
}

// Usage example
$phone_number = "5147810466"; // Replace with actual phone number
$customer_id = "8828A8F3-22FF-41A4-958F-FE2684EACCF9";
$api_key = "LdbvlN1hIcwMfcHF+uH3Ic064nOfoBW9GTjuvxp3gLruyUIDrTGN1ogOrhdBRedDr1JH/EvaRLXPgmnUpqKY7A==";
echo $phone_number;
$result = isValidPhoneNumber($phone_number, $customer_id, $api_key);
$result2 = isItValidPhoneNumber($phone_number, $customer_id, $api_key);
//var_dump($result);
var_dump($result2);