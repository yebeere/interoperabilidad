<?php
//print_r($datos);exit();
$location = $datos->{'location'}->{'city'};
$temp_c = $datos->{'current_observation'}->{'temp_c'};
$icono = $datos->{'current_observation'}->{'icon_url'};
$clima = $datos->{'current_observation'}->{'weather'};
$sensaciontermica_c = $datos->{'current_observation'}->{'feelslike_c'};
$viento_kmh = $datos->{'current_observation'}->{'wind_kph'};
$viento_direccion = $datos->{'current_observation'}->{'wind_dir'};
$presion_mb = $datos->{'current_observation'}->{'pressure_mb'};

$data = urlencode("El clima en Neuquen es " . $clima . " y la temperatura es de " . $temp_c . " grados");
?>
<h1>Mira el google drive<a href="http://drive.google.com">link</a></h1>
<table cellpadding="0" cellspacing="1" id="Tabla1"  style="left:0px;top:0px;width:440px;text-align: center" >
    <tr>
        <th>Ahora</th>
        <th>Temperatura</th>
        <th>Viento</th>
        <th>Presión</th>
    </tr>
    <tr>
        <td>
            <img src="<?php echo "${icono}" ?>" width="44" height="44" alt="<?php echo "$clima" ?>"  />
            <br />
            <span><?php echo "${clima}" ?></span>
        </td>
        <td> 
            <div>  
                <big>
                    <span>                 
                        <span>

                            <?php echo "${temp_c}" ?>

                        </span>
                        &nbsp;&#176; C
                    </span>
                </big>

                <div>
                    <small>
                        <br />
                        <span>Sensación térmica
                            <br />
                            <span>
                                <?php echo "${sensaciontermica_c}" ?>
                            </span>
                            &nbsp;&#176; C
                        </span>
                    </small>
                </div>
            </div>
        </td>
        <td>
            <div>
                <span>
                    <span>
                        <?php echo "${viento_kmh}" ?>

                    </span> 
                    km/h
                </span>
                <br />
                Dirección:
                <?php echo "${viento_direccion}" ?>
            </div>
        </td>
        <td>
            <?php echo "${presion_mb}" ?> 
            <span> mbares</span> 
        </td>
    </tr>
</table>
<audio controls="controls">
    <source src="<?php echo Yii::App()->baseUrl.'/mp3/'.$archivo;?>" type="audio/mpeg" />
    Su navegador no soporta elementos de audio
</audio>

<a href="<?php echo $urlcorta; ?>"><?php echo $urlcorta; ?></a>
<br /><br />
<!--mapa-->

<!--videos-->

<!--lista de twits-->
<div>
    <?php
    foreach ($tweets as $valor) {
        echo '<p>' . $valor->user->name . " " . $valor->created_at . " " . $valor->text . '</p>';
    }
    ?>
</div>

<?php
 
    $count = 1;

$resulta = "<pre>\n";
if (isset($videoFeed)) {
    foreach ($videoFeed as $videoEntry) {
        $resulta = '<h3>Entrada # ' . $count . "</h3><br/>";
        $resulta .= "\tVideos: " . $videoEntry->getVideoTitle() . "<br/>";
        $resulta .= "\tDescripcion: " . $videoEntry->getVideoDescription() . "<br/>";
        $resulta .= "\tURL en Youtube: " . $videoEntry->getVideoWatchPageUrl() . "<br/>";
        $resulta .= "\tDuracion: " . $videoEntry->getVideoDuration() . "\n";
        $resulta .= "\tContador de Vistas: " . $videoEntry->getVideoViewCount() . "<br/>";
        echo "\t<a href=" . $videoEntry->getVideoWatchPageUrl() . "\">";
        $bsinImagen = FALSE;
        $videoThumbnails = $videoEntry->getVideoThumbnails();

        if (!isset($videoThumbnails[0])) {
            $bsinImagen = true;
        } else {
            $videoThumbnail = $videoThumbnails[0];
        }
        $alto = '240';
        $ancho = '360';

        if (!$bsinImagen) {

            echo "\t\t<img src=\"" . $videoThumbnail["url"] . "\"";
            echo " align='center'";
            echo " height=\"" . $alto . "\"";
            echo " width=\"" . $ancho . "\" />";
            echo "</a><br/>";
        }
        $count++;

        echo $resulta;
    }
    $resulta = "</pre>";
    echo $resulta;
}