<?php

require "../functions/dbconn.php";

$endpoint = "TheMovieDB/keywords.csv";
$contents = fopen($endpoint, "r");

$skipline = 0;
$count = 0;

$dataset = array();


while(($row = fgetcsv($contents, 0, ",")) !== false ) {
    // skip the first row
    $skipline++;
    if ($skipline == 1) {
        continue;
    }

    $movie_id = $row[0];
    $keywords = $row[1];
    //echo "<p>Keywords: {$keywords}</p>";

    // fix the bad json - replace single quotes to double quotes
    $keywords = str_replace("'", '"', $keywords);

    // break Keywords Json file type out
    $movieKeywords = json_decode($keywords, true);
    
    //Added each Associative element to the array
     

    $count++;

    $checkInsert = "SELECT * FROM `movies` WHERE id = {$movie_id};";
    $checkResult = $conn->query($checkInsert);

    $row = mysqli_fetch_assoc($checkResult);
    
    if (mysqli_num_rows($checkResult) > 0 && $row['id'] == $movie_id) {
        //echo "<p>$count - $movie_id - {$row['id']}</p>";

        foreach($movieKeywords as $key => $val){
            $line = array('movieId'=>$movie_id, 'keywordId'=>$val["id"]);
            array_push($dataset, $line);
        }

    } else {
        //echo "<p>$count - Not on db</p>";
    }
}
 
foreach($dataset as $key => $val){

    $stmt = $conn->prepare("INSERT INTO `movie_keywords` (`movieId`, `keywordId`) VALUES (?, ?);");
    $stmt->bind_param("ii", $movieId, $keywordId);

    $movieId = $val["movieId"];
    $keywordId = $val["keywordId"];

    $stmt->execute();

    //echo "<p>INSERT INTO `movie_keywords` (`movieId`, `keywordId`) VALUES ($movieId, $keywordId);</p>";
}

echo "Complete";
$stmt->close();
$conn->close();

?>