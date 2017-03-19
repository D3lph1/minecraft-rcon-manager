# minecraft-rcon-manager
Library for easy interaction with the minecraft server using rcon.

## Usage
```php
use \D3lph1\MinecraftRconManager\Connector;
use \D3lph1\MinecraftRconManager\Exceptions\ConnectSocketException;
use \D3lph1\MinecraftRconManager\Exceptions\AccessDenyException;

$connector = new Connector();
try {
    // Connect to RCON
    $rcon = $connector->connect('127.0.0.1', 25575, '123456');
}catch(ConnectSocketException $e) {
    // do something...
}catch(AccessDenyException $e) {
    // do something...
}

// Get only response body
$response = $rcon->send('say Yes, it works...');

print_r($response); // §d[Rcon§d] Yes, it works...

// Get response array
$response = $rcon->send('say Yes, it works...', true);

print_r($response);
/**
 *   Array (
 *      [id] => 16
 *      [type] => 0
 *      [body] => §d[Rcon§d] Yes, it works...
 *   )
 *
 */

// Get last response
$last = $rcon->getLast();

// Disconnect from RCON
$rcon->disconnect();

```
