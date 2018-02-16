<?php
Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem('src/'.$bundle.'Bundle/view/');
$twig = new Twig_Environment($loader);

echo $twig->render('index.twig', array(
										'title' => "Inicio - test"
					));


?>
