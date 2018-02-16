<?php
Twig_Autoloader::register();

$loader = new Twig_Loader_Filesystem('src/'.$bundle.'Bundle/view/');
$twig = new Twig_Environment($loader);

echo $twig->render('404.twig', array(
									'title' => 'Not found'
									));
}



?>
