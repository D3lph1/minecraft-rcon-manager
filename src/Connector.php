<?php

namespace D3lph1\MinecraftRconManager;

use D3lph1\MinecraftRconManager\Exceptions\ConnectSocketException;

/**
 * This class is part of the library d3lph1/minecraft-rcon-manager
 * This class is responsible for connecting to the RCON socket
 *
 * @licence MIT
 * @author  D3lph1 <d3lph1.contact@gmail.com>
 * @package D3lph1\MinecraftRconManager
 */
class Connector implements \ArrayAccess
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
     * Add server in server pool
     *
     * @param string                 $name     Server name
     * @param null|string|array|Rcon $host     Server host
     * @param null|int               $port     Server port
     * @param null|string            $password Server password
     * @param int                    $timeout  Server connection timeout
     */
    public function add($name, $host = null, $port = null, $password = null, $timeout = 10)
    {
        if (is_array($host) or $host instanceof Rcon) {
            $this->servers[$name] = $host;

            return;
        }

        $this->servers[$name] = [
            'host' => (string)$host,
            'port' => (int)$port,
            'password' => (string)$password,
            'timeout' => (int)$timeout
        ];
    }

    /**
     * Get server from server pool by name if it exists
     *
     * @param string $name Server name
     *
     * @return Rcon
     */
    public function get($name)
    {
        if ($this->exists($name)) {
            $server = $this->servers[$name];

            if ($server instanceof Rcon) {
                return $server;
            }

            return $this->connect($server['host'], $server['port'], $server['password'], $server['timeout']);
        }

        throw new \InvalidArgumentException(
            "Server with name \"{$name}\" does not exists in the server pool"
        );
    }

    /**
     * Checks for a server in the server pool
     *
     * @param string $name Server name
     *
     * @return bool
     */
    public function exists($name)
    {
        return isset($this->servers[$name]);
    }

    /**
     * Remove given server from server pool if it exists
     *
     * @param string $name Server name
     */
    public function remove($name)
    {
        if ($this->exists($name)) {
            unset($this->servers[$name]);

            return;
        }

        throw new \InvalidArgumentException(
            "Server with name \"{$name}\" does not exists in the server pool"
        );
    }

    /**
     * Open connection with RCON socket
     *
     * @param string $host     Server host
     * @param int    $port     Server port
     * @param string $password Server password
     * @param int    $timeout  Server connection timeout
     *
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
     * Disconnect from RCON (last connection)
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

    /**
     * Whether a offset exists
     * @link  http://php.net/manual/en/arrayaccess.offsetexists.php
     *
     * @param mixed $offset <p>
     *                      An offset to check for.
     *                      </p>
     *
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     * @since 5.0.0
     */
    public function offsetExists($offset)
    {
        return $this->exists($offset);
    }

    /**
     * Offset to retrieve
     * @link  http://php.net/manual/en/arrayaccess.offsetget.php
     *
     * @param mixed $offset <p>
     *                      The offset to retrieve.
     *                      </p>
     *
     * @return mixed Can return all value types.
     * @since 5.0.0
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * Offset to set
     * @link  http://php.net/manual/en/arrayaccess.offsetset.php
     *
     * @param mixed $offset <p>
     *                      The offset to assign the value to.
     *                      </p>
     * @param mixed $value  <p>
     *                      The value to set.
     *                      </p>
     *
     * @return void
     * @since 5.0.0
     */
    public function offsetSet($offset, $value)
    {
        if (is_array($value) or $value instanceof Rcon) {
            $this->add($offset, $value);

            return;
        }

        throw new \InvalidArgumentException(
            'Value must be a type of array or instance of D3lph1\MinecraftRconManager\Rcon, ' . gettype($value) . ' given'
        );
    }

    /**
     * Offset to unset
     * @link  http://php.net/manual/en/arrayaccess.offsetunset.php
     *
     * @param mixed $offset <p>
     *                      The offset to unset.
     *                      </p>
     *
     * @return void
     * @since 5.0.0
     */
    public function offsetUnset($offset)
    {
        $this->remove($offset);
    }
}
