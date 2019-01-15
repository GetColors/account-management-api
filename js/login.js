
$('#message').hide();
$('#formRecoverPassword').hide();

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