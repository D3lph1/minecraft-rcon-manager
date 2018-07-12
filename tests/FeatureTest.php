<?php

namespace D3lph1\MinecraftRconManager\tests;

use D3lph1\MinecraftRconManager\DefaultConnector;

/**
 * This class is part of the library d3lph1/minecraft-rcon-manager
 *
 * @licence MIT
 * @author D3lph1 <d3lph1.contact@gmail.com>
 * @package D3lph1\MinecraftRconManager\tests
 */

class FeatureTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test send commands on one server
     */
    public function testOneServerSend()
    {
        $connector = new DefaultConnector();
        $rcon = $connector->connect('127.0.0.1', 25575, '123456', 10);

        $response = $rcon->send('say Yes, it works...');
        self::assertEquals('§d[Rcon§d] Yes, it works...', $response);
        $rcon->disconnect();
    }

    /**
     * Test send commands on many servers from server pool
     */
    public function testManyServers()
    {
        $connector = new DefaultConnector();

        // Add server in servers pool
        $connector->add('hi_tech', '127.0.0.1', 25575, '123456', 10);

        // Add server in servers pool
        $connector->add('mmo', [
            'host' => '127.0.0.1',
            'port' => 25575,
            'password' => '123456',
            'timeout' => 10
        ]);

        // Connecting to servers
        $rconHiTech = $connector->get('hi_tech');
        $rconMmo = $connector->get('mmo');

        // Send requests
        $responseHiTech = $rconHiTech->send('say Yes, it works on HiTech!');
        $responseMmo = $rconMmo->send('say Yes, it works on MMO!');

        // Asserting
        self::assertEquals('§d[Rcon§d] Yes, it works on HiTech!', $responseHiTech);
        self::assertEquals('§d[Rcon§d] Yes, it works on MMO!', $responseMmo);

        $rconHiTech->disconnect();
        $rconMmo->disconnect();
    }
}
