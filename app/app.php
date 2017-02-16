<?php

use Symfony\Component\Debug\ErrorHandler;
use Symfony\Component\Debug\ExceptionHandler;
use Symfony\Component\HttpFoundation\Request;

// Register global error and exception handlers
ErrorHandler::register();
ExceptionHandler::register();

// Register JSON data decoder for JSON requests
$app->before(function (Request $request) {
    if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
        $data = json_decode($request->getContent(), true);
        $request->request->replace(is_array($data) ? $data : array());
    }
});

// Register service providers.
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__ . '/../views',
));

$app['twig'] = $app->extend('twig', function (Twig_Environment $twig)
{
    $twig->addExtension(new Twig_Extensions_Extension_Text());

    return $twig;
});
$app->register(new Silex\Provider\ValidatorServiceProvider());
$app->register(new Silex\Provider\DoctrineServiceProvider());
$app->register(new Silex\Provider\AssetServiceProvider(), array(
    'assets.version' => 'v1'
));
$app->register(new Silex\Provider\HttpFragmentServiceProvider());
$app->register(new Silex\Provider\SessionServiceProvider());
$app->register(new Silex\Provider\SecurityServiceProvider(), array(
    'security.firewalls'      => array(
        'secured' => array(
            'pattern'   => '^/',
            'anonymous' => true,
            'logout'    => true,
            'form'      => array('login_path' => '/login', 'check_path' => '/login_check'),
            'users'     => function () use ($app)
            {
                return new BlogWriter\DAO\UserDAO($app['db']);
            },
        ),
    ),
    'security.role_hierarchy' => array(
        'ROLE_ADMIN' => array('ROLE_USER'),
    ),
    'security.access_rules'   => array(
        array('^/admin', 'ROLE_ADMIN'),
        array('^/account', 'ROLE_USER'),
    ),
));

$app->register(new Silex\Provider\FormServiceProvider());
$app->register(new Silex\Provider\LocaleServiceProvider());
$app->register(new Silex\Provider\TranslationServiceProvider());

$app->register(new Silex\Provider\MonologServiceProvider(), array(
    'monolog.logfile' => __DIR__ . '/../var/logs/blogwriter.log',
    'monolog.name'    => 'BlogWriter',
    'monolog.level'   => $app['monolog.level']
));

$app['dao.user'] = function ($app)
{
    return new BlogWriter\DAO\UserDAO($app['db']);
};
$app['dao.episode'] = function ($app)
{
    return new BlogWriter\DAO\EpisodeDAO($app['db']);
};
$app['dao.comment'] = function ($app)
{
    $commentDAO = new BlogWriter\DAO\CommentDAO($app['db']);
    $commentDAO->setEpisodeDAO($app['dao.episode']);
    $commentDAO->setUserDAO($app['dao.user']);

    return $commentDAO;
};

$app['twig'] = $app->extend('twig', function (Twig_Environment $twig, $app)
{
    $episodesRead = (new BlogWriter\Controller\DefaultController())->getEpisodesReadAction($app);
    $twig->addGlobal('episodesRead', $episodesRead);

    return $twig;
});

$app->error(function (\Exception $e, Request $request, $code) use ($app)
{
    switch ($code)
    {
        case 403:
            $message = 'Accès refusé.';
            break;
        case 404:
            $message = 'La page n\'existe pas.';
            break;
        default:
            $message = 'Une erreur s\'est produite.';
    }

    return $app['twig']->render('error.html.twig', array('message' => $message));
});


if (isset($app['validator.validator_factory']))
{
    $app['validator.unique'] = function ($app)
    {
        $validator = new \BlogWriter\Constraint\UniqueValidator($app);

        return $validator;
    };
    $app['validator.validator_service_ids'] =
        isset($app['validator.validator_service_ids']) ? $app['validator.validator_service_ids'] : array();
    $app['validator.validator_service_ids'] = array_merge(
        $app['validator.validator_service_ids'],
        array('validator.unique' => 'validator.unique')
    );
}