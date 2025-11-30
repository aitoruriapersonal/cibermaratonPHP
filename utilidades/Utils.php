<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\util\Utils.php

class Utils
{
    // Constantes para prefijos de nick
    public const NICK_EHU = '23EHU';
    public const NICK_UNI = '23UNI';
    public const NICK_FED = '23FED';

    /**
     * Elimina tildes y reemplaza ñ/Ñ por n/N en una cadena.
     */
    public static function quitarTildesYN(string $cadena): string
    {
        $originales =    ['á','é','í','ó','ú','Á','É','Í','Ó','Ú','ñ','Ñ', 'ü', 'Ü'];
        $reemplazos =    ['a','e','i','o','u','A','E','I','O','U','n','N', 'u', 'U'];
        return str_replace($originales, $reemplazos, $cadena);
    }

    /**
     * Convierte un nombre compuesto a CamelCase, sin espacios, tildes ni ñ/Ñ.
     */
    public static function nombreToCamelCase(string $nombre): string
    {
        $nombre = self::quitarTildesYN($nombre);
        $palabras = preg_split('/\s+/', strtolower($nombre));
        $camel = '';
        foreach ($palabras as $palabra) {
            $camel .= ucfirst($palabra);
        }
        return $camel;
    }

    /**
     * Devuelve el prefijo de nick según el tipo.
     */
    public static function getNickPrefijo(string $tipo): string
    {
        $map = [
            'EHU' => self::NICK_EHU,
            'UNI' => self::NICK_UNI,
            'FED' => self::NICK_FED,
        ];
        $key = strtoupper($tipo);
        return $map[$key] ?? '23XXX';
    }

    public static function emailInscripcionChess($destino, $nick, $nombre){
	
	    $mensaje='<!DOCTYPE html>
        <html lang ="es">       
            <head>
                <meta charset="iso-8859-1" />
                <title>Inicio - Club Deportivo Universitario</title>
                <link type="image/x-icon" rel="shortcut icon" href="http://ajedrez.deporte-universitario.com/img/favicon.ico">
                
                <style>
                    @font-face {
                        font-family: segoe-print;
                        src: url("http://ajedrez.deporte-universitario.com/font/SegoePrint.ttf");
                    }
                    body{
                        /*font-family: segoe-print;*/
                        color: #004466;
                        width:100%;
                    }
                    header, footer{
                        background-color: #08c;
                        padding:25px;
                        min-height:100px;
                    }
                    section{
                        padding:50px;
                        background-color: #F0E68C;
                        font-size: large;
                    }
                </style>
            </head>
            <body>
                <header id="deporteUniversitarioHeader">
                    <img id="deporteUniversitario" src="http://ajedrez.deporte-universitario.com/img/clubDeportivoUniversitario.png"/>
                    <h1 style="float:right;"><b style="font-family: segoe-print;">Ciber</b>-MARATON DE AJEDREZ</h1>
                </header>
                <section>
                    <b style="font-size: 20px;">Enhorabuena '.$nombre.'!! Te has inscrito correctamente en el <b style="font-family: segoe-print;">Ciber</b>-Maraton de la EHU.</b>
                    
                    <br /><br /><b><u>Usuario:</u> '.$nick.'</b><br />
                    
                    <br /><br/>Para poder jugar las partidas debes registrarte en <a href="http://www.chess.com"> www.chess.com </a> con los datos de usuario enviados en este email.
                    
                    <br />Una vez entras en la aplicion veras tu <b>progesion actual, el historial de tus partidas en el cibermaraton y el formulario para introducir los resultados.</b>
                    
                    <br /><br />Mucha suerte y que disfrutes del cibermaraton!!!
                    
                    <br /><br />Coordinador del Cibermaraton de Ajedrez
                </section>
                <footer></footer>
            </body>
        </html>';
        
        // título
        $título = 'Inscripcion al Cibermaraton';
        
        // mensaje
        // Para enviar un correo HTML, debe establecerse la cabecera Content-type
        $cabeceras  = 'MIME-Version: 1.0' . "\r\n";
        $cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        
        // Cabeceras adicionales
        // $cabeceras .= 'Bcc: <cdu.bilbao@gmail.com>' . "\r\n";
        $cabeceras .= 'From: <info.ajedrez@deporteuniversitario.eu>' . "\r\n";
        //$cabeceras .= 'From: <ajedrez@deporte-universitario.com>' . "\r\n";
        $cabeceras .= 'Bcc: <info.ajedrez@deporteuniversitario.eu>' . "\r\n";
        
        // Enviarlo
        mail($destino, $título, $mensaje, $cabeceras);
    }


