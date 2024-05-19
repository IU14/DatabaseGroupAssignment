<?php
require 'vendor/autoload.php'; // include Composer's autoloader

use MongoDB\Client;

try {
    // Create a new client and connect to the server
    $client = new Client("mongodb://localhost:27017");

    // Select the database and collection
    $database = $client->Assignment2;
    $collection = $database->movies;

    // Define the first query
    $filter = ['artists.name' => 'Harrison'];
    $options = ['projection' => ['title' => 1]];

    // Run the first query
    $cursor = $collection->find($filter, $options);
    // Display the introductory line
    echo "<p>Harrison Ford's films are:</p>\n";
    // Iterate through the results and display them
    foreach ($cursor as $document) {
        echo "<p>" . $document['title'] . "</p>\n";
    }

    // Define the aggregation pipeline for the 2nd query
    $pipeline = [
        [
            '$match' => [
                'title' => 'Star Wars'
            ]
        ],
        [
            '$unwind' => '$User Scores'
        ],
        [
            '$group' => [
                '_id' => '$title',
                'averageScore' => ['$avg' => '$User Scores.score']
            ]
        ]
    ];

    // Execute the aggregation pipeline
    $result = $collection->aggregate($pipeline)->toArray();

    // Display the average score for Star Wars
    if (!empty($result)) {
        echo "<p>Average user score for Star Wars: " . $result[0]['averageScore'] . "</p>\n";
    } else {
        echo "<p>No data found for Star Wars.</p>\n";
    }

    // Define the final query using N aggregation pipeline
    $pipeline = [
        [
            '$match' => [
                "artists.role" => "Producer"
            ]
        ],
        [
            '$unwind' => '$artists'
        ],
        [
            '$match' => [
                "artists.role" => "Producer"
            ]
        ],
        [
            '$group' => [
                '_id' => '$artists.surname',
                'count' => ['$sum' => 1]
            ]
        ],
        [
            '$group' => [
                '_id' => null,
                'distinctProducers' => ['$sum' => 1],
                'producers' => ['$push' => ['surname' => '$_id', 'count' => '$count']]
            ]
        ]
    ];

    // Run the 3rd query
    $result = $collection->aggregate($pipeline)->toArray();

    // Output the result
    if (!empty($result)) {
        echo "<p>Number of distinct producers: " . $result[0]['distinctProducers'] . "</p>\n";
        echo "<p>Producers:</p>\n";
        foreach ($result[0]['producers'] as $producer) {
            echo "<p>- Surname: " . $producer['surname'] . ", Count: " . $producer['count'] . "</p>\n";
        }
    } else {
        echo "<p>No data found.</p>\n";
    }

} catch (Exception $e) {
    echo '<p>Caught exception: ' . $e->getMessage() . "</p>\n";
}
?>

