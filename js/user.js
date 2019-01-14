var form=document.getElementById("formLogin");
var response=document.getElementById("response");

form.addEventListener('submit',function(e){
    e.preventDefault();
    var formDates= new FormData(form);
    //Consuminedo Api
    var nameUser = formDates.get('nameUser');
    var pass = formDates.get('pass');
    url='http://localhost/proyecto_ciisa/api_sesion/public/api/users/'+ formDates.get('nameUser');
    fetch(url)
        .then(res=>res.json())
        .then(data=>{
            if(data.length===0){
                response.innerHTML=`
                    <div class="alert alert-danger" role="alert">
                        Usuario o Contraseña incorrecta.
                    </div>`;
            }else{
                if(data[0].user==nameUser && data[0].password==pass){
                    if(data[0].type_user==1){
                        location.href="php/admin.php";
                    }else{
                        location.href="php/user.php";
                    }
                }else{
                    response.innerHTML=`
                    <div class="alert alert-danger" role="alert">
                        Usuario o Contraseña incorrecta.
                    </div>`;
                }
            }
        })       
});
