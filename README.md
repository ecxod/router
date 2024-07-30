# router
Router for Ecxod Projects

cat src/konst/K.php (example)
```php
const ROUTEN = [
      'root' =>         ['pfad' => '/',                 'name' => 'Index',        'proto' => ['GET']],
      'register' =>     ['pfad' => '/register.php',     'name' => 'Register',     'proto' => ['GET', 'POST']],
      'authenticate' => ['pfad' => '/authenticate.php', 'name' => 'Authenticate', 'proto' => ['GET']],
];
```
