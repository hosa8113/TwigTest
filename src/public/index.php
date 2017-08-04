<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';

$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;

$config['db']['host'] = "localhost";
$config['db']['user'] = "user";
$config['db']['pass'] = "password";
$config['db']['dbname'] = "exampleapp";

$app = new  \Slim\App(["settings" => $config]);
//Let's configure it.
// Fetch DI Container
$container = $app->getContainer();

// Register Twig View helper and configure it
$container['view'] = function ($c) {
    //You can change this as you want
    $view = new \Slim\Views\Twig('templates', [
        'cache' => false, //or specify a cache directory
        'debug' => true //FOR TWIG DEBUG purposes
    ]);

    // Instantiate and add Slim specific extension
    $view->addExtension(new Slim\Views\TwigExtension(
        $c['router'],
        $c['request']->getUri()));

    //FOR TWIG DEBUG purposes
    $view->addExtension(new \Twig_Extension_Debug());
    return $view;
};


//Let's use it!
$app->get('/hello[/[{name}]]', function ($req, $res, $args) {

    $twigArgs = array();
    $twigArgs['month'] = date('m', time());

    if (!empty($args['name'])) {
        $twigArgs['name'] = $args['name'];
    } else {
        $twigArgs['name'] = '';
    }
    return $this->view->render($res, "hello.twig", $twigArgs);
});

$app->get('/bootstrap', function ($req, $res, $args) {
    $twigArgs = array();
    return $this->view->render($res, "bootstrap.twig", $twigArgs);
});

$app->get('/simple', function ($req, $res, $args) {
    $twigArgs = array();
    return $this->view->render($res, "simple.twig", $twigArgs);
});
$app->get('/simple_2', function ($req, $res, $args) {
    $twigArgs = array();
    return $this->view->render($res, "simple_2.twig", $twigArgs);
});

$app->run();

