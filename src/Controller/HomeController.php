<?php

namespace BlogWriter\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use BlogWriter\Domain\Comment;
use BlogWriter\Form\Type\CommentType;

class HomeController {

    public function indexAction(Application $app)
    {
        $episodes = $app['dao.episode']->findAll();

        return $app['twig']->render('index.html.twig', array('episodes' => $episodes));
    }

    public function episodeAction($id, Request $request, Application $app)
    {
        $episode = $app['dao.episode']->find($id);
        $commentFormView = null;
        if ($app['security.authorization_checker']->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            // A user is fully authenticated : he can add comments
            $comment = new Comment();
            $comment->setEpisode($episode);
            $user = $app['user'];
            $comment->setAuthor($user);
            $commentForm = $app['form.factory']->create(CommentType::class, $comment);
            $commentForm->handleRequest($request);
            if ($commentForm->isSubmitted() && $commentForm->isValid())
            {
                $app['dao.comment']->save($comment);
                $app['session']->getFlashBag()->add('success', 'Votre commentaire a été ajouté avec succès.');
            }
            $commentFormView = $commentForm->createView();
        }
        $comments = $app['dao.comment']->findAllByEpisode($id);

        return $app['twig']->render('episode.html.twig', array(
            'episode'     => $episode,
            'comments'    => $comments,
            'commentForm' => $commentFormView));
    }

    public function loginAction(Request $request, Application $app)
    {
        return $app['twig']->render('login.html.twig', array(
            'error'         => $app['security.last_error']($request),
            'last_username' => $app['session']->get('_security.last_username'),
        ));
    }
}