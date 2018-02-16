<?php
try{
	require_once('app.php');
}catch(Exception $e){
	print '<pre>' ;
	print 'Exception: ' . $e->getMessage () . "\n";
	print  $e->getTraceAsString () . "\n";
	print  '</pre>' ;
}

?>
