## Config

### Create
Create new php file in the `Config` folder.

The file need to return an array

**Example:**
/Config/example.php
```
return [
	'env' => 'TEST',
	'auth' => [
		'login' => 'bill',
		'password' => '12345678'
	]
];
```

### Use
```
use Kit\Config;
```

### Get
You can get value from your config file with:
```
Config::get($key);
```

**Example 1:**
```
$exampleConfig = Config::get('example');
```

**Example 2:**
```
$login = Config::get('example.auth.login');
```


### Set
You can change config value at runtime with:
```
Config::set($key, $value);
```

**Example:**
```
echo Config::get('example.auth.password'); // 12345678

Config::set('example.auth.password', '87654321');

echo Config::get('example.auth.password'); // 87654321
```
