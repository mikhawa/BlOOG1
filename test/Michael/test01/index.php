<?php

require_once '../../../config.php';

use model\Interface\InterfaceManager;


// Autoload classes
spl_autoload_register(function ($class) {
    $class = str_replace('\\', '/', $class);
    require PROJECT_DIRECTORY.'/' .$class . '.php';
});

class TestInterface implements InterfaceManager{

    protected PDO $connection;
    public function __construct(PDO $pdo)
    {
        $this->connection = $pdo;
    }

    public function selectAll(): array
    {
        // TODO: Implement selectAll() method.
    }

    public function selectOneById(int $id): object
    {
        // TODO: Implement selectOneById() method.
    }

    public function insert(object $object): void
    {
        // TODO: Implement insert() method.
    }

    public function update(object $object): void
    {
        // TODO: Implement update() method.
    }

    public function delete(int $id): void
    {
        // TODO: Implement delete() method.
    }
}

$test = new TestInterface(new PDO(DB_TYPE . ':host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET, DB_LOGIN, DB_PWD));

var_dump($test);
