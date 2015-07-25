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
	
	public function getServerLoad()
	{
		if ($this->ipAddress == '127.0.0.1') {
			$load = sys_getloadavg();
			$cpuCores = $this->num_cpus();
			
			return (int) (($load[1] / $cpuCores) * 100.0);
		}
	}
	
	protected function loadServerInfo()
	{
		try {
			$serverInfo = $this->getServerInfo();
		} catch (\SocketException $e) {
			$this->serverStatus = false;
			return null;
		}
		$this->serverStatus = true;
		
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
	
	protected function num_cpus()
	{
		$numCpus = 1;
		if (is_file('/proc/cpuinfo'))
		{
			$cpuinfo = file_get_contents('/proc/cpuinfo');
			preg_match_all('/^processor/m', $cpuinfo, $matches);
			$numCpus = count($matches[0]);
		}
		else if ('WIN' == strtoupper(substr(PHP_OS, 0, 3)))
		{
			$process = @popen('wmic cpu get NumberOfCores', 'rb');
			if (false !== $process)
			{
				fgets($process);
				$numCpus = intval(fgets($process));
				pclose($process);
			}
		}
		else
		{
			$process = @popen('sysctl -a', 'rb');
			if (false !== $process)
			{
				$output = stream_get_contents($process);
				preg_match('/hw.ncpu: (\d+)/', $output, $matches);
				if ($matches)
				{
					$numCpus = intval($matches[1][0]);
				}
				pclose($process);
			}
		}
	
		return $numCpus;
	}
}
