<?php

namespace D3lph1\MinecraftRconManager;

use D3lph1\MinecraftRconManager\Exceptions\RuntimeException;

/**
 * This interface is part of the library d3lph1/minecraft-rcon-manager
 *
 * @licence MIT
 * @author  D3lph1 <d3lph1.contact@gmail.com>
 * @package D3lph1\MinecraftRconManager
 */
interface Connector extends \ArrayAccess
{
    /**
     * Add server in server pool
     *
     * @param string                              $name     Server name
     * @param null|string|array|DefaultConnection $host     Server host
     * @param null|int                            $port     Server port
     * @param null|string                         $password Server password
     * @param float                               $timeout  Server connection timeout
     */
    public function add($name, $host = null, $port = null, $password = null, $timeout = 1.0);

    /**
     * Get server from server pool by name if it exists
     *
     * @param string $name Server name
     *
     * @return Connection
     */
    public function get($name);

    /**
     * Checks for a server in the server pool
     *
     * @param string $name Server name
     *
     * @return bool
     */
    public function exists($name);

    /**
     * Remove given server from server pool if it exists
     *
     * @param string $name Server name
     */
    public function remove($name);

    /**
     * Open connection with RCON socket
     *
     * @param string $host     Server host
     * @param int    $port     Server port
     * @param string $password Server password
     * @param float  $timeout  Server connection timeout
     *
     * @return Connection
     * @throws RuntimeException
     */
    public function connect($host = '127.0.0.1', $port = 25575, $password, $timeout = 1.0);

    /**
     * Close last RCON connection.
     */
    public function disconnect();

    /**
     * Returns last error number.
     *
     * @return null|int
     */
    public function getErrno();

    /**
     * Returns last error description.
     *
     * @return null|string
     */
    public function getErrstr();
}
