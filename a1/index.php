<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="stylesheet" type="text/css" href="./style.css">
</head>
<body>  

</body>

<?php 
$filename = './image.png';
$MB = 1048576;  //количество байт в 1M
$info   = getimagesize($filename);
$width  = $info[0];
$height = $info[1];

$required_memory = Round($width * $height * $info['bits']);
$required_memory=Round(($required_memory/$MB)/1.48);
$memoryLimit = ini_get("memory_limit");

if ($required_memory>$memoryLimit){
    //php возможно не сможет обработать такой файл из-за ограничений памяти, поэтому выводим его пользователю как есть
    //не смог создать файл для теста больше 10000*10000 px в фотошопе
//     echo 'Размер этого файла '.$width.'x'.$height.' пикселей.<br>PHP-процесс обработки этого файла требует не менее '.$required_memory.'МБ, и может съесть память сервера.<br>';
//     echo 'Поэтому файл будет выдан в браузер в исходном виде';
     echo "<img src='$filename' alt='' class='banner' />";
}else
{
    //Создаем на сервере уменьшенную копию исходного файла и  отдаем её пользователю
    $img = imageCreateFromPng($filename); 
    $src = 'small_file.png';
// Размеры нового файла.
$w = 200;
$h = 0;
if (empty($w)) {
	$w = ceil($h / ($height / $width));
}
if (empty($h)) {
	$h = ceil($w / ($width / $height));
}
$tmp = imageCreateTrueColor($w, $h);
if ($type == 1 || $type == 3) {
	imagealphablending($tmp, true); 
	imageSaveAlpha($tmp, true);
	$transparent = imagecolorallocatealpha($tmp, 0, 0, 0, 127); 
	imagefill($tmp, 0, 0, $transparent); 
	imagecolortransparent($tmp, $transparent);    
}   
$tw = ceil($h / ($height / $width));
$th = ceil($w / ($width / $height));
if ($tw < $w) {
	imageCopyResampled($tmp, $img, ceil(($w - $tw) / 2), 0, 0, 0, $tw, $h, $width, $height);        
} else {
	imageCopyResampled($tmp, $img, 0, ceil(($h - $th) / 2), 0, 0, $w, $th, $width, $height);    
}            
$img = $tmp;
	imagePng($img,$src);
	echo "<img src='$src' alt='' class='banner' />";
}
?>

</html>