<?php 
/* 
	Manually load some needed third party libraries.
	We use the already available composer loader here.
 */
call_user_func(function () {
	$loader = new Composer\Autoload\ClassLoader();
	$third_party_packages = [
		// Add the necessary namespaces below //
		'Respect\\' => array(__DIR__.'/third_party'), // See: https://github.com/Respect/Validation
		'JShrink\\' => array(__DIR__.'/third_party') // See: https://github.com/tedious/JShrink
	];	
	foreach ($third_party_packages as $namespace => $path) {
		$loader->set($namespace, $path);
	}
	$loader->register(true);
});