<?php include("includes/init.php");

include("includes/head.php");

?>
<body>

    <?php include("includes/header.php");

    $currid= $_GET['link'];

    $params= array(
        ":currid" => $currid);

    $sql = "SELECT * FROM location_tags WHERE location_tags.id = :currid";
    $name = exec_sql_query($db, $sql, $params)->fetchAll();

    foreach($name as $title){
       echo "<h2>#".$title["location"]."</h2>";
}?>




<ul><?php
    $sql = "SELECT * FROM images INNER JOIN all_tags ON images.id = all_tags.image_id WHERE all_tags.location_id = :currid";
    $records = exec_sql_query($db, $sql, $params)->fetchAll(PDO::FETCH_ASSOC);?>

<?php
        foreach($records as $record){?>
            <?php echo "<a href = 'img.php?link=".$record['image_id']."'><img src = 'uploads/".$record['image_id'].".".$record['file_ext']."'></a>";
            ?>
           <?php
    }

?>


</ul>




</body>
</html>
