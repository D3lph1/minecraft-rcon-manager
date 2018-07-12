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
     * @return string|array|null
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
