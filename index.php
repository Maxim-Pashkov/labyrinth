<?php

if(empty($argv[1])) {
    echo "Передайте параметром название тестового файла\n";
    die();
}

if(!file_exists($argv[1])) {
    echo "Такого файла не существует\n";
    die();
}

$handle = fopen($argv[1], 'r');
$content = fread($handle, filesize($argv[1]));
fclose($handle);

echo $content;
die();