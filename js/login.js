
$('#message').hide();
$('#formRecoverPassword').hide();

/* 
$('#formLogin').submit(function(e){
    var user={
        nameUser: $('#user').val(),
        password: $('#password').val()
    };
    $.post(
        'php/login.php',
        user,
        function(response){
            var resp = parseInt(response);
            console.log(resp);
            if(!resp){
                $('#message').show();
                setTimeout(function(){ 
                    $('#message').hide();
                }, 2000);
            }else{
                location.href="php/test.php";
            }

          
            if(!response.error){
                location.href="php/test.php";
            }else{
                $('#message').show();
                setTimeout(function(){ 
                    $('#message').hide();}, 2000);
            }         
    });
    e.preventDefault();
});
*/


$('#recoverPassword').click(function(e){
    $('#formLogin').hide();
    $('#formRecoverPassword').show();
    e.preventDefault();

});

$('#recoverPasswordForget').click(function(e){
    $('#formLogin').show();
    $('#formRecoverPassword').hide();
    e.preventDefault();

});