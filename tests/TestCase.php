<?php

declare(strict_types=1);

namespace LaravelPsl\Tests;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Http\Kernel as HttpKernel;
use LaravelPsl\LaravelPslServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

abstract class TestCase extends Orchestra
{
    /**
     * @return list<class-string>
     */
    protected function getPackageProviders($app): array
    {
        return [
            LaravelPslServiceProvider::class,
        ];
    }

    public function artisan($command, $parameters = [])
    {
        return parent::artisan($command, $parameters);
    }

    public function be(Authenticatable $user, $driver = null)
    {
        return parent::be($user, $driver);
    }

    public function call($method, $uri, $parameters = [], $files = [], $server = [], $content = null, $changeHistory = true)
    {
        $kernel = $this->app->make(HttpKernel::class);

        $files = array_merge($files, $this->extractFilesFromDataArray($parameters));

        $symfonyRequest = SymfonyRequest::create(
            $this->prepareUrlForRequest($uri),
            $method,
            $parameters,
            [],
            $files,
            array_replace($this->serverVariables, $server),
            $content,
        );

        $response = $kernel->handle(
            $request = $this->createTestRequest($symfonyRequest),
        );

        $kernel->terminate($request, $response);

        if ($changeHistory && $this->followRedirects) {
            $response = $this->followRedirects($response);
        }

        return $this->createTestResponse($response, $request);
    }

    public function seed($class = 'DatabaseSeeder')
    {
        if ($class === 'DatabaseSeeder') {
            $class = 'Database\\Seeders\\DatabaseSeeder';
        }

        return parent::seed($class);
    }
}
