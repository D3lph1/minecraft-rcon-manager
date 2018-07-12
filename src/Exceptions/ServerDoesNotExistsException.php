<?php

namespace D3lph1\MinecraftRconManager\Exceptions;

use Throwable;

/**
 * This class is part of the library d3lph1/minecraft-rcon-manager
 *
 * @licence MIT
 * @author  D3lph1 <d3lph1.contact@gmail.com>
 * @package D3lph1\MinecraftRconManager\Exceptions
 */
class ServerDoesNotExistsException extends DomainException
{
    public function __construct($server, $code = 0, Throwable $previous = null)
    {
        $message = "Server with name \"{$server}\" does not exists in the server pool";

        parent::__construct($message, $code, $previous);
    }
}
