<?php
require_once 'Zend/Loader.php';

/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AuthYoutube
 *
 * @author gurzaf
 */
class AuthYoutube {

    //Llave de desarrollador provista por google
   private $_developer_key = 'AIzaSyBbRTTIq0Bkx-jAyJcK9mULXZdE1VLsuKg';

//private $_developer_key = '';
    //Nombre del usuario Youtube
    private $_user;

    //Sitio al que es redirigido el navegador una vez que se autentifica
    private $_next;

    //Url del servicio con el que nos vamos a autentificar de google, en este caso Youtube
    private $_scope;

    //Indica que queremos cancelar el registro del token devuelto
    private $_secure;

    //Indica que queremos conservar el token como token de sesion
    private $_session;
    

    function __construct($_user) {
        $this->_user = $_user;
        Zend_Loader::loadClass('Zend_Gdata_YouTube');
        Zend_Loader::loadClass('Zend_Gdata_AuthSub');
        Zend_Loader::loadClass('Zend_Gdata_App_Exception');
        $this->_next = "http://".$_SERVER["HTTP_HOST"].$_SERVER["PHP_SELF"];
        $this->_scope = 'http://gdata.youtube.com';
        $this->_secure = false;
        $this->_session = true;
    }

    public function getAuthURL(){
        $url = Zend_Gdata_AuthSub::getAuthSubTokenUri($this->_next, $this->_scope, $this->_secure, $this->_session);
  
        return $url;
    }

    public function getSessionToken($getToken){
        return Zend_Gdata_AuthSub::getAuthSubSessionToken($getToken);
    }

    public function getYoutubeHttpClient(){
        $httpClient = Zend_Gdata_AuthSub::getHttpClient(Session::getToken());
        
        

        return $httpClient;
    }
    
    public function getNext(){
        $next = $this->_next;
        return $next;
    }

}
?>
