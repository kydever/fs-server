<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
use Hyperf\HttpServer\Router\Router;

Router::addRoute(['GET', 'POST', 'HEAD'], '/', 'App\Controller\IndexController::index');

Router::get('/oauth/authorize', [App\Controller\OAuthController::class, 'authorize']);
Router::get('/oauth/login', [App\Controller\OAuthController::class, 'login']);
Router::post('/oauth/login', [App\Controller\OAuthController::class, 'login']);

Router::post('/file/{id:\d+}', [App\Controller\FileController::class, 'save']);
Router::get('/file/{id:\d+}', [App\Controller\FileController::class, 'info']);
Router::get('/file/download-url', [App\Controller\FileController::class, 'downloadUrl']);
