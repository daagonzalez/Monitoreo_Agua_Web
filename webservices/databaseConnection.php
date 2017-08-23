<?php 
require '../vendor/autoload.php';

function connectDatabaseCollection($database,$collection,$readOrWrite)//for read 0 for readWrite 1.
{
	$client=new MongoDB\Client("mongodb://localhost:27017");
	return $client->$database->$collection;

    /*
     * Uncomment:Use this for real server
 	*/
	/*$user=$readWrite==1?'lectorEscritor':'lector';
    $password=$readWrite==1?'WatMonCR2017':'WatMonCR2017';
    try { 
        $client = new MongoDB\Client( 'mongodb://localhost:27017',
        [ 'username' => $user,
         'password' => $password,
         'authSource' => $database,
        ]);
        $database = $client->$database;
        $collection = $database->$collection;
        return $collection;
    } catch (MongoConnectionException $e) {
        return 0;
    }*/
}


function connectDatabaseClient($database,$readOrWrite){
	return new MongoDB\Client("mongodb://localhost:27017");
    /*
     * Uncomment:Use this for real server
 	*/
  	/*$user=$readWrite==1?'lectorEscritor':'lector';
    $password=$readWrite==1?'WatMonCR2017':'WatMonCR2017';
    try {
        return new MongoDB\Driver\Manager("mongodb://localhost:27017",
            [ 'username' => $user,
                'password' => $password,
                'authSource' => $database,
            ]);
    } catch (MongoConnectionException $e) {
        return 0;
    } */  
}