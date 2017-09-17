<!DOCTYPE html>
<html>
    <head>
        <link href="../css/bootstrap.css" media="screen" rel="stylesheet">
            <link href="../css/main.css" media="screen" rel="stylesheet">
                <meta charset="utf-8">
                    <title>
                        Verify User
                    </title>
                </meta>
            </link>
        </link>
    </head>
    <body>
        <?php
require 'includes/functions.php';
include 'config.php';

//Pulls variables from url. Can pass 1 (verified) or 0 (unverified/blocked) into url
$uid    = $_GET['uid'];
$verify = $_GET['v'];

$e       = new SelectEmail;
$eresult = $e->
    emailPull($uid);

$email    = $eresult['email'];
$username = $eresult['username'];

$v = new Verify;

if (isset($uid) && !empty(str_replace(' ', '', $uid)) && isset($verify) && !empty(str_replace(' ', '', $verify))) {

    //Updates the verify column on user
    $vresponse = $v->verifyUser($uid, $verify);

    //Success
    if ($vresponse == 'true') {
        echo $activemsg;

        //Send verification email
        $m = new MailSender;
        $m->sendMail($email, $username, $uid, 'Active');
    } else {
        //Echoes error from MySQL
        echo $vresponse;
    }
} else {
    //Validation error from empty form variables
    echo 'An error occurred... click
        <a href="index.php">
            here
        </a>
        to go back.';
}
;

?>
    </body>
</html>
