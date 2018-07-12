<?php

namespace D3lph1\MinecraftRconManager;

use D3lph1\MinecraftRconManager\Exceptions\RuntimeException;

interface Connection
{
    /**
     * Send command.
     *
     * @param string $command
     * @param bool   $getFullResponse
     *
     * @throws RuntimeException
     *
     * @return array|null
     */
    public function send($command, $getFullResponse = false);

    /**
     * Returns last response or null.
     *
     * @return mixed
     */
    public function last();

    /**
     * Disconnect from RCON.
     */
    public function disconnect();
}
