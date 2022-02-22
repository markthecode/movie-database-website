<?php

require "../functions/dbconn.php";

$endpoint = "TheMovieDB/movies_metadata.csv";
$contents = fopen($endpoint, "r");

$skipline = 0;
$dataset = array();


while(($row = fgetcsv($contents, 0, ",")) !== false ) {
    // skip the first row
    $skipline++;
    if ($skipline == 1) {
        continue;
    }

    $genres = $row[3];
    // echo "<p>Genre: {$genres}</p>";

    // fix the bad json - replace single quotes to double quotes
    $genres = str_replace("'", '"', $genres);

    // break Genres Json file type out
    $movieGenres = json_decode($genres, true);
    
    //var_dump($movieGenres);
    //print_r($movieGenres);
    //Added each Associative element to the array
    foreach($movieGenres as $key => $val){
        $line = array('id'=>$val["id"], 'name'=>$val["name"]);
        array_push($dataset, $line);
    }
}

// removed duplicates
$dataset = array_values(array_unique($dataset, SORT_REGULAR));
// sorted by ID
//asort($dataset);

array_multisort(array_column($dataset, 'id'), SORT_ASC, $dataset);

//echo json_encode($dataset);

 
foreach($dataset as $key => $val) {

    $stmt = $conn->prepare("INSERT INTO `genres` (`id`, `name`) VALUES (?, ?);");
    $stmt->bind_param("is", $id, $name);

    $id = $val["id"];
    $name = $val["name"];

    $stmt->execute();
    
}

echo "Complete";
$stmt->close();
$conn->close();

?>