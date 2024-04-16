<?php

namespace GlimpsGoneV2\core;

class TemplateEngine
{
private $pug;

public function __construct()
{
$this->pug = new \Pug\Pug([
'basedir' => __DIR__ . '/../../view',
'pretty' => true 
]);
}

public function render($template, $variables = [])
{
echo $this->pug->render($template, $variables);
}
}


// session_start();
// $userLoggedIn = isset($_SESSION['user_id']);

// $pug = new Pug();
// $html = $pug->render('template.pug', [
//     'userLoggedIn' => $userLoggedIn
// ]);
// echo $html;
// ?>

