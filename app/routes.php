<?php

$app->get('/', '\BlogWriter\Controller\HomeController::indexAction')->bind('home');
$app->match('/episode/{id}', '\BlogWriter\Controller\HomeController::episodeAction')->bind('episode');

$app->match('/admin/episode/add', '\BlogWriter\Controller\AdminController::addEpisodeAction')->bind('admin_episode_add');
$app->match('/admin/episode/{id}/edit', '\BlogWriter\Controller\AdminController::editEpisodeAction')->bind('admin_episode_edit');
$app->get('/admin/episode/{id}/delete', '\BlogWriter\Controller\AdminController::deleteEpisodeAction')->bind('admin_episode_delete');
$app->match('/admin/comment/{id}/edit', '\BlogWriter\Controller\AdminController::editCommentAction')->bind('admin_comment_edit');
$app->get('/admin/comment/{id}/delete', '\BlogWriter\Controller\AdminController::deleteCommentAction')->bind('admin_comment_delete');
$app->match('/admin/user/add', '\BlogWriter\Controller\AdminController::addUserAction')->bind('admin_user_add');
$app->match('/admin/user/{id}/edit', '\BlogWriter\Controller\AdminController::editUserAction')->bind('admin_user_edit');
$app->get('/admin/user/{id}/delete', '\BlogWriter\Controller\AdminController::removeUserAction')->bind('admin_user_delete');

$app->get('/login', '\BlogWriter\Controller\HomeController::loginAction')->bind('login');
$app->get('/admin', '\BlogWriter\Controller\AdminController::indexAction')->bind('admin');