function cambiarFormulario(tipo){
    if (tipo == 'registro'){
        document.getElementById("panel_login").style.display = "none";
        document.getElementById("panel_registro").style.display = "flex";
    }

    else{
        document.getElementById("panel_login").style.display = "flex";
        document.getElementById("panel_registro").style.display = "none";
    }
}