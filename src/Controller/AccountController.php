<?php

namespace BlogWriter\Controller;

use BlogWriter\Form\Type\UserType;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class AccountController {

    public function indexAction(Application $app, Request $request)
    {
        $user = $app['user'];
        $episodes = $app['dao.episode']->findAll();

        $form = $app['form.factory']->create(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $plainPassword = $user->getPassword();
            $encoder = $app['security.encoder_factory']->getEncoder($user);
            $password = $encoder->encodePassword($plainPassword, $user->getSalt());
            $user->setPassword($password);
            $app['dao.user']->save($user);
            $app['session']->getFlashBag()->add('success', 'Vos détails ont été modifié avec succès.');

            return $app->redirect($app['url_generator']->generate('account'));
        }

        return $app['twig']->render('account.html.twig', [
            'userForm' => $form->createView(),
            'episodes' => $episodes
        ]);

    }
}

