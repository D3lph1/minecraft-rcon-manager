<?php

namespace D3lph1\MinecraftRconManager;

use D3lph1\MinecraftRconManager\Exceptions\ConnectSocketException;

/**
 * This class is part of the library d3lph1/minecraft-rcon-manager
 * This class is responsible for connecting to the RCON socket
 *
 * @licence MIT
 * @author D3lph1 <d3lph1.contact@gmail.com>
 * @package D3lph1\MinecraftRconManager
 */

class Connector
{
    /**
     * @var null|resource
     */
    private $socket = null;

    /**
     * Socket connection error number
     *
     * @var null|int
     */
    private $errno = null;

    /**
     * Socket connection error description string
     *
     * @var null|string
     */
    private $errstr = null;

    /**
     * Open connection with RCON socket
     *
     * @param string $host
     * @param int    $port
     * @param string $password
     * @param int    $timeout
     * @throws ConnectSocketException
     *
     * @return Rcon
     */
    public function connect($host = '127.0.0.1', $port = 25575, $password, $timeout = 10)
    {
        $this->socket = $this->open($host, $port, $timeout);

        if ($this->errno or $this->errstr) {
            throw new ConnectSocketException($host, $port, $this->errno, (string)$this->errstr);
        }
        stream_set_timeout($this->socket, 3, 0);

        return new Rcon(new Socket($this->socket), $password);
    }

    /**
     * Disconnect from RCON
     */
    public function disconnect()
    {
        if ($this->socket) {
            fclose($this->socket);
        }
    }

    /**
     * @param string $host
     * @param int    $port
     * @param int    $timeout
     *
     * @return resource
     */
    private function open($host, $port, $timeout)
    {
        return @fsockopen($host, $port, $this->errno, $this->errstr, $timeout);
    }

    /**
     * @return null|int
     */
    public function getErrno()
    {
        return $this->errno;
    }

    /**
     * @return null|string
     */
    public function getErrstr()
    {
        return $this->errstr;
    }
}
