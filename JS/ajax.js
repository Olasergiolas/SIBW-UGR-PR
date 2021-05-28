$(document).ready(function() {
    $("#barra_busqueda_principal").keyup(hacerPeticionAjax);
});
  
function hacerPeticionAjax() {
    busqueda = $("#barra_busqueda_principal").val();
    
    $.ajax({
    data: {busqueda:busqueda},
    url: 'ajax.php',
    method: 'POST',
    success: procesaRespuestaAjax
    });
}

function procesaRespuestaAjax(respuesta) {   

$("#resultados").html(respuesta);
if ($("#resultados").text().trim().length > 0 )
    $("#resultados").show();

else
    $("#resultados").hide();
}

function mostrarBusqueda(){
    $("#barra_busqueda_principal").toggle();
    $("#resultados").toggle();
}
