<?php include("includes/init.php");

include("includes/head.php");?>

<body>


  <?php include("includes/header.php");?>

    <h1>BOROUGHS</h1>
    <div class = 'tagresults'>
    <ul>
      <?php
      $records = exec_sql_query($db, "SELECT * FROM location_tags")->fetchAll(PDO::FETCH_ASSOC);
      foreach ($records as $record) {
        echo "<li><a href = 'locationresults.php?link=".$record['id']."'>#". htmlspecialchars($record["location"]). "</a></li>";
      }
      ?>
    </ul>
    </div>

</body>
</html>
