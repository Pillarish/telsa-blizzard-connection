## Telsa Blizzard Connection

A central repository for any project wishing to connect to the Blizzard API.

### Required config definitions

* client_id
* client_secret

```yaml
telsa_blizzard_connection:
    client_id: ~
    client_secret: ~
```

### General Usage

```php
// create your desired API connection
$api = $this->get('telsa_blizzard_connection.apis.world_of_warcraft.character_profile_api');

// set the region
$api->setRegion('eu');

// set the required params
$api->setCharacterName('Pillaren');
$api->setRealm('the-venture-co');

// call the desired endPoint
$char = $api->getCharacter();

// call with any required extra details
$char = $api->getCharacter(['professions', 'mounts', 'talents']);

// some helper functions. eg.
$charWithMounts = $api->getCharacterMounts();
$charWithTalents = $api->getCharacterTalents();
```