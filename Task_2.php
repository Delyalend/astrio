<?php

$db = mysqli_connect('localhost', 'root', 'root', 'testtask_db');

function createTableInDb($db)
{
    $sqlForCreateWorkerTable = "create table if not exists worker(
                                id int PRIMARY key AUTO_INCREMENT,
                                firstname varchar(100) not null,
                                lastname varchar(100) not null,
                                was_car boolean not null);";
    $sqlForCreateCarTable = "create table if not exists car(
                            user_id int(11) not null,
                            model varchar(100) DEFAULT null
                            );";
    $sqlForCreateChildTable = "create table if not exists child(
                                user_id int(11) not null,
                                name varchar(100) not null
                                );";
    mysqli_query($db, $sqlForCreateWorkerTable);
    mysqli_query($db, $sqlForCreateCarTable);
    mysqli_query($db, $sqlForCreateChildTable);
}

function insertIntoTableWorker($db, $id, $firstname, $lastname, $was_car)
{
    mysqli_query($db, "insert into worker values ($id,'$firstname','$lastname',$was_car);");
}

function setCarToWorker($db, $id, $modelCar) {
    insertIntoTableCar($db, $id, $modelCar);
    $query = "update worker set was_car = 1 where id = $id;";
    mysqli_query($db, $query);
}

function insertIntoTableChild($db, $userId, $name)
{
    mysqli_query($db, "insert into child values ($userId,'$name');");
}

function insertIntoTableCar($db, $userId, $model)
{
    mysqli_query($db, "insert into car values ($userId,'$model');");
}

function deleteFromDb($db) {
    mysqli_query($db,"delete from child");
    mysqli_query($db,"delete from car");
    mysqli_query($db,"delete from worker");
}

createTableInDb($db);
insertIntoTableWorker($db, 1, 'Алан', 'По', 1);
insertIntoTableWorker($db, 2, 'Борис', 'Акунин', 1);
insertIntoTableWorker($db, 3, 'Лев', 'Толстой', 0);
insertIntoTableCar($db, 1, "Шкода");
insertIntoTableCar($db, 2, "Лада");
insertIntoTableChild($db, 1, "Ваня");
insertIntoTableChild($db, 2, "Саня");

function printData($db, $query) {
    $res = mysqli_query($db, $query);
    $rows = [];
    while ($row = $res->fetch_array(true)) {
        $rows[] = $row;
    }
    foreach ($rows as $key => $value) {
        echo "<p>" . print_r($value) . "</p>";
    }
}

//----------------------------------------Запрос--------------------------------------------------
$query = "select w.firstname, w.lastname, ch.name as child_name, car.model as car_model 
            from worker w 
            left join child ch on ch.user_id = w.id 
            left join car on car.user_id = w.id 
            where w.was_car = true";


printData($db,$query);
//У Льва Толстого нет и не было автомобиля - в браузер он не попадёт
//output:
//Array ( [firstname] => Алан [lastname] => По [child_name] => Ваня [car_model] => Шкода )
//Array ( [firstname] => Борис [lastname] => Акунин [child_name] => Саня [car_model] => Лада )

//Добавим авто Льву Толстому
setCarToWorker($db, 3, "Порше");


//снова выводим данные
echo "<div></div>";
printData($db,$query);
//output:
//Array ( [firstname] => Алан [lastname] => По [child_name] => Ваня [car_model] => Шкода )
//Array ( [firstname] => Борис [lastname] => Акунин [child_name] => Саня [car_model] => Лада )
//Array ( [firstname] => Лев [lastname] => Толстой [child_name] => [car_model] => Порше )

deleteFromDb($db);













