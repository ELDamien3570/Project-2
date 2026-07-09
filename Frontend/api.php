<?php
    define('API_BASE', 'http://localhost:50/companies');

    function getAllCompanies() {

        $response = file_get_contents(API_BASE);


        return json_decode($response, true);
    }

    function getCompanyByName($name) {

        $response = @file_get_contents(API_BASE . '/' . rawurlencode($name));

        if ($response === false) {
            return null;
        }

        return json_decode($response, true);
    }

    function createCompany($name, $location) {
        return sendJsonRequest('POST', API_BASE, ['name' => $name, 'location' => $location]);
    }

    function updateCompany($name, $location) {
        return sendJsonRequest('PUT', API_BASE . '/' . rawurlencode($name), ['name' => $name, 'location' => $location]);
    }

    function deleteCompany($name) {
        return sendJsonRequest('DELETE', API_BASE . '/' . rawurlencode($name));
    }

    function sendJsonRequest($method, $url, $data = null) {

        $options = [
            'http' => [
                'method' => $method,
                'ignore_errors' => true,
            ],
        ];

        if ($data !== null) {
            $options['http']['header'] = "Content-Type: application/json\r\n";
            $options['http']['content'] = json_encode($data);
        }

        $context = stream_context_create($options);
        $body = file_get_contents($url, false, $context);

        return json_decode($body, true);
    }