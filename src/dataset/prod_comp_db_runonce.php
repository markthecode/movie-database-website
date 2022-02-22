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
    $production_companies = $row[12];

    // fix the bad json - replace single quotes to double quotes
    $production_companies = str_replace("'", '"', $production_companies);

    // break Genres Json file type out
    $movieProductionCompanies = json_decode($production_companies, true);
    
    //Added each Associative element to the array
    foreach($movieProductionCompanies as $key => $val){
        $line = array('name'=>$val["name"], 'id'=>$val["id"]);
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

    $stmt = $conn->prepare("INSERT INTO `production_companies` (`id`, `name`) VALUES (?, ?);");
    $stmt->bind_param("is", $id, $name);

    $id = $val["id"];
    $name = $val["name"];

    $stmt->execute();
    
}

echo "Complete";
$stmt->close();
$conn->close();
 
?>