<?php include("includes/init.php");

include("includes/head.php");

if (isset($_GET['search'])){
  $do_search = TRUE;
  $search = filter_input(INPUT_GET, 'search', FILTER_SANITIZE_STRING);
  $search = trim($search);
}else{
  $do_search = FALSE;
  $search = NULL;
}

?>

<body>


  <?php include("includes/header.php");?>

<h1>TAGS</h1>
<div class = 'tagresults'>
    <ul>
      <?php
      $tags = exec_sql_query($db, "SELECT * FROM tags")->fetchAll(PDO::FETCH_ASSOC);
      foreach ($tags as $tag) {
        echo "<li><a href = 'tagresults.php?link=".$tag['id']."'>#". htmlspecialchars($tag["tag"]). "</a></li>";
      }

      ?>
    </ul>
    </div>

</body>
</html>
