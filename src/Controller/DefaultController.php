<?php

namespace BlogWriter\Controller;

use Silex\Application;

class DefaultController {

    public function menuAction(Application $app)
    {
        $episodes = $app['dao.episode']->findAll();

        return $app['twig']->render('partials/menu.html.twig', compact('episodes'));
    }

    public function getEpisodesReadAction(Application $app)
    {
        $episodesRead = [];

        if ($app['user']) $episodesRead = $app['dao.episode']->findRead($app['user']);

        return $episodesRead;
    }
}