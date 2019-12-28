<?php

require_once('./Labyrinth.php');

if(empty($argv[1])) {
    echo "Передайте параметром название тестового файла\n";
    die();
}

if(empty($argv[2])) {
    echo "Передайте параметром название конечного файла\n";
    die();
}

if(!file_exists($argv[1])) {
    echo "Такого файла не существует\n";
    die();
}

$handleInput = fopen($argv[1], 'r');
$countTests = (int) fgets($handleInput);
$result = '';
if(!$countTests) {
    echo "Первая строка должна обозначать количество выполняемых тестов\n";
    die();
}

echo "Выполнение...\n";
for($i = 0; $i < $countTests; $i++) {
    $num = $i + 1;
    $title = "Case #{$num}";
    $result.= "$title\n";

    $stepsTrack = fgets($handleInput);

    echo "$title\n";

    if(!is_string($stepsTrack)) {
        echo "$title: Не удалось получить строку для задания";
        die();
    }

    if(!preg_match("/^W[WLR]*W\sW[WLR]*W$/", $stepsTrack)) {
        echo "$title: Все пути должны состоять как минимум из двух символов, содержать только символы 'W', 'L', 'R', начинаться и заканчиваться символом 'W'";
        die();
    }

    list($stepsStraigh, $stepsReverse) = explode(" ", $stepsTrack);

    $data = (new Labyrinth())
        ->action($stepsStraigh)
        ->actionReverse($stepsReverse)
        ->getData();

    foreach($data as $row) {
        $result.= implode('', $row)."\n";
    }
}
fclose($handleInput);

$handleOutput = fopen($argv[2], 'w');
fwrite($handleOutput, $result);
fclose($handleOutput);

echo "Выполнение завершено!\n";
die();