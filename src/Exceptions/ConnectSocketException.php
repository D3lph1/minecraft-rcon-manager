<?php

namespace D3lph1\MinecraftRconManager\Exceptions;

/**
 * This class is part of the library d3lph1/minecraft-rcon-manager
 *
 * @licence MIT
 * @author  D3lph1 <d3lph1.contact@gmail.com>
 * @package D3lph1\MinecraftRconManager\Exceptions
 */

class ConnectSocketException extends RuntimeException
{
    /**
     * @param string          $host   RCON host
     * @param int             $ip     RCON port
     * @param int             $errno  Error number
     * @param string          $errstr Error description string
     * @param int             $code
     * @param \Exception|null $previous
     */
    public function __construct($host, $ip, $errno, $errstr, $code = 0, \Exception $previous = null)
    {
        parent::__construct("Could not connect to socket {$host}:{$ip}", $code, $previous);
    }
}
