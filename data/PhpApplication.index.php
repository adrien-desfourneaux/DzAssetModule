<?php

// DO NOT USE !

$openSession = function($alreadyOpened) {
    if (!$alreadyOpened) {
        session_start();
    }
};

$closeSession = function($alreadyOpened) {
    if (!$alreadyOpened) {
        session_write_close();
    }
};

$alreadyOpened = isset($_SESSION) ? true : false;

$openSession($alreadyOpened);

if (isset($_SESSION['DzAssetModule\Sender'])) {
	if (isset($_SESSION['DzAssetModule\Sender']['PhpApplication'])
		&& $_SESSION['DzAssetModule\Sender']['PhpApplication']['sent'] === false)
	{
		$phpApplication = $_SESSION['DzAssetModule\Sender']['PhpApplication']['file'];
		$_SESSION['DzAssetModule\Sender']['PhpApplication']['sent'] = true;
		$closeSession($alreadyOpened);

		include $phpApplication;
		exit;
	}
}

$closeSession($alreadyOpened);

unset($openSession);
unset($closeSession);
unset($alreadyOpened);
unset($phpApplication);