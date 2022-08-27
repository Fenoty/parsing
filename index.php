<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style.css">
    <title>XML выгрузка</title>
</head>
<body>

    <div class="input__file">
        <div class="input__text">Выгрузить XML файл в БД</div>
        <form method="post" enctype="multipart/data">
            <input type="submit" name="submit" value="Выгрузить">
        </form>
    </div>
    
</body>
</html>
<?php 
    include "database/connect.php";

    global $pdo;

    function change($date){

    }

    error_reporting(E_ALL);
    ini_set("display_errors", "on");

    $xml = simplexml_load_file('xmldata/data.xml'); // название файла и его путь


    if (isset($_POST['submit'])) {
        $count = $pdo->query("SELECT count(*) FROM public.data")->fetch(PDO::FETCH_ASSOC);
        if ($count['count']<=0) {
            foreach ($xml->offers->offer as $key) {
                $body = $key->{'body-type'};
                $engine = $key->{'engine-type'};
                $gear = $key->{'gear-type'};
                $pdo->query("INSERT INTO public.data (id, mark, model, generation, year, run, color, body_type, engine_type, transmission, gear_type, generation_id) VALUES 
                ('$key->id', '$key->mark', '$key->model', '$key->generation', '$key->year', '$key->run', 
                '$key->color', '$body', '$engine', '$key->transmission', '$gear', '$key->generation_id')");
            }
        }
        else {
            $data_array_tag = ['id', 'mark', 'model', 'generation', 'year', 'run', 'color', 'body_type', 'engine_type','transmission', 'gear_type', 'generation_id'];
            $data_xml = ['id', 'mark', 'model', 'generation', 'year', 'run', 'color', 'body-type', 'engine-type','transmission', 'gear-type', 'generation-id'];
            $data_array = [];
            foreach($pdo->query("SELECT * FROM public.data") as $r){
                array_push($data_array, $r);
            }
            $g=0;
            foreach ($xml->offers->offer as $key) {
                $i = 0;
                foreach ($data_array_tag as $s){
                    $id = $key->id;
                    $pdo->query("UPDATE public.user SET '$s' = '$key->{$data_xml[$i]}' WHERE 'id' = $id");
                    echo $data_array[$g][$s].' ('.$key->{$data_xml[$i]}.') ';  
                    $i++;
                }
                $g++;   
                echo '</br>';
            }      
        }          
            echo 'в базе уже есть данные';
    }

        echo '<b>Выгрузка в БД завершена</b>';
