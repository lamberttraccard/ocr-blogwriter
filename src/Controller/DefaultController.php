<?php

namespace BlogWriter\Controller;

use Silex\Application;

class DefaultController {

    public function menuAction(Application $app) {
        $episodes = $app['dao.episode']->findAll();

        return $app['twig']->render('partials/menu.html.twig', array(
            'episodes' => $episodes,
        ));
    }
}