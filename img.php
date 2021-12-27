<?php include("includes/init.php");

include("includes/head.php");

$currid= $_REQUEST['link'];

if (isset($_POST['submit_tags'])){
    $edit_true = TRUE;}

if($_SERVER['REQUEST_METHOD'] == "POST"){
    $imgid = $_REQUEST['imageid'];
    $imgext = $_REQUEST['imageext'];}

?>
<body>

<?php include("includes/header.php");

// if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['edit'])){
//    $show_editform = TRUE; }

$params= array(
    ":currid" => $currid);

$sql = "SELECT * FROM images WHERE images.id = :currid";
$records = exec_sql_query($db, $sql, $params);//->fetchAll(PDO::FETCH_ASSOC);

$tag_sql = exec_sql_query($db, "SELECT * FROM tags INNER JOIN all_tags ON all_tags.tag_id = tags.id WHERE all_tags.image_id = :currid", $params) -> fetchAll();

$loc_sql = exec_sql_query($db, "SELECT * FROM location_tags INNER JOIN all_tags ON all_tags.location_id = location_tags.id INNER JOIN images on images.id = all_tags.image_id WHERE images.id = :currid", $params) -> fetchAll();

$alltag_sql = exec_sql_query($db, "SELECT * FROM tags") -> fetchAll();

?>

<div class = "current_page">

<?php
foreach($records as $record){

?>
<div class = 'curr_img'>
    <img src = "uploads/<?php echo $record['id'].".".$record['file_ext'];?>">

</div>
<div class = "curr_descriptions">

<div class = "editbuttons">

<div class = 'option'>

    <form id = 'confirmedit' action = 'img.php?link=<?php echo $currid ?>' method = 'POST'>
     <button type = 'submit' name = 'edit' id = 'edit' class = "button2">.</br>.</br>.</button></br>
     </form>
</div>

<?php if(isset($_REQUEST['edit'])){?>
<div class = "options">

     <form id = 'editTags' action = 'img.php?link=<?php echo $currid ?>' method = 'POST'>
         <button type = "submit" id = 'add' name = 'add' class = "button3">Add Tag</button>

         <button type = "submit" id = 'existag' name = 'existag' class = "button3">Add an Existing Tag</button>

         <button type = "submit" id = 'delete' name = 'delete' class = "button3">Delete Tag</button>
     </form>
</div>

</div>

     <?php }

     if(isset($_REQUEST['add'])){?>
     <form id = 'addnewtag' action = 'img.php?link=<?php echo $currid ?>' method = 'POST'>
         <div class = "formlabel">
         <label for="newtag">Add new tag: </label>
         <div class = "forminput">
             <input type = "text" name = 'newtag' id = 'newtag'>
         </div>
         </div>
         <button type = "submit" name = "submit_tags" id = "submit_tags" class = "savebutton">Save Changes</button>
         </form>
         <?php }

     if(isset($_REQUEST['existag'])){?>

         <form id = 'addexistingtag' action = 'img.php?link=<?php echo $currid ?>' method = 'POST'>

         <div class = "formlabel">
         <label for = "existingtag">Add existing tag </label>
         <div class = "forminput">
             <select name = "existingtag" id = 'existingtag'>
                 <option value = "">Select</option>
             <?php foreach($alltag_sql as $tag){
                 echo "<option value = '".$tag['tag']."'>".$tag['tag']."</value>";}
             ?></select>
         </div>
         </div>
         <button type = "submit" name = "submit_tags" id = "submit_tags" class = "savebutton">Save Changes</button>
             </form>

     <?php }
     if(isset($_REQUEST['delete'])){?>

         <form id = 'deletetags' action = 'img.php?link=<?php echo $currid ?>' method = 'POST'>

         <div class = "formlabel">
         <label for = "Deletetag">Delete existing tag </label>
         <div class = "forminput">
             <select name = "deletetag" id = 'deletetag'>
             <option value = ""></option>
             <?php foreach($tag_sql as $tag){
                 echo "<option value = '".$tag['tag']."'>".$tag['tag']."</value>";}
             ?></select>
         </div>
         </div>

         <button type = "submit" name = "submit_tags" id = "submit_tags" class = "savebutton">Save Changes</button>
         </form>
         <?php }
}
     ?>


 <?php

 //change tags form inputs

 $overlap_msg = FALSE;
 if ($edit_true){
     if((($_REQUEST['newtag']))){
        $new_tag = filter_input(INPUT_POST, 'newtag', FILTER_SANITIZE_STRING);

        if(!empty($new_tag)){

            $newtag_sql = exec_sql_query($db, "SELECT * FROM tags") -> fetchAll();
            foreach($newtag_sql as $tag){
                if($new_tag == $tag['tag']){
                    $overlap_msg = TRUE;
                }
            }
            if (!$overlap_msg){
                $tag_params = array(
                    ':id'=> $currid,
                    ':newtag' => $new_tag
                );
                exec_sql_query($db, "INSERT INTO tags(image_id, tag) VALUES (:id, :newtag)", $tag_params)-> fetchAll();
                $tagid = $db -> lastInsertId('id');

                $locparams = array(
                ':currid' => $currid,
                ':tagid' => $tagid,
                );

                exec_sql_query($db, "INSERT INTO all_tags(image_id, tag_id) VALUES (:currid,:tagid)", $locparams)-> fetchAll();
        }
    }
    }


     else if(($_REQUEST['existingtag'])){
         $existingtag = filter_input(INPUT_POST, 'existingtag', FILTER_SANITIZE_STRING);
         $tag_params2 = array(
             ':id'=> $currid,
             ':newtag' => $existingtag
         );

         $params_1 = array(
             ':newtag' => $existingtag
         );

         $existtag_sql = exec_sql_query($db, "SELECT * FROM tags INNER JOIN images ON images.id = tags.image_id WHERE tags.tag = :newtag", $params_1) -> fetchAll();

         foreach($existtag_sql as $tag){
             $tagid = $tag['id'];
             $locparams = array(
                 ':currid' => $currid,
                 ':tagid' => $tagid
             );
             exec_sql_query($db, "INSERT INTO all_tags(image_id, tag_id) VALUES (:currid, :tagid)", $locparams)-> fetchAll();}

     }
     else if(($_REQUEST['deletetag'])){
         $deletetag = filter_input(INPUT_POST, 'deletetag', FILTER_SANITIZE_STRING);
         $tag_params3 = array(
             ':deletetag' => $deletetag,
         );
         $temp = exec_sql_query($db, "SELECT * FROM tags WHERE tags.tag LIKE :deletetag", $tag_params3)-> fetchAll();
         $tagtempid;
         foreach($temp as $temp_){
             $tagtempid = $temp_['id'];
             $tag_params4 = array(
                 ':tagtempid' => $tagtempid,

             );
             exec_sql_query($db, "DELETE FROM all_tags WHERE all_tags.tag_id = :tagtempid", $tag_params4)-> fetchAll();

         }

     }
    }


