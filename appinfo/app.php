<?php

\OC::$server->getEventDispatcher()->addListener('OCA\Files::loadAdditionalScripts', function()
{
	OCP\Util::addScript('readlink', 'readlink');
});
