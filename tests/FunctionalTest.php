<?php

namespace D3lph1\MinecraftRconManager\tests;

use D3lph1\MinecraftRconManager\Connector;

/**
 * This class is part of the library d3lph1/minecraft-rcon-manager
 *
 * @licence MIT
 * @author D3lph1 <d3lph1.contact@gmail.com>
 * @package D3lph1\MinecraftRconManager\tests
 */

class FunctionalTest extends \PHPUnit_Framework_TestCase
{
    public function testSend()
    {
        $connector = new Connector();
        $rcon = $connector->connect('127.0.0.1', 25575, '123456', 10);

        $response = $rcon->send('say Yes, it works...');
        $this->assertEquals('§d[Rcon§d] Yes, it works...', $response);
    }
}