$params= array(
    ":currid" => $currid);

$sql = "SELECT * FROM images WHERE images.id = :currid";
$records = exec_sql_query($db, $sql, $params);//->fetchAll(PDO::FETCH_ASSOC);

$tag_sql = exec_sql_query($db, "SELECT * FROM tags INNER JOIN all_tags ON all_tags.tag_id = tags.id WHERE all_tags.image_id = :currid", $params) -> fetchAll();

$loc_sql = exec_sql_query($db, "SELECT * FROM location_tags INNER JOIN all_tags ON all_tags.location_id = location_tags.id INNER JOIN images on images.id = all_tags.image_id WHERE images.id = :currid", $params) -> fetchAll();

$alltag_sql = exec_sql_query($db, "SELECT * FROM tags") -> fetchAll();


 ?>
<?php
    if($overlap_msg){?>
        <p class = 'form_feedback'>Tag already exists. Enter new tag.</p>
<?php  }
     ?>
    <p><strong>Description: </strong><?php echo $record['description']?></p>
    <p><strong>Picture creds: </strong><?php echo $record['citation']?></p>
    <p><strong>Tags: </strong>
    <?php
    foreach($tag_sql as $tag){?>
       #<?php echo $tag['tag'] ?><?php
    }?>
    </p>
     <?php
    foreach($loc_sql as $loc){?>
        <p><strong>Borough: </strong> #<?php echo $loc['location']?></p><?php
    }



 ?>

<form id="deleteImage" action="index.php" method = 'POST'>
         <input type = 'hidden' id = 'imageid' name = 'imageid' value = "<?php echo $record['id'] ?>">
         <input type = 'hidden' id = 'imageext' name = 'imageext' value = "<?php echo $record['file_ext'] ?>">
         <div class = "deletebutton">
             <button type = "submit" name="delete" id = "delete" value = "delete" class = "button">Delete</button>
         </div>
     </form>

</div>
    </div>



</body>
</html>
