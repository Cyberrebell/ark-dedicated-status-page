<?php

namespace Application\Model;

require_once __DIR__ . '/../../../../../vendor/koraktor/steam-condenser/lib/steam-condenser.php';

class ArkDedicatedServer extends \SourceServer
{
	protected $serverStatus;
	protected $game;
	protected $serverVersion;
	protected $serverName;
	protected $passwordProtected;
	protected $map;
	protected $playersOnline;
	protected $slots;
	
	public function getServerStatus()
	{
		if (!$this->serverStatus) {
			$this->loadServerInfo();
		}
		return $this->serverStatus;
	}
	
	public function getGame()
	{
		if (!$this->game) {
			$this->loadServerInfo();
		}
		return $this->game;
	}
	
	public function getServerVersion()
	{
		if (!$this->serverVersion) {
			$this->loadServerInfo();
		}
		return $this->serverVersion;
	}
	
	public function getServerName()
	{
		if (!$this->serverName) {
			$this->loadServerInfo();
		}
		return $this->serverName;
	}
	
	public function getPasswordProtected()
	{
		if (!$this->passwordProtected) {
			$this->loadServerInfo();
		}
		return $this->passwordProtected;
	}
	
	public function getMap()
	{
		if (!$this->map) {
			$this->loadServerInfo();
		}
		return $this->map;
	}
	
	public function getPlayersOnline()
	{
		if (!$this->playersOnline) {
			$this->loadServerInfo();
		}
		return $this->playersOnline;
	}
	
	public function getSlots()
	{
		if (!$this->slots) {
			$this->loadServerInfo();
		}
		return $this->slots;
	}
	
	protected function loadServerInfo()
	{
		try {
			$serverInfo = $this->getServerInfo();
		} catch (\SocketException $e) {
			$this->serverStatus = false;
			return null;
		}
		
		$this->game = $serverInfo['gameDesc'];

		$steamServerName = $serverInfo['serverName'];
		$versionStr = strrchr($steamServerName, '(');
		$this->serverVersion = substr($versionStr, 1, strlen($versionStr) - 2);
		$this->serverName = substr($steamServerName, 0, strlen($steamServerName) - strlen($versionStr) - 3);

		$this->passwordProtected = $serverInfo['passwordProtected'];
		$this->map = $serverInfo['mapName'];
		$this->playersOnline = $serverInfo['numberOfPlayers'];
		$this->slots = $serverInfo['maxPlayers'];
	}
}
