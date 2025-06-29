<?php

function callAPI($method, $url, $data = false, $headers = []) {
    $curl = curl_init();
    switch (strtoupper($method)) {
        case "POST":
            curl_setopt($curl, CURLOPT_POST, true);
            if ($data) {
                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
            }
            break;

        case "PUT":
        case "PATCH":
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, strtoupper($method));
            if ($data) {
                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
            }
            break;

        case "DELETE":
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
            break;

        default: // GET
            if ($data) {
                $url .= '?' . http_build_query($data);
            }
            break;
    }

    $defaultHeaders = [
        'Content-Type: application/json',
        'Accept: application/json'
    ];
    curl_setopt($curl, CURLOPT_HTTPHEADER, array_merge($defaultHeaders, $headers));

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($curl);

    if (curl_errno($curl)) {
        return [
            'status' => 500,
            'error' => curl_error($curl)
        ];
    }

    $httpStatus = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    return [
        'status' => $httpStatus,
        'response' => json_decode($response, true)
    ];
}