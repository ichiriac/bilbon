<?php

require_once __DIR__ . '/bilbon.php';

use \bilbon\Gandalf as Storage;
use \bilbon\Hobbit as ActiveRecord;

// Defines the connector
Storage::$instances['default'] = array(
    'dsn' => 'mysql:host=localhost', // if already created use : ,dbname=epic
    'user' => 'root',
    'password' => ''
);

// Initialize database
Storage::get('default')->query('CREATE DATABASE IF NOT EXISTS epic');
Storage::get('default')->query('USE epic');
Storage::get('default')->query('CREATE TABLE IF NOT EXISTS dragons (
    id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(60),
    PRIMARY KEY (id)
)');
Storage::get('default')->query('TRUNCATE TABLE dragons'); 
Storage::get('default')->query('CREATE TABLE IF NOT EXISTS users (
    id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(60),
    age TINYINT UNSIGNED,
    PRIMARY KEY (id)
)');
Storage::get('default')->query('TRUNCATE TABLE users'); 
// Defines classes
class Dragon extends ActiveRecord {
    public static $table = 'dragons';
}
class Actor extends ActiveRecord {
    public static $table = 'users';
}

// Populate some data
$gollum = new Actor(array(
    'name' => 'Gollum',
    'age' => 255
));
$gollum->insert();
$bilbon = new Actor(array(
    'age' => 10
));
$bilbon->name = 'Bilbon';
$bilbon->insert();
$thorin = new Actor();
$thorin->name = 'Thorin';
$thorin->insert();
$thorin->age = 50;
$thorin->update();
$gandalf = new Actor(array(
    'name' => 'Gandalf',
    'age' => 200
));
$gandalf->insert();
$bad_guy = new Dragon(array(
    'name' => 'Smog'
));
$bad_guy->insert();
// Retrieving the data
echo "Bad guys : \n";
echo implode("------\n", Dragon::request('SELECT * FROM {table}'));

echo "\n-> Young actors : \n";
echo implode("------\n", Actor::request('SELECT * FROM {table} WHERE age < 100'));

echo "\n-> Old actors : \n";
echo implode("------\n", Actor::request('SELECT * FROM {table} WHERE age > 100'));

// kill the bad guy
$bad_guy->delete();
echo "\nBad guys (should be nobody at the story end): \n";
echo implode("------\n", Dragon::request('SELECT * FROM {table}'));
