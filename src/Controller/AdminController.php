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
        $form = $app['form.factory']->create(EpisodeType::class, $episode);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $app['dao.episode']->save($episode);
            $app['session']->getFlashBag()->add('success', 'L\'épisode a été ajouté avec succès.');

            return $app->redirect($app['url_generator']->generate('admin'));
        }

        return $app['twig']->render('episode_form.html.twig', [
            'title'       => 'Nouvel épisode',
            'episodeForm' => $form->createView()
        ]);
    }

    public function editEpisodeAction($id, Request $request, Application $app)
    {
        $episode = $app['dao.episode']->find($id);
        $form = $app['form.factory']->create(EpisodeType::class, $episode);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $app['dao.episode']->save($episode);
            $app['session']->getFlashBag()->add('success', 'L\'épisode a été modifié avec succès.');

            return $app->redirect($app['url_generator']->generate('admin'));
        }

        return $app['twig']->render('episode_form.html.twig', [
            'title'       => 'Modifier épisode',
            'episodeForm' => $form->createView()
        ]);
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
        $form = $app['form.factory']->create(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $app['dao.comment']->save($comment);
            $app['session']->getFlashBag()->add('success', 'Le commentaire a été modifié avec succès.');

            return $app->redirect($app['url_generator']->generate('admin'));
        }

        return $app['twig']->render('comment_form.html.twig', [
            'title'       => 'Modifier commentaire',
            'commentForm' => $form->createView()
        ]);
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
        $form = $app['form.factory']->create(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $salt = substr(md5(time()), 0, 23);
            $user->setSalt($salt);
            $plainPassword = $user->getPassword();
            $encoder = $app['security.encoder.bcrypt'];
            $password = $encoder->encodePassword($plainPassword, $user->getSalt());
            $user->setPassword($password);
            $user->setRole('ROLE_USER');
            $app['dao.user']->save($user);
            $app['session']->getFlashBag()->add('success', 'L\'utilisateur a été ajouté avec succès.');

            return $app->redirect($app['url_generator']->generate('admin'));
        }

        return $app['twig']->render('user_form.html.twig', [
            'title'    => 'Nouvel utilisateur',
            'userForm' => $form->createView(),
        ]);
    }

    public function editUserAction($id, Request $request, Application $app)
    {
        $user = $app['dao.user']->find($id);
        $form = $app['form.factory']->create(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $plainPassword = $user->getPassword();
            $encoder = $app['security.encoder_factory']->getEncoder($user);
            $password = $encoder->encodePassword($plainPassword, $user->getSalt());
            $user->setPassword($password);
            $app['dao.user']->save($user);
            $app['session']->getFlashBag()->add('success', 'L\'utilisateur a été modifié avec succès.');

            return $app->redirect($app['url_generator']->generate('admin'));
        }

        return $app['twig']->render('user_form.html.twig', [
            'title'    => 'Modifier utilisateur',
            'userForm' => $form->createView()
        ]);
    }

    public function removeUserAction($id, Application $app)
    {
        $app['dao.comment']->deleteAllByUser($id);
        $app['dao.user']->delete($id);
        $app['session']->getFlashBag()->add('success', 'L\'utilisateur a été supprimé avec succès.');

        return $app->redirect($app['url_generator']->generate('admin'));
    }

}