<?php

namespace BlogWriter\Controller;

use Silex\Application;

class HomeController {

    public function indexAction(Application $app)
    {
        $episodes = $app['dao.episode']->findAll();

        return $app['twig']->render('index.html.twig', array('episodes' => $episodes));
    }
}