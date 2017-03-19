<?php

namespace D3lph1\MinecraftRconManager;

use D3lph1\MinecraftRconManager\Exceptions\AccessDenyException;
use D3lph1\MinecraftRconManager\Exceptions\IdentifierDoNotMatch;

/**
 * This class is part of the library d3lph1/minecraft-rcon-manager
 * The class in charge of working with RCON
 *
 * @licence MIT
 * @author D3lph1 <d3lph1.contact@gmail.com>
 * @package D3lph1\MinecraftRconManager
 */

class Rcon
{
    const SERVERDATA_AUTH = 3;

    const SERVERDATA_AUTH_RESPONSE = 2;

    const SERVERDATA_EXECCOMMAND = 2;

    const SERVERDATA_RESPONSE_VALUE = 0;

    /**
     * @var Socket
     */
    private $socket;

    /**
     * @param Socket $socket
     * @param string $password
     */
    public function __construct(Socket $socket, $password)
    {
        $this->socket = $socket;
        $this->auth($password);
    }

    /**
     * Make auth RCON - user request
     *
     * @param string $password
     * @throws IdentifierDoNotMatch
     * @throws AccessDenyException
     *
     * @return bool
     */
    private function auth($password)
    {
        // The identifier serves to check the integrity of the response.
        // If the answer id matches the request id, then the answer is authentic.
        $id = $this->generateId();
        $this->socket->write($id, self::SERVERDATA_AUTH, $password);
        $response = $this->socket->read();

        if ($response['type'] === self::SERVERDATA_AUTH_RESPONSE) {
            if ($response['id'] === $id) {
                return true;
            }

            // If the identifiers do not match
            throw new IdentifierDoNotMatch($id, $response['id']);
        }

        throw new AccessDenyException();
    }

    /**
     * Send command
     *
     * @param string $command
     * @param bool   $getFullResponse
     * @throws IdentifierDoNotMatch
     *
     * @return array|bool
     */
    public function send($command, $getFullResponse = false)
    {
        // The identifier serves to check the integrity of the response.
        // If the answer id matches the request id, then the answer is authentic.
        $id = $this->generateId();
        if ($this->socket->write($id, self::SERVERDATA_EXECCOMMAND, $command)) {
            $response = $this->socket->read();

            if ($response['type'] === self::SERVERDATA_RESPONSE_VALUE) {
                if ($response['id'] === $id) {
                    if ($getFullResponse) {
                        return $response;
                    }

                    return trim($response['body']);
                }

                // If the identifiers do not match
                throw new IdentifierDoNotMatch($id, $response['id']);
            }
        }

        return false;
    }

    /**
     * Disconnect from RCON
     */
    public function disconnect()
    {
        $this->socket->disconnect();
    }

    /**
     * Generates a random number
     *
     * @return int
     */
    private function generateId()
    {
        return mt_rand(1, 128);
    }
}
