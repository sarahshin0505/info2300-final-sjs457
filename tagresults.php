<?php include("includes/init.php");

include("includes/head.php");

?>
<body>

    <?php include("includes/header.php");

    $currid= $_GET['link']; //id of the tag we have

    $params= array(
        ":currid" => $currid);

    $sql = "SELECT * FROM tags WHERE tags.id = :currid";
    $name = exec_sql_query($db, $sql, $params)->fetchAll();


    foreach($name as $title){
       echo "<h2>#".$title["tag"]."</h2>";}


        $imgsql = //"SELECT * FROM images INNER JOIN tags ON tags.image_id = images.id INNER JOIN all_tags ON tags.image_id = all_tags.image_id WHERE all_tags.tag_id = :currid";
        "SELECT * FROM images INNER JOIN all_tags ON images.id = all_tags.image_id WHERE all_tags.tag_id = :currid";
        $records = exec_sql_query($db, $imgsql, $params)->fetchAll(PDO::FETCH_ASSOC);
        foreach($records as $record){
            echo "<a href = 'img.php?link=".$record['image_id']."'><img src = 'uploads/".$record['image_id'].".".$record['file_ext']."'></a>";

        }
    ?>

</body>
</html>
