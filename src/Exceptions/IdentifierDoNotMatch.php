<?php

namespace D3lph1\MinecraftRconManager\Exceptions;

/**
 * This class is part of the library d3lph1/minecraft-rcon-manager
 *
 * @licence MIT
 * @author D3lph1 <d3lph1.contact@gmail.com>
 * @package D3lph1\MinecraftRconManager\Exceptions
 */

class IdentifierDoNotMatch extends RuntimeException
{
    /**
     * @param string          $requestId
     * @param int             $responseId
     * @param int             $code
     * @param \Exception|null $previous
     */
    public function __construct($requestId, $responseId, $code = 0, \Exception $previous = null)
    {
        $message =
            "The request ID and response identifier do not match.
            Request ID: {$requestId}, response ID: {$responseId}";

        parent::__construct($message, $code, $previous);
    }
}
