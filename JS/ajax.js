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
    if (respuesta.length == 0)
        $("#resultados").hide();

    /*else{
        $("#resultados").show();
        res = "";
        respuesta.forEach(element => {
            res += (element + "<br>");
        });
        $("#resultados").html(respuesta);
    }*/

    else{
        $("#resultados").show();
        $("#resultados").html(respuesta);
    }
  }
  