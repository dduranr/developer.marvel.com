// Elimina una etiqueta HTML
// Uso: <a data-iddelwrapper="identificador" onclick="eliminarTagHTML(this)">delete</a>
function eliminarTagHTML(link) {
    jQuery(document).ready(function() {
        let iddelwrapper = jQuery(link).data("iddelwrapper");
        jQuery('#'+iddelwrapper).remove();
    });
}

function eliminar(campo) {
    var url   = jQuery(campo).attr('data');
    var id    = jQuery(campo).attr('data2');
    var table = jQuery(campo).attr('data3');

    // Eliminar el evento click del botón "modalEliminar_si" antes de agregarlo nuevamente
    jQuery('#modal_eliminar_si').off('click');

    const elem = document.getElementById('modal_eliminar');
    const instance = M.Modal.init(elem, {dismissible: true});
    instance.open();
    jQuery('#modal_eliminar .nombre').html('ID: ' + id);

    jQuery('#modal_eliminar_si').on('click.close', function (e) {
        jQuery.ajax({
            url: url,
            data: {
                'id': id
            },
            type: 'POST',
            dataType: 'json',
            async: true,
            success: function (response) {
                showToastR(response.msg, response.msgtype, response.title);
                jQuery('#' + table).DataTable().ajax.reload();
                // if( response.bandera ){
                // }
            }
        });
    });
}

// TODO: poner nuevo param para que se pueda seleccionar la posición del alert: left, center, right
function showToastR(msg, type, title) {
    toastr.options = {
        showDuration: 300,
        hideDuration: 1000,
        timeOut: 0,
        extendedTimeOut: 1000,
        showEasing: 'swing',
        hideEasing: 'linear',
        showMethod: 'fadeIn',
        hideMethod: 'fadeOut',
        closeButton: true,
        newestOnTop: true,
        progressBar: false,
        rtl: false,
        positionClass: 'toast-top-center',
        preventDuplicates: true,
        onclick: null,
        escapeHtml: false,
    };
    if (type==='success') toastr.success(msg, title);
    if (type==='warning') toastr.warning(msg, title);
    if (type==='error')   toastr.error  (msg, title);
}


/**
*   Esta función crea la cookie
*
*   @author   David Durán
*   @param    Str. nombre. Nombre de la cookie
*   @param    Mix. valor. Valor de la cookie
*   @param    Str. caducidad. Número de días de duración de la cookie
*   @return   Void.
*/
function crearCookie(nombre,valor,caducidad) {
    let fecha = new Date();
    fecha.setDate(fecha.getDate() + parseInt(caducidad));
    let valor_cookie_seguro=escape(valor) + ((caducidad==null) ? "" : ("; expires="+fecha.toUTCString()));
    document.cookie=nombre + "=" + valor_cookie_seguro;
}


/**
*   Esta función establece los datos que irán a parar a la cookie y luego llama a la función para crear la cookie
*
*   @author   David Durán
*   @param    Str. nombre_cookie. Nombre de la cookie
*   @return   Void.
*/
function setDataToCookie(nombre_cookie) {
    let caducidad_cookie = "3"; // Días a partir de hoy
    let valor_cookie  = "true";
    crearCookie(nombre_cookie,valor_cookie,caducidad_cookie);
}


/**
*   Esta función se encarga de buscar la cookie cuyo nombre de le pasa por parámetro
*
*   @author   David Durán
*   @param    Str. nombre_cookie. Nombre de la cookie
*   @return   Bool. TRUE en caso que la cookie exista
*/
function leerCookie(nombre_cookie) {
    // Aislamos cada cookie partiendo la cadena que contiene todas las cookies ahí donde halla un ";".
    let lista = document.cookie.split(";");
    for (i in lista) {
        let busca = lista[i].search(nombre_cookie);
        // Si existe la cookie buscada, la variable "mi_cookie" guardará la cookie que buscamos: clave=valor
        if (busca > -1) {
            mi_cookie=lista[i];
            let signo_igual          = mi_cookie.indexOf("=");              // Buscamos la posición del signo igual dentro de la cookie
            let valor_cokie_guardada = mi_cookie.substring(signo_igual+1);  // Obtenemos toda la cadena hallada después del signo igual
            return true;
        }
        else {
            return false;
        }
    }
}




/**
*   Esta función recupera el nombre del navegador usado actualmente
*
*   @author   David Durán
*   @return   Str. El nombre del browser
*/
function getDataBrowser() {
    var ua=navigator.userAgent,tem,M=ua.match(/(opera|chrome|safari|firefox|msie|trident(?=\/))\/?\s*(\d+)/i) || [];
    if(/trident/i.test(M[1])){
        tem=/\brv[ :]+(\d+)/g.exec(ua) || [];
        return {name:'IE',version:(tem[1]||'')};
        }
    if(M[1]==='Chrome'){
        tem=ua.match(/\bOPR|Edge\/(\d+)/)
        if(tem!=null)   {return {name:'Opera', version:tem[1]};}
        }
    M=M[2]? [M[1], M[2]]: [navigator.appName, navigator.appVersion, '-?'];
    if((tem=ua.match(/version\/(\d+)/i))!=null) {M.splice(1,1,tem[1]);}
    return {
      name: M[0],
      version: M[1]
    };
 }



/**
*   Esta función recupera el nombre del navegador usado actualmente
*
*   @author   David Durán
*   @return   Str. El nombre del browser
*/
function getBrowserName(){
    let sBrowser, sUsrAg = navigator.userAgent;
    if(sUsrAg.indexOf("Chrome") > -1) {
        sBrowser = "Google Chrome";
    } else if (sUsrAg.indexOf("Safari") > -1) {
        sBrowser = "Apple Safari";
    } else if (sUsrAg.indexOf("Opera") > -1) {
        sBrowser = "Opera";
    } else if (sUsrAg.indexOf("Firefox") > -1) {
        sBrowser = "Mozilla Firefox";
    } else if (sUsrAg.indexOf("MSIE") > -1) {
        sBrowser = "Microsoft Internet Explorer";
    }
    return sBrowser;
}




/**
*   Esta función convierte a string el número de posición de la leyenda "FINALISTA" de la banda ganadora. Mas exactamente, convierte la posición elegida en el dashboard (0-5), a una clase CSS, la cual finalmente se encargará de posicionar la leyenda "FINALISTA" sobre la foto de la banda.
*
*   @author   David Durán
*   @param    Int. pos. El identificador numérico de la posición deseada de la leyenda
*   @return   Str. El nombre de la clase CSS que hace que la leyenda se posicione en el lugar correcto
*/
function convertNumPosAstr(pos){
    switch (pos) {
        case 0:
            return 'd-none';
            break;
        case 1:
            return 'finalistaLegend_topleft';
            break;
        case 2:
            return 'finalistaLegend_topright';
            break;
        case 3:
            return 'finalistaLegend_bottomleft';
            break;
        case 4:
            return 'finalistaLegend_bottomright';
            break;
        case 5:
            return 'finalistaLegend_center';
            break;
        default:
            return 'd-none';
            break;
      }
}

