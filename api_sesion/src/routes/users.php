
<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


$app = new \Slim\App;

$app->get('/api/users',function(Request $request, Response $response){
    $query="CALL sp_get_allUsers;";

    try{
        $db = new db();
        $db=$db->connect();
        $execute=$db->query($query);
        $users=$execute->fetchAll(PDO::FETCH_OBJ);
        $db=null;
        echo json_encode($users);
    }catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});

$app->get('/api/users/{nameUser}',function(Request $request, Response $response){
    $nameUser = $request->getAttribute('nameUser');
    $query="call proyecto_ciisa.sp_get_user('$nameUser');";
    try{
        $db = new db();
        $db=$db->connect();
        $execute=$db->query($query);
        $user=$execute->fetchAll(PDO::FETCH_OBJ);
        $db=null;
        echo json_encode($user);
    }catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});

?>