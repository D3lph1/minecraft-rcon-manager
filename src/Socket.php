<?php

namespace D3lph1\MinecraftRconManager;

/**
 * This class is part of the library d3lph1/minecraft-rcon-manager
 * Sends data to the socket and then reads it
 *
 * @licence MIT
 * @author  D3lph1 <d3lph1.contact@gmail.com>
 * @package D3lph1\MinecraftRconManager
 */

class Socket
{
    /**
     * Socket stream resource.
     *
     * @var resource
     */
    private $socket;

    /**
     * @param resource $socket
     *
     * @throws \InvalidArgumentException
     */
    public function __construct($socket)
    {
        if (!$this->isStream($socket)) {
            throw new \UnexpectedValueException(
                'Socket must be resource of type "stream"'
            );
        }

        $this->socket = $socket;
    }

    /**
     * Read data from socket.
     *
     * @return array
     */
    public function read()
    {
        if (!$this->isStream($this->socket)) {
            throw new \UnexpectedValueException(
                'Socket must be resource of type "stream"'
            );
        }

        $size = fread($this->socket, 4);
        $package = unpack("V1size", $size);
        $size = $package['size'];
        $package = fread($this->socket, $size);

        return unpack("V1id/V1type/a*body", $package);
    }

    /**
     * Write data in socket.
     *
     * @param int    $id
     * @param int    $type
     * @param string $body
     *
     * @return int
     */
    public function write($id, $type, $body)
    {
        if (!$this->isStream($this->socket)) {
            throw new \UnexpectedValueException(
                'Socket must be resource of type "stream"'
            );
        }

        $package = pack("VV", $id, $type) . $body . "\x00" . "\x00";
        $size = strlen($package);
        $package = pack("V", $size) . $package;

        return fwrite($this->socket, $package, strlen($package));
    }

    private function isStream($socket)
    {
        return is_resource($socket) ? (get_resource_type($socket) === 'stream') : false;
    }

    /**
     * Close RCON connection.
     */
    public function disconnect()
    {
        if ($this->socket) {
            fclose($this->socket);
        }
    }
}
