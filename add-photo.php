<?php

	foreach($_FILES['images']['error'] as $k=>$key){

    $date = date("d-m-y-h-s");
    $path = $_SERVER['DOCUMENT_ROOT'].'/alboms/'. $date.'/';
    $pathDB = './alboms/'. $date.'/';

    if (!is_dir($path)) {
        mkdir($path, 0777, true);
    }

    $toLower = mb_strtolower(basename($_FILES['images']['name'][$k]));
    $uploadfile = $path.$toLower;

    if (move_uploaded_file($_FILES['images']['tmp_name'][$k], $uploadfile))
    {

                // ЗАГРУЗКА ПРЕВ'Ю
            $title_photo = $_FILES['img_title']['name'];
            $extension = pathinfo($title_photo, PATHINFO_EXTENSION);
            $filename = "preview-photo" . "." . $extension;
            move_uploaded_file($_FILES['img_title']['tmp_name'],  $path . $filename);

    }

    else
    {
        echo "Possible file : ", $_FILES['images']['name'][$k], " upload attack!\n";
    }

}

function resizeCompressJPG($path){
    
        $directory = $path;
        $images = glob($directory."*.jpg");
        
        
        foreach($images as $image) {
            $im_php = imagecreatefromjpeg($image);
            $im_php = imagescale($im_php, 1280);
            $new_height = imagesy($im_php);
            $new_name = str_replace('-1920x1080', '-640x'.$new_height, basename($image)); 
            imagejpeg($im_php, $path.$new_name, 30);
            
        }
}


    resizeCompressJPG($path);

function resizeCompressJpeg($path){
   
        $directory = $path;
        $images = glob($directory."*.jpeg");
        
        
        foreach($images as $image) {
            $im_php = imagecreatefromjpeg($image);
            $im_php = imagescale($im_php, 1280);
            $new_height = imagesy($im_php);
            $new_name = str_replace('-1920x1080', '-640x'.$new_height, basename($image)); 
            imagejpeg($im_php, $path.$new_name, 30);
            
        }
}


  resizeCompressJpeg($path);

    include_once 'db.php';
    
    // добавляем запись в карту сайта
       include_once './sitemap.php';
    //
    
    
  // запись в бд
$arrPhotos =  mb_strtolower($_FILES['images']['name']);

$stringPhotos = implode(":^:", $arrPhotos);
$posted = explode('-', $_POST['posted']);
$normalData = $posted[2].'-'.$posted[1].'-'.$posted[0];

$time = date("H:i:s");


$sql = "INSERT INTO `galery` (`path`, `img_title`, `images`, `title`, `date`, `description`,`keywords`, `time`) VALUES (:path, :img_title, :images, :title, :posted, :description, :keywords, :time)";

 $statemants = $db->prepare($sql);
 $statemants -> bindParam(":path", $pathDB);
 $statemants -> bindParam(":img_title", $filename);
 $statemants -> bindParam(":images", $stringPhotos);
 $statemants -> bindParam(":title", $_POST['title']);
 $statemants -> bindParam(":posted", $_POST['posted']);
 $statemants -> bindParam(":description", $_POST['description']);
 $statemants -> bindParam(":keywords", $_POST['keywords']);
 $statemants -> bindParam(":time", $time);

 $statemants->setFetchMode(PDO::FETCH_ASSOC);
 $statemants->execute();
 siteMapGallerues();
 header("Location:");
