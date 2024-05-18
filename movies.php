<?php
require 'vendor/autoload.php'; // include Composer's autoloader

use MongoDB\Client;

try {
    // Create a new client and connect to the server
    $client = new Client("mongodb://localhost:27017");

    // Select the database and collection
    $database = $client->Assignment2;
    $collection = $database->movies;

    // Define the query
    $filter = ['artists.name' => 'Harrison'];
    $options = ['projection' => ['title' => 1]];

    // Run the query
    $cursor = $collection->find($filter, $options);
    // Display the introductory line
    echo "Harrison Ford's films are:\n";
    // Iterate through the results and display them
    foreach ($cursor as $document) {
        echo $document['title'], "\n";
    }
} catch (Exception $e) {
    echo 'Caught exception: ', $e->getMessage(), "\n";
}
?>
