# DealerInventory.app Client

PHP Client to connector for <https://deaperinventory.app>

```
composer require dealerinventory/client
```

### Quick Example:

```
$client = new DealerInventory('client_key');

$info = $client->info();

echo $info->name;
``` 

### Further Examples 
```
// first page of your listed vehicles
$vehicles = $client->listed(1); // first page

$vehicles->each(function($vehicle) {
    echo $vehicle->name;
});

// get a single vehicle
$vehicle = $client->vehicle('my-vehicle-slug');
```
