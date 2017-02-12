<?php

namespace BlogWriter\Controller;

use BlogWriter\Domain\Comment;
use BlogWriter\Form\Type\CommentType;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class EpisodeController {

    public function indexAction($id, Request $request, Application $app)
    {
        $episode = $app['dao.episode']->find($id);
        $commentFormView = null;

        if ($app['security.authorization_checker']->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            $comment = new Comment();
            $comment->setEpisode($episode);
            $user = $app['user'];
            $comment->setAuthor($user);
            $commentForm = $app['form.factory']->create(CommentType::class, $comment);
            $commentForm->handleRequest($request);
            $commentFormView = $commentForm->createView();

            if ($commentForm->isSubmitted() && $commentForm->isValid())
            {
                $app['dao.comment']->save($comment);
                $app['session']->getFlashBag()->add('success', 'Votre commentaire a été ajouté avec succès.');
            }
        }

        $comments = $app['dao.comment']->findAllByEpisode($id);

        return $app['twig']->render('episode.html.twig', array(
            'episode'     => $episode,
            'comments'    => $comments,
            'commentForm' => $commentFormView
        ));
    }
}