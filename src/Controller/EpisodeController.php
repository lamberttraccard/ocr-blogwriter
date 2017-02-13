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
        $commentForm = null;
        $episodeNext = $app['dao.episode']->findNext($id);
        $episodePrevious = $app['dao.episode']->findPrevious($id);

        if ($app['security.authorization_checker']->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            $comment = new Comment();
            $comment->setEpisode($episode);
            $user = $app['user'];
            $comment->setAuthor($user);
            $form = $app['form.factory']->create(CommentType::class, $comment);
            $form->handleRequest($request);
            $commentForm = $form->createView();

            if ($form->isSubmitted() && $form->isValid())
            {
                $app['dao.comment']->save($comment);
                $app['session']->getFlashBag()->add('success', 'Votre commentaire a été ajouté avec succès.');
            }
        }

        $comments = $app['dao.comment']->findAllByEpisode($id);

        return $app['twig']->render('episode.html.twig', compact(
            'episode', 'comments', 'commentForm', 'episodeNext', 'episodePrevious'
        ));
    }

    public function readAction($id, Application $app, Request $request)
    {
        if ($app['security.authorization_checker']->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            $episode = $app['dao.episode']->find($id);
            $user = $app['user'];
            $app['dao.episode']->markAsRead($episode, $user);

            $app['session']->getFlashBag()->add('success', 'L\'épisode a été marqué comme lu.');

            return $app->redirect($request->server->get('HTTP_REFERER'));

        }

        throw new \Exception("You must be logged in to mark an episode as read");
    }

    public function unreadAction($id, Application $app, Request $request)
    {
        if ($app['security.authorization_checker']->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            $episode = $app['dao.episode']->find($id);
            $user = $app['user'];
            $app['dao.episode']->markAsUnread($episode, $user);

            $app['session']->getFlashBag()->add('success', 'L\'épisode a été marqué comme non lu.');

            return $app->redirect($request->server->get('HTTP_REFERER'));
        }

        throw new \Exception("You must be logged in to mark an episode as unread");
    }
}