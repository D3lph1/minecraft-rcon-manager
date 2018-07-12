<?php

namespace D3lph1\MinecraftRconManager;

use D3lph1\MinecraftRconManager\Exceptions\ConnectSocketException;
use D3lph1\MinecraftRconManager\Exceptions\ServerDoesNotExistsException;

/**
 * This class is part of the library d3lph1/minecraft-rcon-manager
 * This class is responsible for connecting to the RCON socket
 *
 * @licence MIT
 * @author  D3lph1 <d3lph1.contact@gmail.com>
 * @package D3lph1\MinecraftRconManager
 */
class DefaultConnector implements Connector
{
    /**
     * Servers pool
     *
     * @var array
     */
    private $servers = [];

    /**
     * Last connection socket resource
     *
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
     * {@inheritdoc}
     */
    public function add($name, $host = null, $port = null, $password = null, $timeout = 1.0)
    {
        if (is_array($host) or $host instanceof DefaultConnection) {
            $this->servers[$name] = $host;

            return;
        }

        $this->servers[$name] = [
            'host' => (string)$host,
            'port' => (int)$port,
            'password' => (string)$password,
            'timeout' => (float)$timeout
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function get($name)
    {
        if ($this->exists($name)) {
            $server = $this->servers[$name];

            if ($server instanceof DefaultConnection) {
                return $server;
            }

            return $this->connect($server['host'], $server['port'], $server['password'], $server['timeout']);
        }

        throw new ServerDoesNotExistsException($name);
    }

    /**
     * {@inheritdoc}
     */
    public function exists($name)
    {
        return isset($this->servers[$name]);
    }

    /**
     * {@inheritdoc}
     */
    public function remove($name)
    {
        if ($this->exists($name)) {
            unset($this->servers[$name]);

            return;
        }

        throw new ServerDoesNotExistsException($name);
    }

    /**
     * {@inheritdoc}
     */
    public function connect($host = '127.0.0.1', $port = 25575, $password, $timeout = 1.0)
    {
        $this->socket = $this->open($host, $port, $timeout);

        if ($this->errno or $this->errstr) {
            throw new ConnectSocketException($host, $port, $this->errno, (string)$this->errstr);
        }
        stream_set_timeout($this->socket, 3, 0);

        return new DefaultConnection(new Socket($this->socket), $password);
    }

    /**
     * {@inheritdoc}
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
     * @param float  $timeout
     *
     * @return resource
     */
    private function open($host, $port, $timeout)
    {
        return @fsockopen($host, $port, $this->errno, $this->errstr, $timeout);
    }

    /**
     * {@inheritdoc}
     */
    public function getErrno()
    {
        return $this->errno;
    }

    /**
     * {@inheritdoc}
     */
    public function getErrstr()
    {
        return $this->errstr;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        return $this->exists($offset);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value)
    {
        if (is_array($value) or $value instanceof DefaultConnection) {
            $this->add($offset, $value);

            return;
        }

        throw new \InvalidArgumentException(
            'Value must be a type of array or instance of D3lph1\MinecraftRconManager\Rcon, ' . gettype($value) . ' given'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        $this->remove($offset);
    }
}
