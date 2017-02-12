<?php

namespace BlogWriter\Controller;

use Silex\Application;

class AccountController {

    public function indexAction(Application $app)
    {
        return $app['twig']->render('account.html.twig', array());
    }
}

