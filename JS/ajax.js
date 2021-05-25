$(document).ready(function() {
    $("#barra_busqueda_principal").keyup(hacerPeticionAjax);
  });
  
  function hacerPeticionAjax() {
      busqueda = $("#barra_busqueda_principal").val();
      
      $.ajax({
        data: {busqueda},
        url: 'ajax.php',
        type: 'get',
        success: function(respuesta) {
            procesaRespuestaAjax(respuesta);
        }
      });
  }
  
  function procesaRespuestaAjax(respuesta) {
    if (respuesta.length == 0)
        $("#resultados").hide();

    else{
        $("#resultados").show();
        res = "";
        respuesta.forEach(element => {
            res += (element + "<br>");
        });
        $("#resultados").html(res);
    }
  }
  