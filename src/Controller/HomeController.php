<?php

namespace BlogWriter\Controller;

use BlogWriter\Domain\User;
use Silex\Application;

class HomeController {

    public function indexAction(Application $app)
    {
        $user = $app['dao.user']->findOneby(array('id', 1));

        return $app['twig']->render('index.html.twig', compact('user'));
    }
}