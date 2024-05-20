<?php
try {
    // Create connection using PDO
    $connection = new PDO("mysql:host=localhost;dbname=movie", 'root', '');
    // Set the PDO error mode to exception
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // First SQL query
    $sql1 = "SELECT m.movie_id, m.title, a.name AS actor_name
             FROM movie.movie m
             JOIN movie.role r ON m.movie_id = r.movie_id
             JOIN movie.artist a ON r.actor_id = a.artist_id
             WHERE a.surname = 'Ford' AND a.name = 'Harrison'";

    $result1 = $connection->query($sql1);

    // Check if any results were returned for the first query
    if ($result1->rowCount() > 0) {
        // Output data in an HTML table for the first query
        echo "<h2>Movies with Harrison Ford</h2>";
        echo "<table border='1'>
                <tr>
                    <th>Movie ID</th>
                    <th>Title</th>
                    <th>Actor Name</th>
                </tr>";

        while ($row = $result1->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>
                    <td>" . htmlspecialchars($row["movie_id"]) . "</td>
                    <td>" . htmlspecialchars($row["title"]) . "</td>
                    <td>" . htmlspecialchars($row["actor_name"]) . "</td>
                  </tr>";
        }

        echo "</table>";
    } else {
        echo "<p>No results found for Harrison Ford movies.</p>";
    }

    // Second SQL query
    $sql2 = "SELECT m.title, AVG(s.score) AS average_score
             FROM movie.movie m
             JOIN movie.score s ON m.movie_id = s.movie_id
             WHERE m.title = 'Star Wars'
             GROUP BY m.title";

    $result2 = $connection->query($sql2);

    // Check if any results were returned for the second query
    if ($result2->rowCount() > 0) {
        // Output data in an HTML table for the second query
        echo "<h2>Average Score for Star Wars</h2>";
        echo "<table border='1'>
                <tr>
                    <th>Title</th>
                    <th>Average Score</th>
                </tr>";

        while ($row = $result2->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>
                    <td>" . htmlspecialchars($row["title"]) . "</td>
                    <td>" . htmlspecialchars(number_format($row["average_score"], 2)) . "</td>
                  </tr>";
        }

        echo "</table>";
    } else {
        echo "<p>No results found for Star Wars average score.</p>";
    }

    // Third SQL query
    $sql3 = "SELECT a.artist_id, a.surname, a.name, COUNT(m.movie_id) AS films_produced
             FROM movie.artist a
             JOIN movie.movie m ON a.artist_id = m.producer_id
             GROUP BY a.artist_id, a.surname, a.name
             ORDER BY films_produced DESC";

    $result3 = $connection->query($sql3);

    // Check if any results were returned for the third query
    if ($result3->rowCount() > 0) {
        // Output data in an HTML table for the third query
        echo "<h2>Films Produced by Artists</h2>";
        echo "<table border='1'>
                <tr>
                    <th>Artist ID</th>
                    <th>Surname</th>
                    <th>Name</th>
                    <th>Films Produced</th>
                </tr>";

        while ($row = $result3->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>
                    <td>" . htmlspecialchars($row["artist_id"]) . "</td>
                    <td>" . htmlspecialchars($row["surname"]) . "</td>
                    <td>" . htmlspecialchars($row["name"]) . "</td>
                    <td>" . htmlspecialchars($row["films_produced"]) . "</td>
                  </tr>";
        }

        echo "</table>";
    } else {
        echo "<p>No results found for films produced by artists.</p>";
    }

} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

// Close the connection
$connection = null;
?>
