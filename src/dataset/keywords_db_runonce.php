<?php

require "../functions/dbconn.php";

$endpoint = "TheMovieDB/keywords.csv";
$contents = fopen($endpoint, "r");

$skipline = 0;

$dataset = array();


while(($row = fgetcsv($contents, 0, ",")) !== false ) {
    // skip the first row
    $skipline++;
    if ($skipline == 1) {
        continue;
    }

    $id = $row[0];
    $keywords = $row[1];
    //echo "<p>Keywords: {$keywords}</p>";

    // fix the bad json - replace single quotes to double quotes
    $keywords = str_replace("'", '"', $keywords);

    // break Keywords Json file type out
    $movieKeywords = json_decode($keywords, true);
    
    //Added each Associative element to the array
     
    foreach($movieKeywords as $key => $val){
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

    $stmt = $conn->prepare("INSERT INTO `keywords` (`id`, `name`) VALUES (?, ?);");
    $stmt->bind_param("is", $id, $name);
    
    $id = $val["id"];
    $name = $val["name"];
    
    $stmt->execute();
}

echo "Complete";
$stmt->close();
$conn->close();
