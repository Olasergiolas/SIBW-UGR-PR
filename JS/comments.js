function openComments() {
    document.getElementById("comments_section").style.width = "450px";
}

function closeComments() {
    document.getElementById("comments_section").style.width = "0px";
}

function raise_fill_gaps_error(){
    document.getElementById("fill_gaps_warning").style.display = "flex";
}

function close_fill_gaps_error() {
    document.getElementById("fill_gaps_warning").style.display = "none";
}

function raise_wrong_mail_error(){
    document.getElementById("wrong_mail_warning").style.display = "flex";
}

function close_wrong_mail_error() {
    document.getElementById("wrong_mail_warning").style.display = "none";
}

function comprobarCampos() {
    var enviar = true;
    var campos = document.getElementsByClassName("campo");
    var mail = document.getElementById("fmail").value;

    var i;
    for (i=0; i < campos.length && enviar; i++){
        if (campos[i].value.length == 0){
            raise_fill_gaps_error();
            enviar = false;
        }
    }
    
    if (enviar){
        //const regex = new RegExp(".+@.+\\..+");
        //Regex obtenida del sourcecode de chromium
        regex = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        if (!regex.test(String(mail).toLowerCase())){
            raise_wrong_mail_error();
            enviar = false;
        }
    }

    if (enviar)
        addComentario();
    
}

function censurar(){
    var comment = document.getElementById("fcomment");
    var comment_lower = comment.value.toLowerCase();
    var banned_words = ["arch", "nintendo", "tonto"];
        
    banned_words.forEach(function(element){
        var censura = new Array(element.length + 1).join('*');
        var pos = comment_lower.indexOf(element);

        if (pos > -1){
            comment.value = comment.value.substring(0, pos) + 
                censura + comment.value.substring(pos + element.length, comment.value.length);
        }
    })
}

function addComentario(){
    var fecha = new Date();
    var username = document.getElementById("fname").value;
    var comment = document.getElementById("fcomment").value;

    var div = document.getElementsByClassName("comentario")[0];
    var new_msg = div.cloneNode(true);

    new_msg.querySelector(".username").innerHTML = username;
    new_msg.querySelector(".parrafo_comentario").innerHTML = comment;
    new_msg.querySelector(".fecha_comentario").innerHTML = fecha.getFullYear() + '-' +
        (parseInt(fecha.getMonth())+1) + '-' + fecha.getDate() + ' ' + fecha.getHours() + 
        ':' + fecha.getMinutes() + ':' + fecha.getSeconds();

    document.getElementById("bloque_comentarios").appendChild(new_msg);
}