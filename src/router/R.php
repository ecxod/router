<?php

declare(strict_types=1);

namespace Ecxod\Router;

use \Ecxod\Konst\K;
use \FastRoute\RouteCollector;
use function \FastRoute\simpleDispatcher;

class R
{

    public array $routen;
    protected \FastRoute\Dispatcher $dispatcher;
    protected string $httpMethod;
    protected string $requesturi;
    protected string $uri;
    protected string $vars;
    protected $url;
    protected array $routeInfo;

    function __construct()
    {
        $this->routen = K::ROUTEN; // initialisierung
        $this->httpMethod = $_SERVER['REQUEST_METHOD'];
        $this->requesturi = $_SERVER['REQUEST_URI'];

        if ($exp = explode(separator: '?', string: $this->requesturi, limit: 2)) {
            $this->uri = $exp[0];
            $this->vars = empty($exp[1]) ? '' : strval($exp[1]);
            $this->vars = empty($this->vars) ? '' : explode(separator: '&', string: $this->vars)[0];
        }
        $this->uri = rawurldecode($this->uri);
        if (empty($_ENV['PHP'])) {
            $_ENV['PHP'] = "/../php/";
            \Sentry\captureMessage(message: 'Router::__construct() must have $_ENV["PHP"] deined');
        }
    }

    function go()
    {
        $txt = "";
        $tvars = $this->vars;

        $this->dispatcher = simpleDispatcher(function (RouteCollector $r) use (&$txt, $tvars): void {
            foreach ($this->routen as $rval) {
                $proto = $rval['proto'];
                $pfad =  $rval['pfad'];

                $r->addRoute(httpMethod: $proto, route: $pfad, handler: function () use ($pfad, &$txt, $tvars): void {

                    $pfad = $pfad === '/' ? '/index.php' : $pfad;
                    $realpath = realpath($_SERVER['DOCUMENT_ROOT'] . $_ENV['PHP'] . $pfad);
                    if (!empty($realpath)) {
                        ob_start();
                        $vars = $tvars;
                        include $realpath;
                        $txt .= ob_get_clean();
                    }
                });
            }
        });


        $this->routeInfo = $this->dispatcher->dispatch(httpMethod: $this->httpMethod, uri: $this->uri);

        switch ($this->routeInfo[0]) {
            case \FastRoute\Dispatcher::NOT_FOUND:
                // 404 Not Found
                $txt .= '404 Not Found';
                break;
            case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                // 405 Method Not Allowed
                $txt .= '405 Method Not Allowed';
                break;
            case \FastRoute\Dispatcher::FOUND:
                $handler = $this->routeInfo[1];
                $handler();
                break;
        }
        return $txt;
    }
}
