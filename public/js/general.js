var path_base = null;

function controlFile(objeto)
{

    var idBtn  = "close-preview" + (new Date().getTime());
    // Create the close button
    var closebtn = $('<button/>', {
        type:"button",
        text: 'x',
        id: idBtn,
        style: 'font-size: initial;',
    });


    closebtn.attr("class","close pull-right");
    // Set the popover default content
    /*$(objeto).popover({
        trigger:'manual',
        html:true,
        title: "<strong>Vista previa</strong>"+$(closebtn)[0].outerHTML,
        content: "No hay imagen",
        placement:'auto'
    }); */
    // Clear event
    $(objeto + ' .image-preview-clear').click(function(){
        $(objeto).attr("data-content","").popover('hide');
        $(objeto + ' .image-preview-filename').val("");
        $(objeto + ' .image-preview-clear').hide();
        $(objeto + ' .image-preview-input input:file').val("");
        //$(objeto + ' .image-preview-input-title').text("Abrir"); 
        $(objeto + " .image-preview-input input:hidden").val("");
    }); 
    // Create the preview image
    $(objeto + " .image-preview-input input:file").change(function (){     
        /*var img = $('<img/>', {
            id: 'dynamic',
            width:250,
            height:200
        }); */
        var file = this.files[0]; 
        var reader = new FileReader();
        // Set preview image into the popover data-content
        reader.onload = function (e) {
            //$(objeto + " .image-preview-input-title").text("Cambiar");
            $(objeto + " .image-preview-clear").show();
            $(objeto + " .image-preview-filename").val(file.name);       
            $(objeto + " .image-preview-input input:hidden").val(e.target.result);

            $(objeto + " .image-nombre").val(file.name);
        }        
        reader.readAsDataURL(file);
    });

    //Evento cerrar
    $(document).on('click', "#" + idBtn, function(){ 
        $( objeto).popover('hide');
        // Hover befor close the preview
        $( objeto).hover(
            function () {
               $( objeto).popover('show');
            }, 
             function () {
               $( objeto).popover('hide');
            }
    );    
});




}

function validarCamposNumerico(evt) {
    var charCode = (evt.which) ? evt.which : evt.keyCode
    if ((charCode >= 48 && charCode <= 57) || charCode == 8) {
        return true;
    }
    else {
        return false;
    }
}

function abrirModal(objeto)
{
    $(objeto).modal("show");
}

function cerrarModal(objeto)
{
    $(objeto).modal("hide");
}

function mostrarImagen(objeto,imgBase64)
{
    //$(objeto + " .image-preview-input-title").text("Cambiar");
    $(objeto + " .image-preview-clear").show();
    $(objeto + " .image-preview-filename").val("imagen");

    
    var img = $('<img/>', {
            id: 'dynamic',
            width:250,
            height:200
        });  
    img.attr('src', imgBase64);
    $(objeto).attr("data-content",$(img)[0].outerHTML).popover("show");
    $(objeto + " .image-preview-input input:hidden").val(imgBase64);


}

Number.prototype.format = function(){
   return this.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
};
String.prototype.replaceAll = function(search, replacement) {
    var target = this;
    return target.replace(new RegExp(search, 'g'), replacement);
};
//Propiedad Objeto Date agregar días.
Date.prototype.addDays = function (days) {
    var dat = new Date(this.valueOf());
    dat.setDate(dat.getDate() + days);
    return dat;
}

//Propiedad Objeto Date Format fecha yyyy/MM/dd
Date.prototype.toFormatAMD = function () {
    var dat = new Date(this.valueOf());
    return dat.toJSON().slice(0, 10).replace(/-/g, "/");
}
//Propiedad Objeto Date Format fecha dd/MM/yyyy
Date.prototype.toFormatDMA = function () {
    var dat = new Date(this.valueOf());
    var arrFecha = dat.toJSON().slice(0, 10).split('-');
    var fecha = (arrFecha[2] + "/" + arrFecha[1] + "/" + arrFecha[0]);

    return fecha;
}

//DatePicker
function campoFecha() {
    $('.datepicker').datepicker({
        format: 'dd/mm/yyyy',
        autoclose: true,
        todayHighlight: true,
        language: 'es'
    });
}

function campoFechaDeshabilitadaHoy() {
    $('.datepicker').datepicker({
        format: 'dd/mm/yyyy',
        autoclose: true,
        language: 'es',
        startDate: new Date()
    });
}

function fechaActualRestarDias(dias) {
    fecha = new Date();
    fecha.setDate(fecha.getDate() + (dias));

    $('.datepicker').datepicker({
        format: 'dd/mm/yyyy',
        autoclose: true,
        todayHighlight: true,
        language: 'es',
        startDate: fecha
    });
}

function cargarCalificacion(calif) {
    $( "#divCalificacionFinal label" ).each(function( index ) {
        if((index+1) <= calif) {
            $( this ).addClass('starOrange');
        } else {
            $( this ).removeClass('starOrange');
        }
    });
}

function limpiarCalificacion() {
    $('.rdEstrella').prop('checked', false);
}

function filee(data, nombre, mime) {
    download(data, nombre, 'text/plain')
            .then(function(file){
                saveFile(file, nombre);
            })
}

function saveFile(blob, filename) {
    if (window.navigator.msSaveOrOpenBlob) {
        window.navigator.msSaveOrOpenBlob(blob, filename);
    } else {
        const a = document.createElement('a');
        document.body.appendChild(a);
        const url = window.URL.createObjectURL(blob);
        a.href = url;
        a.download = filename;
        a.click();
        /*setTimeout(() => {
        window.URL.revokeObjectURL(url);
        document.body.removeChild(a);
        }, 0)*/
    }
}

function download(url, filename, mimeType){
    return (fetch(url)
        .then(function(res){return res.arrayBuffer();})
        .then(function(buf){return new File([buf], filename, {type:mimeType});})
    );
}