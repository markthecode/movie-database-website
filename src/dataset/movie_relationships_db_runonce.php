<?php

require "../functions/dbconn.php";

$endpoint = "TheMovieDB/movies_metadata.csv";
$contents = fopen($endpoint, "r");

$skipline = 0;
$count = 0;
$genres_dataset = array();
$pc_dataset = array();


while(($row = fgetcsv($contents, 0, ",")) !== false ) {
    // skip the first row
    $skipline++;
    if ($skipline == 1) {
        continue;
    }

    $production_companies = $row[12];
    $genres = $row[3];
    $movie_id = $row[5];

    // fix the bad json - replace single quotes to double quotes
    $genres = str_replace("'", '"', $genres);
    $production_companies = str_replace("'", '"', $production_companies);

    // break Genres Json file type out
    $movieGenres = json_decode($genres, true);
    $movieProductionCompanies = json_decode($production_companies, true);
    
    $count++;

    $checkInsert = "SELECT * FROM `movies` WHERE id = {$movie_id};";
    $checkResult = $conn->query($checkInsert);

    $row = mysqli_fetch_assoc($checkResult);
    
    if (mysqli_num_rows($checkResult) > 0 && $row['id'] == $movie_id) {
        //echo "<p>$count - $movie_id - {$row['id']}</p>";

        foreach($movieGenres as $key => $val){

            $line = array('movieId'=>$movie_id, 'genreId'=>$val["id"]);
            array_push($genres_dataset, $line);
        }

        foreach($movieProductionCompanies as $key => $val){

            $line = array('movieId'=>$movie_id, 'proCompId'=>$val["id"]);
            array_push($pc_dataset, $line);

        }
    } else {
        //echo "<p>$count - Not on db</p>";
    }
}
 
foreach($genres_dataset as $key => $val){

    $stmt = $conn->prepare("INSERT INTO `movie_genres` (`movieId`, `genreId`) VALUES (?, ?);");
    $stmt->bind_param("ii", $movieId, $genreId);

    $movieId = $val["movieId"];
    $genreId = $val["genreId"];
         
    $stmt->execute();

    //echo "<p>INSERT INTO `movie_genres` (`movieId`, `genreId`) VALUES ($movieId, $genreId);</p>";
}

foreach($pc_dataset as $key => $val){

    $stmt = $conn->prepare("INSERT INTO `movie_pro_comp` (`movieId`, `proCompId`) VALUES (?, ?);");
    $stmt->bind_param("ii", $movieId, $proCompId);

    $movieId = $val["movieId"];
    $proCompId = $val["proCompId"];

    $stmt->execute();

    //echo "<p>INSERT INTO `movie_pro_comp` (`movieId`, `proCompId`) VALUES ($movieId, $proCompId);</p>";
}

echo "Complete";
$stmt->close();
$conn->close();

?>