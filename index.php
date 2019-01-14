<?php


?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Page Title</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="css/index.css" />
</head>
<body class="">
<nav class="navbar navbar-expand-lg  bg-primary">
    <h1 class="text-light">GetColors FC</h1>
</nav>
    <div class="container mt-5">
        <div class="row">
            <div class="col-xl-4 col-lg-4 col-md-3 col-sm-1"></div>
                <div class="col-xl-4 col-lg-4 col-md-11 col-sm-11">
                     <h3 class="ml-5">GetColors FC</h3>
                    <form id="formLogin">
                        <div class="card p-4" id="loginUser">
                            <div class="form-group">
                                <label for="userText" class="my-3 mx-3" >Usuario</label>
                                <input type="text" class="form-control mr-3" id="user" name="nameUser" required>
                            </div>
                            <div class="form-group">
                                <label for="userText" class="my-3 mx-3" >Password</label>
                                <input type="password" class="form-control" id="password" name="pass" required>
                            </div>
                            <button class="btn btn-primary" type="submit">Enviar Datos</button>
                            <p class="my-3 mx-3"> Olvidaste tu contraseña <a href=""  id="recoverPassword" class="link">Presiona Aqui</a></p>
                            <div id="response" class="ml-4"></div>
                        </div>
                    </form>
                    
                    <form id="formRecoverPassword">
                        <div class="card p-4">
                            <form action="">
                                <div  class="form-group">
                                    <label for="userText" class="my-3 mx-3" >Ingresa Tu correo</label>
                                    <input type="mail" class="form-control mr-3" id="mail" required>
                                </div>
                                <button class="btn btn-primary" type="submit">Recuperar Contraseña</button>
                                <p class="my-3 mx-3"> Volver al Login <a href=""  id="recoverPasswordForget" class="link">Presiona Aqui</a></p>
                            </form>
                        </div>
                    </form>
                </div>  
            </div>
        </div>
    </div>
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/login.js"></script>
    <script src="js/user.js"></script>
</body>

</html>