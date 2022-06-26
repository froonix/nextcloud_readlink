<?php namespace OCA\ReadLink\Controller;

class InfoController extends \OCP\AppFramework\Controller
{
	private $userId;

	public function __construct($AppName, \OCP\IRequest $request, $UserId)
	{
		parent::__construct($AppName, $request);
		$this->userId = $UserId;
	}

	/**
	* @NoAdminRequired
	*/
	public function getPath($fileId)
	{
		if(!($file = \OC::$server->getRootFolder()->getById($fileId)))
		{
			return null;
		}

		$info    = $file[0]->getFileInfo();
		$mount   = $info->getMountPoint();
		$type    = $mount->getMountType();
		$storage = $info->getStorage();
		$local   = $storage->isLocal();
		$path    = null;

		if($local)
		{
			$path = preg_replace('#[/]{2,}#', '/', $storage->getLocalFile($info->getInternalPath()));
		}
		else if($type == 'external')
		{
			// Sadly there's no way to get the server details array from "files_external" app. This is a very ugly workaround by re-formatting the storage ID.
			$path = preg_replace('#^([A-Z]+)::' . preg_quote($this->userId, '#') . '@([^/]+)[/]+(.+?)[/]*$#i', '$1://$2/$3', $storage->getId()) . '/' . $info->getInternalPath();
			$path = preg_match('#[A-Z]+://#i', $path) ? $path : false;
		}
		else if($type == 'shared')
		{
			// If you know, how to get the real storage object for shared objects at external mounts: Please open a PR!
			$path = false;
		}

		return json_encode(['path' => $path, 'local' => $local]);
	}
}
