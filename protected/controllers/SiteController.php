<?php

class SiteController extends Controller {

    /**
     * Declares class-based actions.
     */
    public function actions() {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ),
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }

    public function clima() {
        $json_string = file_get_contents("http://api.wunderground.com/api/facfde1f912d0a40/forecast/geolookup/conditions/lang:SP/q/Argentina/Neuquen.json");
        $datosClima = json_decode($json_string);
        return $datosClima;
    }

    /**
     * Reducir URL
     */
    public function comprimirUrl($link) {
        session_start();

        yii::import('ext.google-api-php-client.src.*');

        $client = new Google_Client();
        $client->setClientId('1036172153344-0fkriteltr75kiu1t1d4q6garmeoskpu.apps.googleusercontent.com');
        $client->setClientSecret('E2tXm0HnwJtuSfxg7hufGsl8');
        $client->setRedirectUri('http://localhost/yii/interop');
        $client->setDeveloperKey('AIzaSyBbRTTIq0Bkx- jAyJcK9mULXZdE1VLsuKg');
        $service = new Google_UrlshortenerService($client);
//exit('2');
        if (isset($_REQUEST['logout'])) {
            unset($_SESSION['access_token']);
        }

        if (isset($_GET['code'])) {
            $client->authenticate();
            $_SESSION['access_token'] = $client->getAccessToken();
            $redirect = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
            header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
        }
//exit('1');
        if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
            $client->setAccessToken($_SESSION['access_token']);
        } else {
            $authUrl = $client->createAuthUrl();
            exit("<a class='login' href='$authUrl'>Connect Me!</a>");
        }

        if ($client->getAccessToken() && isset($link)) {
// Start to make API requests.
            $url = new Google_Url();
            $url->longUrl = $link;
            $short = $service->url->insert($url);
            $_SESSION['access_token'] = $client->getAccessToken();
        }

        return $short['id'];
    }

    /**
     * Escribir en twitter 
     */
    public function twitter($url) {

        $return = false;

        if ($url != '') {

            $consumerKey = 'BFu9FfOn7MPO7ZHWPhqJiw';
            $consumerSecret = 'arNjmxvSqDJRH8XVQZhHwBkbZZ10xYkJ7orecERwMYg';
            $oAuthToken = '1919784024-OsEfg8Les91W3tVeUxJsPTwkg8wuAo6rv1yWzcO';
            $oAuthSecret = 'zorTCldkwXIW8v6FbZls9239pxgvI2aSin1FHfF7I';

// librería para usar la API OAuth
            Yii::import('ext.twitteroauth.*');
//require_once '../../extensions/twitter/twitteroauth/twitteroauth.php';

            $tweet = new TwitterOAuth($consumerKey, $consumerSecret, $oAuthToken, $oAuthSecret);

# envio del tweet

            $tweet->post('statuses/update', array('status' => $url . ' ' . date('h:i:s')));

            return $return = true;
        } else {
            return $return;
        }
    }

    /**
     * Funcion de twitter, muestra twits de un usuario dado
     */
    public function buscarTweets($user, $cant_tw) {
        $return = false;

        if (($user != '') && ($cant_tw != '')) {

            $consumerKey = 'BFu9FfOn7MPO7ZHWPhqJiw';
            $consumerSecret = 'arNjmxvSqDJRH8XVQZhHwBkbZZ10xYkJ7orecERwMYg';
            $oAuthToken = '1919784024-OsEfg8Les91W3tVeUxJsPTwkg8wuAo6rv1yWzcO';
            $oAuthSecret = 'zorTCldkwXIW8v6FbZls9239pxgvI2aSin1FHfF7I';

            // librería para usar la API OAuth
            Yii::import('ext.twitteroauth.*');
            //require_once '../../extensions/twitter/twitteroauth/twitteroauth.php';

            $tweet = new TwitterOAuth($consumerKey, $consumerSecret, $oAuthToken, $oAuthSecret);

            # envio del tweet
            //$tweet->post('statuses/update', array('status' => $url.' '.date('h:i:s')));
            $json = $tweet->get("https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=$user&count=$cant_tw");
            return $json;
        } else {
            return $return;
        }
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex() {
// renders the view file 'protected/views/site/index.php'
// using the default layout 'protected/views/layouts/main.php'
        $datos = $this->clima();
        $urlcorta = 'pepe';//$this->comprimirUrl('pedco.uncoma.edu.ar');
        /* escribir en twitter */
        /* escribir en drive */
        /* traer datos de twitter */
        $tweets=$this->buscarTweets('mattleblancmm', 20);
        /* traer datos de youtube */


        $this->render('index', array('datos' => $datos, 'urlcorta' => $urlcorta,'tweets'=>$tweets));
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError() {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    /**
     * Displays the contact page
     */
    public function actionContact() {
        $model = new ContactForm;
        if (isset($_POST['ContactForm'])) {
            $model->attributes = $_POST['ContactForm'];
            if ($model->validate()) {
                $name = '=?UTF-8?B?' . base64_encode($model->name) . '?=';
                $subject = '=?UTF-8?B?' . base64_encode($model->subject) . '?=';
                $headers = "From: $name <{$model->email}>\r\n" .
                        "Reply-To: {$model->email}\r\n" .
                        "MIME-Version: 1.0\r\n" .
                        "Content-type: text/plain; charset=UTF-8";

                mail(Yii::app()->params['adminEmail'], $subject, $model->body, $headers);
                Yii::app()->user->setFlash('contact', 'Thank you for contacting us. We will respond to you as soon as possible.');
                $this->refresh();
            }
        }
        $this->render('contact', array('model' => $model));
    }

    /**
     * Displays the login page
     */
    public function actionLogin() {
        $model = new LoginForm;

// if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

// collect user input data
        if (isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];
// validate user input and redirect to the previous page if valid
            if ($model->validate() && $model->login())
                $this->redirect(Yii::app()->user->returnUrl);
        }
// display the login form
        $this->render('login', array('model' => $model));
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout() {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }

}