<?php

namespace BlogWriter\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class APIController {

    public function userAction(Request $request, Application $app)
    {
        $user = false;

        if ($request->request->has('username'))
        {
            $user = $app['dao.user']->findOneBy(['username', $request->request->get('username')]);
        }

        if (!$user) return $app->json('No user found', 400);

        $responseData = array(
            'id'          => $user->getId(),
            'username'    => $user->getUsername(),
            'displayName' => $user->getDisplayName()
        );

        return $app->json($responseData);
    }
}