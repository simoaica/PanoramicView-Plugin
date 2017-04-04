<?php
  if (filter_has_var(INPUT_POST, 'continut')) {
    $continut = htmlentities($_POST['continut']);
    $file = 'customcss.txt';
    file_put_contents($file, $continut);
    echo 'am primit cererea, ai trimis '.$continut;
  } elseif (filter_has_var(INPUT_POST, 'incarc') && $_POST['continut'] = 'da') {
    $file = 'customcss.txt';
    if (file_exists($file)) {
      $continut = file_get_contents($file, true);
      echo $continut;
    } else {
      echo 'nasol';
    }
  } else {
    die('Nu ai ce cauta aici!');
  }
?>
