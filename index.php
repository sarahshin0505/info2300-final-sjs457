<?php include("includes/init.php");

include("includes/head.php");
const MAX_FILE_SIZE = 5000000;

//delete image
if (isset($_REQUEST['delete'])){
  $imgid = $_REQUEST['imageid'];
  $imgext = $_REQUEST['imageext'];
  $path = "uploads/".$imgid.".".$imgext;
  $delete_params = array(
    ':imgid' => $imgid,
  );
  $sql = "DELETE FROM images WHERE images.id = :imgid";
  exec_sql_query($db, $sql, $delete_params)->fetchAll();
  exec_sql_query($db, "DELETE FROM tags WHERE tags.image_id = :imgid", $delete_params)->fetchAll();
  exec_sql_query($db, "DELETE FROM all_tags WHERE all_tags.image_id = :imgid", $delete_params)->fetchAll();
  unlink($path);}

//upload image
if ( (isset($_POST["submit_upload"]))){

  if(((!file_exists($_FILES['box_file'])) || !is_uploaded_file($_FILES['box_file']))) {
    $file_msg = TRUE;
  }

  $file_citation = filter_input(INPUT_POST, 'citation', FILTER_SANITIZE_STRING);
  $file_description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
  $tag = filter_input(INPUT_POST, 'tag', FILTER_SANITIZE_STRING);
  $location = filter_input(INPUT_POST, 'location', FILTER_SANITIZE_STRING);
  $upload= $uploaded_file_info;

  if(empty($file_citation)){
    $cite_msg = TRUE;
  }

  if(empty($file_description)){
    $description_msg = TRUE;
  }

  if(empty($tag)){
    $tag_msg = TRUE;
  }

  if ($upload['error'] == UPLOAD_ERR_OK){

    $upload_info = $_FILES['box_file'];
    $basename = basename($upload_info['name']);
    $upload_ext = strtolower(pathinfo($basename, PATHINFO_EXTENSION));

    if(empty($upload_info)){
      $file_msg = TRUE;
    }

    $params = array(
      ':basename' => $basename,
      ':upload_ext' => $upload_ext,
      ':file_description' => $file_description,
      ':file_citation' => $file_citation,
    );

    $result = exec_sql_query($db, "INSERT INTO images (file_name, file_ext, citation, description) VALUES (:basename, :upload_ext, :file_citation, :file_description)", $params);

    if ($result) {

      $id = $db -> lastInsertId('id');

      $path = 'uploads/'.($id).'.'.$upload_ext;
      move_uploaded_file($upload_info["tmp_name"], $path);

      $params2 = array(
        ':location' => $location,
        );

      if($location == 'Queens'){
        $locid = 1;
      }
      else if($location == 'Manhattan'){
        $locid = 2;
      }
      else if($location == 'Brooklyn'){
        $locid = 3;
      }
      else if($location == 'The Bronx'){
        $locid = 4;
      }
      else if($location == 'Staten Island'){
        $locid = 5;
      }

      $params3 = array(
        ':tag' => $tag,
        ':id' => $id,);

      exec_sql_query($db, "INSERT INTO tags(image_id, tag) VALUES (:id, :tag)", $params3);

      $tagid = $db -> lastInsertId('id');

      $params4 = array(
        ':id' => $id,
        ':locid' => $locid,
        ':tagid' => $tagid,
      );

      exec_sql_query($db, "INSERT INTO all_tags(image_id, location_id, tag_id) VALUES (:id, :locid, :tagid)", $params4);

    }
    else{
      $upload_error = TRUE;
    }

  }

}
?>

<body>


  <?php include("includes/header.php");

  $currimg; ?>

    <ul>
        <?php
        // getting the images to show
        $records = exec_sql_query($db, "SELECT * FROM images")->fetchAll(PDO::FETCH_ASSOC);


        foreach ($records as $record) {

          //get tags for image
          $currid=$record['id'];
          $params= array(
            ":currid" => $currid);
          $tag_sql = exec_sql_query($db, "SELECT * FROM tags INNER JOIN all_tags ON all_tags.tag_id = tags.id WHERE all_tags.image_id = :currid", $params) -> fetchAll();?>

        <div class = "entry">
        <div class = "img_entry">

        <?php

        // link for every image to img.php page
          echo "<li><a href = 'img.php?link=".$record["id"]."'><img src = 'uploads/". $record["id"] . "." . $record["file_ext"]."' id = ".$record["id"] ." alt = ".$record['description']."></a></li>";
          echo "<!--Source taken from:".$record["citation"]."-->";
        ?></div><?php
        foreach($tag_sql as $tag){?>
          <div class = "tag_entry">
        <?php
          echo "#".$tag['tag']." ";
          ?></div>

          <?php }?>
          </div>

    <?php

        }
        ?></ul>

  <!-- Upload image form-->

  <div class = "footer">
  <div class = "form">
      <div class ='uploadform'>
    <h3>Want to share your favorite NYC food spot?</h3>

    </div>

    <!-- Source: drawn by Sarah Shin -->


    <?php if($upload_error){?>
      <p class = 'formfeedback'>Error uploading image.</p>
    <?php } ?>

    <form id="uploadFile" action="index.php" method = "post" enctype = "multipart/form-data">
    <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo MAX_FILE_SIZE; ?>" />

    <div class = "formlabel">
      <label for="box_file">Upload Picture:* </label>
      <div class = "forminput">
      <input id="box_file" type="file" name="box_file">
    </div>
    </div>

    <?php if($cite_msg){?>
      <p class = 'form_feedback'>Please provide a source for the image.</p>
    <?php } ?>

    <div class = "formlabel">
      <label for="box_cite">Image source:* </label>
      <div class = "forminput">
      <input type = "text" id="box_cite" name="citation">
    </div>
      </div>

    <?php if($tag_msg){?>
      <p class = 'form_feedback'>Please provide a tag for the image.</p>
    <?php } ?>

    <div class = "formlabel">
      <label for="tag">Tag:</label>
      <div class = "forminput">
      <input type = "text" id="tag" name="tag" placeholder = "ex) Restaurant, name of dish, etc.">
    </div>
      </div>

    <div class = "formlabel">
      <label for="location">Borough:</label>
      <div class = "forminput">
          <select name = 'location'>
            <option value = "Brooklyn">Brooklyn</value>
            <option value = "Manhattan">Manhattan</value>
            <option value = "Queens">Queens</value>
            <option value = "Staten Island">Staten Island</value>
            <option value = "The Bronx">The Bronx</value>
          </select>
      </div>
    </div>

    <?php if($description_msg){?>
      <p class = 'form_feedback'>Please provide a description for the image.</p>
    <?php } ?>

    <div class = "formlabel">
      <label for="box_desc">Description:</label>
      <div class = "forminput">
        <input type = "text" id="box_desc" name="description" >
    </div>
      </div>

    <div class="formlabel">
      <span></span>
      <button name="submit_upload" type="submit" class = "submit">Upload File</button>
    </div>
    </form>
  </div>



  <img src = 'images/cover.png' alt = 'nycdrawing'>
  <!-- Picture credits: drawn by Sarah Shin -->
  </div>


</body>

</html>
