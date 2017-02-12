<?php

namespace BlogWriter\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use BlogWriter\Domain\Episode;
use BlogWriter\Domain\User;
use BlogWriter\Form\Type\EpisodeType;
use BlogWriter\Form\Type\CommentType;
use BlogWriter\Form\Type\UserType;

class AdminController {

    public function indexAction(Application $app)
    {
        $episodes = $app['dao.episode']->findAll();
        $comments = $app['dao.comment']->findAll();
        $users = $app['dao.user']->findAll();

        return $app['twig']->render('admin.html.twig', array(
            'episodes' => $episodes,
            'comments' => $comments,
            'users'    => $users
        ));
    }

    public function addEpisodeAction(Request $request, Application $app)
    {
        $episode = new Episode();
        $episodeForm = $app['form.factory']->create(EpisodeType::class, $episode);
        $episodeForm->handleRequest($request);

        if ($episodeForm->isSubmitted() && $episodeForm->isValid())
        {
            $app['dao.episode']->save($episode);
            $app['session']->getFlashBag()->add('success', 'L\'épisode a été ajouté avec succès.');

            return $app->redirect($app['url_generator']->generate('admin'));
        }

        return $app['twig']->render('episode_form.html.twig', array(
            'title'       => 'Nouvel épisode',
            'episodeForm' => $episodeForm->createView()
        ));
    }

    public function editEpisodeAction($id, Request $request, Application $app)
    {
        $episode = $app['dao.episode']->find($id);
        $episodeForm = $app['form.factory']->create(EpisodeType::class, $episode);
        $episodeForm->handleRequest($request);

        if ($episodeForm->isSubmitted() && $episodeForm->isValid())
        {
            $app['dao.episode']->save($episode);
            $app['session']->getFlashBag()->add('success', 'L\'épisode a été modifié avec succès.');

            return $app->redirect($app['url_generator']->generate('admin'));
        }

        return $app['twig']->render('episode_form.html.twig', array(
            'title'       => 'Modifier épisode',
            'episodeForm' => $episodeForm->createView()
        ));
    }

    public function deleteEpisodeAction($id, Application $app)
    {
        $app['dao.comment']->deleteAllByEpisode($id);
        $app['dao.episode']->delete($id);
        $app['session']->getFlashBag()->add('success', 'L\'épisode a été supprimé avec succès.');

        return $app->redirect($app['url_generator']->generate('admin'));
    }

    public function editCommentAction($id, Request $request, Application $app)
    {
        $comment = $app['dao.comment']->find($id);
        $commentForm = $app['form.factory']->create(CommentType::class, $comment);
        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid())
        {
            $app['dao.comment']->save($comment);
            $app['session']->getFlashBag()->add('success', 'Le commentaire a été modifié avec succès.');

            return $app->redirect($app['url_generator']->generate('admin'));
        }

        return $app['twig']->render('comment_form.html.twig', array(
            'title'       => 'Modifier commentaire',
            'commentForm' => $commentForm->createView()
        ));
    }

    public function deleteCommentAction($id, Application $app)
    {
        $app['dao.comment']->delete($id);
        $app['session']->getFlashBag()->add('success', 'Le commentaire a été supprimé avec succès.');

        return $app->redirect($app['url_generator']->generate('admin'));
    }

    public function addUserAction(Request $request, Application $app)
    {
        $user = new User();
        $userForm = $app['form.factory']->create(UserType::class, $user);
        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid())
        {
            $salt = substr(md5(time()), 0, 23);
            $user->setSalt($salt);
            $plainPassword = $user->getPassword();
            $encoder = $app['security.encoder.bcrypt'];
            $password = $encoder->encodePassword($plainPassword, $user->getSalt());
            $user->setPassword($password);
            $app['dao.user']->save($user);
            $app['session']->getFlashBag()->add('success', 'L\'utilisateur a été ajouté avec succès.');

            return $app->redirect($app['url_generator']->generate('admin'));
        }

        return $app['twig']->render('user_form.html.twig', array(
            'title'    => 'Nouvel utilisateur',
            'userForm' => $userForm->createView(),
        ));
    }

    public function editUserAction($id, Request $request, Application $app)
    {
        $user = $app['dao.user']->find($id);
        $userForm = $app['form.factory']->create(UserType::class, $user);
        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid())
        {
            $plainPassword = $user->getPassword();
            $encoder = $app['security.encoder_factory']->getEncoder($user);
            $password = $encoder->encodePassword($plainPassword, $user->getSalt());
            $user->setPassword($password);
            $app['dao.user']->save($user);
            $app['session']->getFlashBag()->add('success', 'L\'utilisateur a été modifié avec succès.');

            return $app->redirect($app['url_generator']->generate('admin'));
        }

        return $app['twig']->render('user_form.html.twig', array(
            'title'    => 'Modifier utilisateur',
            'userForm' => $userForm->createView()
        ));
    }

    public function removeUserAction($id, Application $app)
    {
        $app['dao.comment']->deleteAllByUser($id);
        $app['dao.user']->delete($id);
        $app['session']->getFlashBag()->add('success', 'L\'utilisateur a été supprimé avec succès.');

        return $app->redirect($app['url_generator']->generate('admin'));
    }

}