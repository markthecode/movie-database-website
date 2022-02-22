<?php

require "../functions/dbconn.php";


$sqlread = "SELECT * FROM `movies` WHERE 1;";

$result = $conn->query($sqlread);

if(!$result){
    echo $conn->error;
    die();
}

$count = 0;
$dataset = array();

while($row = $result->fetch_assoc()) {

    $thumbnail_path = $row['thumbnail'];
    $id = $row['id'];
        
    $search = 'https://image.tmdb.org/t/p/w500';
    $text = 'https://image.tmdb.org/t/p/w185https://image.tmdb.org/t/p/w500';

       

    if (strpos($thumbnail_path, $search) !== false) {
        $count++;  
        
        //echo "<p>{$count} - {$id} Image = {$thumbnail_path}</p>";

        // Repeated the image path into my database so needed to remove the repeated string.
        $new_thumbnail_path = str_replace($search, '', $thumbnail_path);

        // tried to run the UPDATE query here but would only update the first query ... 
        // so wrote my updated image path to an array
        $line = array('id'=>$id, 'thumbnail'=>$new_thumbnail_path);
        array_push($dataset, $line);
        
    }   

}

// looped through the array to update my database with new image path string
foreach($dataset as $key => $val ){

    $id = $val['id'];
    $new_thumbnail_path = $val['thumbnail'];
    
    //echo "<p>UPDATE `movies` SET thumbnail='{$new_thumbnail_path}' WHERE id='{$id}';</p>";

    $update = "UPDATE `movies` SET thumbnail='{$new_thumbnail_path}' WHERE id='{$id}';";
    $result = $conn->query($update);

}

echo "<p>Complete</p>";

$conn->close();

?>