    public static function emailFinMaraton($destino, $nick, $creditosTotales){
        $mensaje='<!DOCTYPE html>
            <html lang ="es">       
                <head>
                    <meta charset="utf-8" />
                    <title>Inicio - Club Deportivo Universitario</title>
                    <link type="image/x-icon" rel="shortcut icon" href="http://ajedrez.deporte-universitario.com/img/favicon.ico">
                    
                    <style>
                        @font-face {
                            font-family: segoe-print;
                                src: url("http://ajedrez.deporte-universitario.com/font/SegoePrint.ttf");
                            }
                            body{
                                /*font-family: segoe-print;*/
                                color: #004466;
                                width:100%;
                            }
                            header, footer{
                                background-color: #08c;
                                padding:25px;
                                min-height:100px;
                            }
                            section{
                                padding:50px;
                                background-color: #F0E68C;
                                font-size: 40px;
                            }
                        </style>
                    </head>
                    <body>
                        <header id="deporteUniversitarioHeader">
                            <img id="deporteUniversitario" src="http://ajedrez.deporte-universitario.com/img/clubDeportivoUniversitario.png"/>
                            <h1 style="float:right;"><b style="font-family: segoe-print;">Ciber</b>-MARATON DE AJEDREZ</h1>
                        </header>
                        <section style="font-size: 50px;">
                            <b style="font-size: 25px;">Enhorabuena!! Has finalizado correctamente la parte <b style="font-family: segoe-print;">Ciber</b> del Maraton de Ajedrez de la EHU.</b>
                            <br /><br />Teniendo en cuenta tu pretensi&oacute;n de esta actividad deportiva te sirva para la obtenci&oacute;n de cr&eacute;ditos de libre elecci&oacute;n, cuando termine la fase presencial
                            presencial <b><u>propondremos que se te otorguen <b style="background-color:#2EFEF7;">'.$creditosTotales.' cr&eacute;ditos.</b></u></b>
                            <br />Recuerda que para obtener m&aacute;s de 1 cr&eacute;dito hay que asistir a la fase presencial y jugar una partida. <i>Bases Art.22 </i>
                            <br /><br />Tambi&eacute;n, te informamos de que por el mero hecho de haber terminado el ciber-marat&oacute;n el club organizador te obsequia con 
                            <b style="background-color:#2EFEF7;">un mes de clases de artes marciales</b>(siempre que no est&eacute;s practicando en la actualidad). <i>Bases Art.21</i>
                            <br /><br />
                            Pretendemos que la fase presencial sea una fiesta, y asistas a la misma aunque solo sea para presenciar el Match que van a disputar los primeros clasificados.    
                            <br /><br />
                            Deseamos que esta actividad haya sido de tu agrado y que participes en futuras ediciones.
                            <br /><br />
                            Un cordial saludo,
                            <br />Coordinador de Ajedrez 
                            <br /><br />
                            <br /><b><u>TU OPINION ES IMPORTANTE: </u></b>
                            <br /><b>Por favor <b style="background-color:yellow;">danos tu opini&oacute;n respecto al funcionamiento de esta actividad</b>, y en qu&eacute; forma la podemos mejorar. 
                            <br />Responde a este email dando tu valoraci&oacute;n y tus sugerencias. 
                            <br /><br />
                            Muchas Gracias!!!</b>
                        </section>		
                        <footer></footer>
                    </body>
                </html>';
        // título
        $título = 'Usuario '.$nick.' has completado el Ciber-maraton';
        
        // mensaje
                // Para enviar un correo HTML, debe establecerse la cabecera Content-type
        $cabeceras  = 'MIME-Version: 1.0' . "\r\n";
        $cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        
        // Cabeceras adicionales
        // $cabeceras .= 'Bcc: <cdu.bilbao@gmail.com>' . "\r\n";
        $cabeceras .= 'From: <info.ajedrez@deporteuniversitario.eu>' . "\r\n";
        $cabeceras .= 'Bcc: <info.ajedrez@deporteuniversitario.eu>' . "\r\n";
        //$cabeceras .= 'To: <'.$destino.'>' . "\r\n";
        
        // Enviarlo
        mail($destino, $título, $mensaje, $cabeceras);
        
    }
}