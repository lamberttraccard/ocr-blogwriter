<?php

$app->get('/', '\BlogWriter\Controller\HomeController::indexAction')->bind('home');

$app->get('/login', '\BlogWriter\Controller\AuthController::loginAction')->bind('login');

$app->get('/admin', '\BlogWriter\Controller\AdminController::indexAction')->bind('admin');
$app->match('/admin/episode/add', '\BlogWriter\Controller\AdminController::addEpisodeAction')->bind('admin_episode_add');
$app->match('/admin/episode/{id}/edit', '\BlogWriter\Controller\AdminController::editEpisodeAction')->bind('admin_episode_edit');
$app->get('/admin/episode/{id}/delete', '\BlogWriter\Controller\AdminController::deleteEpisodeAction')->bind('admin_episode_delete');
$app->match('/admin/comment/{id}/edit', '\BlogWriter\Controller\AdminController::editCommentAction')->bind('admin_comment_edit');
$app->get('/admin/comment/{id}/delete', '\BlogWriter\Controller\AdminController::deleteCommentAction')->bind('admin_comment_delete');
$app->match('/admin/user/add', '\BlogWriter\Controller\AdminController::addUserAction')->bind('admin_user_add');
$app->match('/admin/user/{id}/edit', '\BlogWriter\Controller\AdminController::editUserAction')->bind('admin_user_edit');
$app->get('/admin/user/{id}/delete', '\BlogWriter\Controller\AdminController::removeUserAction')->bind('admin_user_delete');

$app->match('/account', '\BlogWriter\Controller\AccountController::indexAction')->bind('account');

$app->match('/episode/{id}', '\BlogWriter\Controller\EpisodeController::indexAction')->bind('episode');
$app->get('/episode/{id}/read', '\BlogWriter\Controller\EpisodeController::readAction')->bind('episode_read');
$app->get('/episode/{id}/unread', '\BlogWriter\Controller\EpisodeController::unreadAction')->bind('episode_unread');