<h1>__php_incomplete_class</h1>

Un caso que me paso. <strong>Necesitaba consumir DESDE AJAX POR GET un JSON que me de como respuesta un SCRIPT externo que cree en WORDPRESS</strong>

<h2> Necesitaba consumir unos datos desde la bd de wordpress (serializar una información).</h2>

- SELECT * FROM `p_options` WHERE option_name = 'fwdsuvp_data'

Pero al momento de obtener los datos y aplicar unserialize tenia el error de __php_incomplete_class.
Como era un script externo, <strong> el error era porque no encontraba la clase que estaba dentro de la informacion serializada en esa tabla.</strong>,

entonces pense en usar las funciones que ya me da wordpress.
<h2>1 -CREAMOS EL SCRIPT EN LA RAIZ DE WORDPRESS</h2>
<strong>Ahi dejo el script que cree para lo que yo necesitaba. </strong>

ruta donde esta instalado mi wordpress/script.php
ejemplo : public_html/script.php
<p>
<strong>
define('WP_USE_THEMES', false);<br>
require('./wp-blog-header.php');<br>
</strong>
Estos dos parametros me permite hacer uso de las funciones 
 - el primero desactiva el tema para este script.
</p>

<h2>2- USO DE FUNCIONES WORPRESS : </h2>
Use maybe_unserialize() : es unserilze de wordpress, pero esto me seguia dando el error.

-Entonces investigando, observe que exist un funcion general que si le pasas el parametro que deseas
trae la informacion. 

<stron><h3>get_option('fwdsuvp_data')</h3></strong> : es una funcion global que se usa para traer informacion de la tabla de <strong>"options"</strong>

con eso obtuve mi informacion convertida en un array y pude hacer uso de los datos que requeria.

<stron><h3>wp_send_json(array) </h3></strong>  : Envíe una respuesta JSON a una solicitud Ajax.

3 - DONDE COLOCAR SCRIPT PARA EVITAR ERROR DE CONSUMO AJAX (404)
     - /domains/app.nutricionplatinum.es/public_html/wp-content/themes/twentynineteen/functions.php.
     
     Aqui colocamos el : <strong>requiere "script.php";</strong>
     
4- CODIGO AJAX GET : esto ya lo pueden adaptar segun a lo que necesiten consumir

<script>
    $(document).ready(function() {
        var idCliex = 6498;
        $.get(
            "http://localhost/nutri/exportar.php/exportar.php", {
                idCliente: idCliex
            },
            function(data, status) {
                if (status == 'success') {
                    console.log(data);
                }
            },
            "json"
        );

    });
</script>
