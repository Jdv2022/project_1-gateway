<?php

// Include the autoload file
require 'vendor/autoload.php';

// Use the correct namespace
use Users\UserServiceClient;
use Users\RegisterUserDetailsRequest;  

// Create the client
$client = new UserServiceClient(
    '127.0.0.1:9001',  // gRPC server address
    [
        'credentials' => \Grpc\ChannelCredentials::createInsecure(),
    ]
);

// Create a request (replace with actual request parameters)
$request = new RegisterUserDetailsRequest();
$request->setFirstName('John');
$request->setMiddleName('Doe');
$request->setLastName('Smith');
$request->setEmail('johndoe@example.com');
$request->setPhone('123-456-7890');
$request->setAddress('1234 Main St, Hometown, USA');
$request->setCountry('USA');
$request->setDateOfBirth('1990-01-01');
$request->setAge('35');
$request->setGender('Male');
$request->setProfileImage('profile_image_url');
$request->setActionByUserId('user_id_123');

// Make the gRPC call
list($response, $status) = $client->RegisterUserDetails($request)->wait();

// Check if the response was successful
if ($status->code === \Grpc\STATUS_OK) {
    echo "Response: " . $response->getSaved() . PHP_EOL;
} else {
    echo "gRPC call failed with status: " . $status->details . PHP_EOL;
}
