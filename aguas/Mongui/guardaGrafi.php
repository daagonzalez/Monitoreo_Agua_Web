<?php
require_once 'mongo_login.php';
$_SESSION['userID'] = 4;
$directions = $_POST['json'];
$datos = json_decode($directions,true);
$datos['uID'] = $_SESSION['userID'];
$bulk = new MongoDB\Driver\BulkWrite(['ordered' => true]);
$bulk->insert($datos);

$manager = new MongoDB\Driver\Manager('mongodb://' .
                USERNAME . ':' . PASSWORD .
                '@' . HOST_IP . ':' . PORT
            );
$writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 1000);

try {
    $result = $manager->executeBulkWrite('jm.grafi_usuarios', $bulk, $writeConcern);
} catch (MongoDB\Driver\Exception\BulkWriteException $e) {
    $result = $e->getWriteResult();

    // Check if the write concern could not be fulfilled
    if ($writeConcernError = $result->getWriteConcernError()) {
        printf("%s (%d): %s\n",
            $writeConcernError->getMessage(),
            $writeConcernError->getCode(),
            var_export($writeConcernError->getInfo(), true)
        );
    }

    // Check if any write operations did not complete at all
    foreach ($result->getWriteErrors() as $writeError) {
        printf("Operation#%d: %s (%d)\n",
            $writeError->getIndex(),
            $writeError->getMessage(),
            $writeError->getCode()
        );
    }
} catch (MongoDB\Driver\Exception\Exception $e) {
    printf("Other error: %s\n", $e->getMessage());
    exit;
}
print('{"Resultado": "exito"}');

?>