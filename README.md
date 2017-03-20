# minecraft-rcon-manager
Library for easy interaction with the minecraft server using rcon.

## Installation
Execute command:
```bash
composer require d3lph1/minecraft-rcon-manager ~1.0
```
or add line to require section of `composer.json`
```json
"d3lph1/minecraft-rcon-manage": "~1.0",
```
and execute:
```bash
composer update
```

## Usage

### Overview
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

### Connecting
First, you need to create an instance of the Connector class. The instance will serve to create a connection to the server, add servers to the pool and remove them from there.
```php
use \D3lph1\MinecraftRconManager\Connector;

$connector = new Connector();
```
In order to connect directly to the server, use this construction
```php
$rcon = $connector->connect($host, $port, $password, $timeout);
```
Where:
* `$host` - Server host (By default, `127.0.0.1`)
* `$port` - Rcon port (By default, `25575`)
* `$password` - Rcon password
* `$timeout` - Connection timeout (By default, `10` seconds)


You can also add the server to the server pool in order to get the connection instance later:
```php
$connector->add($name, $host, $port, $password, $timeout);

// Still as an option...
$connector->add('hi_tech', [
    'host' => '127.0.0.1',
    'port' => 25575,
    'password' => '123456',
    'timeout' => 10
]);

// Since the Connector class implements the ArrayAccess interface,
// the servers can be placed into the pool by accessing the object as an array.
$connector['mmo'] = [
    'host' => '127.0.0.1',
    'port' => 25576,
    'password' => '123456',
    'timeout' => 10
];
```

Ok, now we need the servers in the pool. Now that you need a server in the future, you will pull it out of the pool like this:
```php
$rcon = $connector->get('hi_tech');

// Or...
$rcon = $connector['mmo'];
```

Remove server from server pool:
```php
$connector->remove('hi_tech');

// or...
unset($connector['hi_tech']);
```

### Sending requests

Using the constructs described above, we get an instance of the class `D3lph1\MinecraftRconManager\Rcon` with which we can finally send requests to our server:
```php
$response = $rcon->send('say Yes, it works...');
```
In return we will get something like this: `§d[Rcon§d] Yes, it works...`


You can get not only the body of the answer but the entire array in which besides the body contains also the service information. Just pass `true` to the second argument to `send()`.
```php
$response = $rcon->send('say Yes, it works...', true);
```
This array contains element `type` - the type of request (authorization or command execution) and the request `id` - required to check the integrity of the response.


Get data from last request:
```php
$response = $rcon->last();
```

Disconnect from server:
```php
$rcon->disconnect();
```

