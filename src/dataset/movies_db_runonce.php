<?php

require "../functions/dbconn.php";

$endpoint = "TheMovieDB/movies_metadata.csv";
$contents = fopen($endpoint, "r");

// API Key for themoviedb.org - to pull image_paths
$key = "123456";

$skipline = 0;
$count = 0;

while(($row = fgetcsv($contents, 0, ",")) !== false ) {
    // skip the first row
    $skipline++;
    if ($skipline == 1) {
        continue;
    }

    //print_r($row);

    //$adult = $row[0];
    //$belongs_to_collection = $row[1];
    $budget = intval($row[2]);
    //$genres = $row[3];
    //$homepage = $row[4];
    $id = $row[5]; // This ID seems to match TMDB's API data
    //$imdb_id = $row[6];
    //$original_language = $row[7];
    $original_title = mysqli_real_escape_string($conn, $row[8]);
    $overview = mysqli_real_escape_string($conn, $row[9]);
    $popularity = doubleval($row[10]);
    //$poster_path = $row[11];
    //$production_companies = $row[12];
    //$production_countries = $row[13];
    $release_date = $row[14];
    $revenue = intval($row[15]);
    $runtime = doubleval($row[16]);
    //$spoken_languages = $row[17];
    //$status = $row[18];
    $tagline = mysqli_real_escape_string($conn, $row[19]);
    $title = mysqli_real_escape_string($conn, $row[20]);
    //$video = $row[21];
    //$vote_average = $row[22];
    //$vote_count = $row[23];

    /** ------- NOTE ----------
     * I've left this in to show my orginal plan to fix bad string types, but then found php functions to do it better!
    // fix - replace , ' ! in strings.
    $stringChar = array(",", "'", "!", ":", ";");
    $stringAscii = array("&#44;", "&#39;", "&#33;", "&#58;", "&#59;");
    $overview = str_replace($stringChar, $stringAscii, $overview);
    $tagline = str_replace($stringChar, $stringAscii, $tagline);
    $original_title = str_replace($stringChar, $stringAscii, $original_title);
    $title = str_replace($stringChar, $stringAscii, $title);
    */
    /** ------ NOTE ------
     * Moved into update table as I was running into Request Limits, so updated my database with no images first.
    // pulling the movie artwork from API json file
    $json = file_get_contents("https://api.themoviedb.org/3/movie/{$id}?api_key=$key");
    $result = json_decode($json, true);

    $api_poster_path = $result["poster_path"];
    $api_backdrop_path = $result["backdrop_path"];

    //echo "<p>ID: {$id}  -  IMDB ID: {$imdb_id} - Title: {$title} - </p>";

    //echo "<p>Poster: {$api_poster_path} - Backdrop: {$api_backdrop_path}</p>";

    $api_backdrop_path = "https://image.tmdb.org/t/p/original{$api_backdrop_path}";

    // create placeholders if images are empty
    if(!empty($api_poster_path)){
        $api_thumbnail_path = "https://image.tmdb.org/t/p/w185{$api_poster_path}";
        $api_poster_path = "https://image.tmdb.org/t/p/w500{$api_poster_path}";
    } else {
        $api_poster_path = "https://via.placeholder.com/500x750?text=Visit+placeholder.com+Now";
        $api_thumbnail_path = "https://via.placeholder.com/185x278?text=Visit+placeholder.com+Now";
    }
    */
    /**
    $api_poster_path = " ";
    $api_backdrop_path = " ";
    $api_backdrop_path = " ";
    */
    if(empty($tagline)){
        $tagline = " ";
    }
    if(empty($release_date)){
        $release_date = "2021-04-29";
    }
    if(empty($runtime)){
        $runtime = "00.0";
    }
    if(empty($revenue)){
        $revenue = "0";
    }
    if(empty($budget)){
        $budget = "0";
    }
    if(empty($popularity)){
        $popularity = "0";
    }

    $count++;
    //echo "INSERT INTO movies ('id', 'title', 'tagline', 'overview', 'poster_path', 'backdrop_path', 'thumbnail', 'budget', 'popularity', 'release_date', 'revenue', 'runtime') VALUES ($id, $original_title, $tagline, $overview, $api_poster_path, $api_backdrop_path, $api_thumbnail_path, $budget, $popularity, $release_date, $revenue, $runtime)";

    $checkInsert = "SELECT * FROM `movies` WHERE id = {$id};";
    $checkResult = $conn->query($checkInsert);

    $row = mysqli_fetch_assoc($checkResult);

    if (mysqli_num_rows($checkResult) > 0 && $row['poster_path'] == " ") {
        // pulling the movie artwork from API json file
        $json = file_get_contents("https://api.themoviedb.org/3/movie/{$id}?api_key=$key");
        $result = json_decode($json, true);

        $api_poster_path = $result["poster_path"];
        $api_backdrop_path = $result["backdrop_path"];

        // create placeholders if images are empty
        if(!empty($api_poster_path)){
            $api_thumbnail_path = "https://image.tmdb.org/t/p/w185{$api_poster_path}";
            $api_backdrop_path = "https://image.tmdb.org/t/p/original{$api_backdrop_path}";
            $api_poster_path = "https://image.tmdb.org/t/p/w500{$api_poster_path}";
        } else {
            $api_poster_path = "https://via.placeholder.com/500x750?text=Visit+placeholder.com+Now";
            $api_thumbnail_path = "https://via.placeholder.com/185x278?text=Visit+placeholder.com+Now";
            $api_backdrop_path = "";
        }
        $update = "UPDATE `movies` SET title='{$title}', tagline='{$tagline}', overview='{$overview}', poster_path='{$api_poster_path}', backdrop_path='{$api_backdrop_path}', thumbnail='{$api_thumbnail_path}', budget='{$budget}', popularity='{$popularity}', release_date='{$release_date}', revenue='{$revenue}', runtime='{$runtime}' WHERE id='{$id}';";
        $result = $conn->query($update);
        if(!$result){
            echo $conn->error;
            die();
        }
        echo "<p>$count - $id - Update Image Path</p>";

    } elseif (mysqli_num_rows($checkResult) > 0 && $row['poster_path'] != " ") {
        echo "<p>$count - $id - Updated</p>";
    } else {
        $insert = "INSERT INTO `movies` (`id`, `title`, `tagline`, `overview`, `poster_path`, `backdrop_path`, `thumbnail`, `budget`, `popularity`, `release_date`, `revenue`, `runtime`) VALUES ('{$id}', '{$title}', '{$tagline}', '{$overview}', '{$api_poster_path}', '{$api_backdrop_path}', '{$api_thumbnail_path}', '{$budget}', '{$popularity}', '{$release_date}', '{$revenue}', '{$runtime}')";
        $result = $conn->query($insert);
        if(!$result){
            echo $conn->error;
            die();
        }
        echo "<p>$count - $id - added</p>";
    }


    /**
    $insert = "INSERT INTO `movies` (`id`, `title`, `tagline`, `overview`, `poster_path`, `backdrop_path`, `thumbnail`, `budget`, `popularity`, `release_date`, `revenue`, `runtime`) VALUES ('{$id}', '{$title}', '{$tagline}', '{$overview}', '{$api_poster_path}', '{$api_backdrop_path}', '{$api_thumbnail_path}', '{$budget}', '{$popularity}', '{$release_date}', '{$revenue}', '{$runtime}')";
    $result = $conn->query($insert);
    */

    // ------ NOTE -------
    // I tried using prepared statments for this but I couldn't get it to work correctly - so used the
    // methood we were taught and feel as this is meant for a one time use for me it was fine.

    //$stmt = $conn->prepare("INSERT INTO movies (id, title, tagline, overview, poster_path, backdrop_path, thumbnail, budget, popularity, release_date, revenue, runtime) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);");
    //$stmt->bind_param("issssssidiid", $id, $original_title, $tagline, $overview, $api_poster_path, $api_backdrop_path, $api_thumbnail_path, $budget, $popularity, $release_date, $revenue, $runtime);
    //$stmt->execute();

}

echo "<p>Complete</p>";


//$stmt->close();
$conn->close();

?>
