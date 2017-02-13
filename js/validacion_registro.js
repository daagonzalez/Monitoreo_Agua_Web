
var expr = /^[a-zA-Z0-9_\.\-]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$/;
var expr2 = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}$/;


$('#registro').submit(function(e) { 
    
    
    var correo = $("#email").val();
    var passw = $("#password").val();
    var repass = $("#password2").val();
    
    if(correo == "" || !expr.test(correo)){
        alert('Correo mal escrito.');
        return false;
    }
    else{
        if(passw != repass && !expr2.test(passw)){
            alert('La contraseña debe de contener al menos 6 caracteres, incluyendo letras minúsculas, mayúsculas y números.');
            return false;
        }
    }       
    
    e.preventDefault(); 
});

