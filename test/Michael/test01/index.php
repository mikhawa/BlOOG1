<?php
require_once '../../../config.php';
// Autoload classes
spl_autoload_register(function ($class) {
    $class = str_replace('\\', '/', $class);
    require PROJECT_DIRECTORY.'/' .$class . '.php';
});


require "InterfaceSlugManager.php";
require "CategoryMapping.php";

use model\Interface\InterfaceManager;
use model\Trait\TraitSlugify;



class CategoryManager implements InterfaceManager, InterfaceSlugManager{

    protected PDO $connection;
    public function __construct(PDO $pdo)
    {
        $this->connection = $pdo;
    }

    use TraitSlugify;

    public function selectAll(): array
    {
        $stmt = $this->connection->query('SELECT * FROM `category`');
        return $stmt->fetchAll();
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

    public function selectOneBySlug(string $slug): object
    {
        /*
        $query = $this->connection->prepare('SELECT * FROM `category` WHERE `category_slug` = :slug');
        $query->execute(['slug' => $slug]);
        return new CategoryMapping($query->fetch(PDO::FETCH_ASSOC));
        */
        return new CategoryMapping(['category_id' => 1, 'category_name' => 'test', 'category_slug' => 'test', 'category_description' => 'test', 'category_parent' => 1]);
    }
}

$test = new CategoryManager(new PDO(DB_TYPE . ':host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET, DB_LOGIN, DB_PWD));


var_dump($test, $test->selectAll(),$test->selectOneBySlug('test'));
