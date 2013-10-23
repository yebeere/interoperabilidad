<!--<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    </head>
    <body>
        <div></div>
    </body>
</html>-->

<?php
require_once 'Zend/Loader.php';
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of YoutubeClient
 *
 * @author gurzaf
 */
class YoutubeClient {
    
    public $_yt;
    private $_commentFeed;
    
    

    function __construct($httpClient) {
        $applicationId='miyouapi';
        $clientId='1036172153344-vgslatsd0hog7ejksjkje2ecti276rd0.apps.googleusercontent.com';
        $developerKey=     'AIzaSyBbRTTIq0Bkx-jAyJcK9mULXZdE1VLsuKg';
        $this->_yt= new Zend_Gdata_YouTube($httpClient, $applicationId, $clientId, $developerKey);

        //$this->_yt = new Zend_Gdata_YouTube($httpClient);
    }

    public function printVideoEntry($videoEntry, $tabs = "")
    {
      // the videoEntry object contains many helper functions that access the underlying mediaGroup object
      $resulta = $tabs . "\tVideos: " . $videoEntry->getVideoTitle() . "\n";
      $resulta .= $tabs . "\tDescripcion: " . $videoEntry->getVideoDescription() . "\n";
      $resulta .= $tabs . "\tURL en Youtube: " . $videoEntry->getVideoWatchPageUrl() . "\n";
      $resulta .= $tabs . "\tDuracion: " . $videoEntry->getVideoDuration() . "\n";
      $resulta .= $tabs . "\tContador de Vistas: " .$videoEntry->getVideoViewCount() . "</p>\n";
      

      $videoThumbnails = $videoEntry->getVideoThumbnails();

      if (count($videoThumbnails)>=0){
            $videoThumbnail = @$videoThumbnails[0];
      //      echo 'alto ' .$videoThumbnail["height"] . '<br/>';
      //      echo 'ancho '.$videoThumbnail["width"]. '<br/>';
      //      $alto=$videoThumbnail["height"];
      //      $ancho=$videoThumbnail["width"];
            $alto='180';
            $ancho='300';
            $resulta .= $tabs . "\t<a href=\"?video=".$videoEntry->getVideoId()."\">";
            $resulta .= $tabs . "\t\t<img src=\"" . $videoThumbnail["url"]."\"";
            $resulta .= " height=\"" . $alto."\"";
            $resulta .= " width=\"" . $ancho ."\" />";
            $resulta .= "</a>\n";
      }
      return $resulta;
    }

    public function printVideoFeed($videoFeed, $displayTitle = null)
    {
      $count = 1;
      if ($displayTitle === null) {
   $displayTitle = '';//$videoFeed->title->text;
      }
      $result = '<h2>' . $displayTitle . "</h2>\n";
      $result .= "<pre>\n";
      foreach ($videoFeed as $videoEntry) {
        $result .= '<h3>Entrada # ' . $count . "</h3>\n";
        $result .= $this->printVideoEntry($videoEntry);
        $result .= "\n";
        $count++;
      }
      $result .= "</pre>\n";
      return $result;
    }

    public function getUserUploads($userName)
    {
        if($userName==null) $userName = "default";
        return $this->printVideoFeed($this->_yt->getuserUploads($userName));
    }



 

        public function addcoment($coment,$video_id)
        {
            
            $newComment = $this->_yt->newCommentEntry();
            $newComment->content =  $this->_yt->newContent()->setText($coment);             //$service->newContent()->setText($coment);
            $comment_post_url = 'http://gdata.youtube.com/feeds/videos/'. $video_id .'/comments';
            $updatedVideoEntry = $this->_yt->insertEntry($newComment, $comment_post_url);
           // header("Location: ".$_SERVER["PHP_SELF"]."?video=".$video_id);
        }

        function getAndPrintCommentFeed($videoId)
        {
            $this->_commentFeed = $this->_yt->getVideoCommentFeed($videoId);
             return $this->printCommentFeed($this->_commentFeed);
        }

        function printCommentFeed($commentFeed, $displayTitle = null)
        {
          $count = 1;
          $html="";
          if ($displayTitle === null) {
            
                          if (isset($commentFeed->title->text))  {
                  $displayTitle = $commentFeed->title->text;
              } else {
                  $displayTitle='';
              }
              
           
          }

          $html.='<h2>' . $displayTitle . "</h2>\n";
          $html.="<pre>\n";

          foreach ($commentFeed as $commentEntry) {
            $html.= 'Entrada # ' . $count . "\n";
            $html.=$this->printCommentEntry($commentEntry);
            $html.= "\n";
            $count++;
          }
          $html.= "</pre>\n";
          return $html;
        }

        function printCommentEntry($commentEntry)
        {
           $html="";
           
          $html.= 'Comentario: ' . $commentEntry->title->text . "\n";
          $html.= "\tTexto: " . $commentEntry->content->text . "\n";
          $html.= "\tAutor: ". $commentEntry->author[0]->name->text. "\n";

          return $html;
        }

        

  
    
 

      
     public function subirVideo(){

//http://ixavi.com/blog/2009/08/24/subir-videos-a-youtube-con-zend-gdata/
//
//         1) Requisitos previos
//         2) Identificación del usuario
//         3) Subir el vídeo
         
// Llegados a este punto, la subida del vídeo es la parte más sencilla de todo el proceso. Sólo hace falta seguir al pie de la letra las indicaciones existentes tanto en la documentación del Zend Framework como en la página de la API de YouTube destinada a este propósito para que la operación llegue a buen puerto.

//A continuación se detalla el código necesario para la inserción del video a YouTube:
$yt = new Zend_Gdata_YouTube($ytClient);
$myVideoEntry = new Zend_Gdata_YouTube_VideoEntry();
$filesource = new Zend_Gdata_App_MediaFileSource('Alfonsina.mp4');
$filesource->setContentType('video/mp4');
$filesource->setSlug('Alfonsina.mp4');

$myVideoEntry->setMediaSource($filesource);
$myVideoEntry->setVideoTitle('Prueba de subida a YT');
$myVideoEntry->setVideoDescription('Una descripción');
$myVideoEntry->setVideoCategory('Music'); //La categoría debe ser una categoría válida en YouTube
$myVideoEntry->SetVideoTags('mytest, sample'); //Tags
$myVideoEntry->setVideoDeveloperTags(array('solutions', 'iceberg')); //Tags como desarrollador

//URI estática para realizar las subidas
$uploadUrl = 'http://uploads.gdata.youtube.com/feeds/api/users/default/uploads';

//Bloque try-catch para subir el vídeo
try {
$newEntry = $yt->insertEntry($myVideoEntry, $uploadUrl, 'Zend_Gdata_YouTube_VideoEntry');
} catch (Zend_Gdata_App_HttpException $httpException) {
echo $httpException->getRawResponseBody();
} catch (Zend_Gdata_App_Exception $e) {
echo $e->getMessage();
}

     }
}
?>
