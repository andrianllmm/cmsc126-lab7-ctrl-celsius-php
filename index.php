<?php

/**
 * Application Entry Point
 */

// Base path of the project (root directory)
// IMPORTANT: use this instead of __DIR__ to avoid issues
define('BASE_PATH', __DIR__);

// Load core files
require BASE_PATH . '/config/env.php';
require BASE_PATH . '/config/database.php';
require BASE_PATH . '/models/Student.php';
require BASE_PATH . '/controllers/StudentController.php';

// Load environment variables from .env
loadEnv(BASE_PATH . '/.env');

// Base URL (path prefix only)
$base = getenv('BASE_URL') ?: '';

$base = '/' . trim($base, '/');
$base = $base === '/' ? '' : $base;

define('BASE_URL', $base);

// Initialize database connection
$db = (new Database())->connect();

// Initialize model and controller
$model = new Student($db);
$controller = new StudentController($model);

// Parse current request URI (path only)
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Remove base path
if (BASE_URL !== '') {
    $uri = preg_replace('#^' . preg_quote(BASE_URL, '#') . '#', '', $uri);
}

// Always normalize to single leading slash, no trailing slash
$uri = '/' . trim($uri, '/');

/**
 * Basic Router
 *
 * Maps URI paths to controller actions
 */
if ($uri === '' || $uri === '/') {
    // GET /
    $controller->index();
} elseif ($uri === '/create') {
    // GET /create
    $controller->create();
} elseif ($uri === '/store') {
    // POST /store
    $controller->store();
} elseif (preg_match('#^/edit/(\d+)$#', $uri, $m)) {
    // GET /edit/{id}
    $controller->edit($m[1]);
} elseif (preg_match('#^/update/(\d+)$#', $uri, $m)) {
    // POST /update/{id}
    $controller->update($m[1]);
} elseif (preg_match('#^/delete/(\d+)$#', $uri, $m)) {
    // GET /delete/{id}
    $controller->delete($m[1]);
} else {
    // 404 Not Found
    http_response_code(404);
    require BASE_PATH . '/views/errors/404.php';
}
