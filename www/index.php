<?php
require 'vendor/autoload.php';

Flight::register('db', 'PDO', array("mysql:host=".$_ENV["MYSQL_HOST"].";dbname=".$_ENV["MYSQL_DATABASE"]."",$_ENV["MYSQL_USER"],$_ENV["MYSQL_PASSWORD"]));
Flight::route('GET /', function(){
    phpinfo();
});

Flight::route('GET /persons', function(){
    $sentence = Flight::db()->prepare("SELECT * FROM Person");
    $sentence->execute();
    $data = $sentence->fetchAll();
    Flight::json($data);
});

Flight::route('GET /persons/@id:[0-9]+', function($id){
    $sentence = Flight::db()->prepare("SELECT * FROM Person WHERE id=?");
    $sentence->bindParam(1, $id);
    $sentence->execute();
    $data = $sentence->fetchAll();
    Flight::json($data);
});

Flight::route('POST /persons', function(){
    $id = (Flight::request()->data->id);
    $name = (Flight::request()->data->name);
    $sentence = Flight::db()->prepare("INSERT INTO Person (name) VALUES (?)");
    $sentence->bindParam(1, $name);
    $sentence->execute();
    Flight::jsonp(["Person added"]);
});  
Flight::route('PUT /persons/@id:[0-9]+', function($id){
    $name = (Flight::request()->data->name);
    $sentence = Flight::db()->prepare("UPDATE Person SET name=? WHERE id=?");
    $sentence->bindParam(1, $name);
    $sentence->bindParam(2, $id);
    $sentence->execute();
    Flight::jsonp(["Person updated"]);
});
Flight::route('DELETE /persons/@id:[0-9]+', function($id){
    $sentence = Flight::db()->prepare("DELETE FROM Person WHERE id=?");
    $sentence->bindParam(1, $id);
    $sentence->execute();
    Flight::jsonp(["Person deleted"]);
});
Flight::start();