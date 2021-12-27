<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <title>NY Food Guide</title>

  <link rel="stylesheet" type="text/css" href="styles/site.css" media="all" />

  <?php if ( isset($scripts) ) {
    foreach ($scripts as $script) {
      echo "<script src=\"" . $script . "\" type=\"text/javascript\"></script>\n";
    }
  } ?>
</head>
