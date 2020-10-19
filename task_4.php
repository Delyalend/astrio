<?php

abstract class AbstractBox
{

    protected $storage = array();

    protected static $instance;

    protected function __construct()
    {
    }

    protected function __clone()
    {
    }

    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize a singleton.");
    }

    public static function getInstance()
    {
        $cls = static::class;
        if (!isset(self::$instance[$cls])) {
            self::$instance[$cls] = new static();
        }
        return self::$instance[$cls];
    }

    public function setValue($key, $value)
    {
        if (array_key_exists($key, $this->storage)) {
            $this->storage[$key] = $value;
        } else {
            array_push($this->storage, array($key => $value));
        }
    }

    public function getValue($key)
    {
        $mas = $this->storage;
        foreach ($mas as $key2 => $value) {
            foreach ($value as $key3 => $value3) {
                if ($key == $key3) {
                    return $value;
                }
            }
        }
        return null;
    }


    public static abstract function save();

    public static abstract function load();
}

class FileBox extends AbstractBox
{

    private $filePath;

    public static function load()
    {
        if (!isset($file)) {
            self::getInstance()->storage = unserialize(file_get_contents(self::getInstance()->filePath));
        }
    }

    public static function save()
    {
        if (!isset($filePath)) {
            $data = serialize(self::getInstance()->storage);
            file_put_contents(self::getInstance()->filePath, $data);
        }
    }

    public static function setFilePath($filePath)
    {
        self::getInstance()->filePath = $filePath;
    }
}

class DbBox extends AbstractBox
{

    private $db;

    //Загрузить данные из базы в storage
    public static function load()
    {
        if (isset(self::getInstance()->db)) {

            $res = mysqli_query(self::getInstance()->db, "select * from test where id = 1");
            $row = $res->fetch_row();
            foreach ($row as $key => $value) {
                self::getInstance()->storage = unserialize($value);
            }
        }
    }

    //Сохранить storage в базу
    public static function save()
    {
        if (isset(self::getInstance()->db)) {
            $string = serialize(self::getInstance()->storage);

            //Попробуем получить запись из БД
            $valueFromDb = mysqli_query(self::getInstance()->db, "select * from test where id = 1");


            //Если запись есть, то её нужно изменить
            if ($valueFromDb) {
                $sql = "update test set val = '$string' where id = 1;";
            } //Если записи нет, то добавить
            else {
                $sql = "insert into test values (1,'$string');";
            }
            mysqli_query(self::getInstance()->db, $sql);
        }
    }

    public static function setDb($host, $user, $pass, $db)
    {
        $sql = "create table if not exists test (id int PRIMARY KEY AUTO_INCREMENT, val varchar(1000))";
        self::getInstance()->db = mysqli_connect($host, $user, $pass, $db);
        mysqli_query(self::getInstance()->db, $sql);
        if (!db) {
            echo("<P>В настоящий момент сервер базы данных не доступен, поэтому 
           корректное отображение страницы невозможно.</P>");
            exit();
        }
    }
}

$fileBox = FileBox::getInstance();
$fileBox->setFilePath("file.txt");
$fileBox->setValue("array_1", array("1" => "первое значение", "2" => "второе значение", "3" => "третье значение"));
$fileBox->setValue("val2", "просто строка");
$fileBox->save();
$fileBox->load();
print_r($fileBox->getValue("val2"));
echo "<div></div>";


$dbBox = DbBox::getInstance();
$dbBox->setDb('localhost', 'root', 'root', 'testtask_db');

$dbBox->setValue("Я", array("правильно" => "делаю?:)"));
$dbBox->setValue("простоЗначение", 5);
$dbBox->setValue("ещёОдинМассив", array("Белый" => "снег", "серый" => "лёд"));
$dbBox->save();

$dbBox->load();
print_r($dbBox->getValue('Я'));
echo "<div></div>";
print_r($dbBox->getValue('простоЗначение'));

