# router
Router for Ecxod Projects

```sh
php composer.phar require nikic/fast-route
```

cat .htaccess
```conf
RewriteEngine On
RewriteBase /
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^ index.php [QSA,L]
```

cat src/konst/K.php (example)
```php
const ROUTEN = [
      'root' =>         ['pfad' => '/',                 'name' => 'Index',        'proto' => ['GET']],
      'register' =>     ['pfad' => '/register.php',     'name' => 'Register',     'proto' => ['GET', 'POST']],
      'authenticate' => ['pfad' => '/authenticate.php', 'name' => 'Authenticate', 'proto' => ['GET']],
];
```
