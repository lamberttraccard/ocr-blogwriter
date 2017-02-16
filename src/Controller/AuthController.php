<?php

namespace BlogWriter\Controller;

use BlogWriter\Domain\User;
use BlogWriter\Form\Type\UserType;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class AuthController {

    public function loginAction(Request $request, Application $app)
    {
        return $app['twig']->render('login.html.twig', [
            'errors'        => $app['security.last_error']($request),
            'last_username' => $app['session']->get('_security.last_username'),
        ]);
    }

    public function registerAction(Request $request, Application $app)
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

            return $app->redirect($app['url_generator']->generate('login'));
        }

        return $app['twig']->render('register.html.twig', [
            'title'    => 'S\'enregistrer',
            'userForm' => $form->createView(),
        ]);
    }
}