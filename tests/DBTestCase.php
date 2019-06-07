<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

abstract class DBTestCase extends TestCase
{

    use RefreshDatabase;

    protected function createUser(): User
    {
        $user          = factory(User::class)->state('superAdmin')->create();
        return $user;
    }

    /**
     * Helper to Invoke a Method and Access Potentially Protected/Private Methods
     * @param $object
     * @param $methodName
     * @param array $parameters
     * @return mixed
     * @throws \ReflectionException
     */
    public function invokeMethod($object, $methodName, array $parameters = [])
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);
        return $method->invokeArgs($object, $parameters);
    }

    /**
     * Helper to get protected properties
     * @param $object
     * @param string $propertyName
     * @return mixed
     * @throws \ReflectionException
     */
    public function getPropertyOfObject($object, string $propertyName) {
        $reflection = new \ReflectionClass($object);
        $property = $reflection->getProperty($propertyName);
        $property->setAccessible(true);
        return $property->getValue($object);
    }

    /**
     * Helper to set protected properties
     * @param $object
     * @param string $propertyName
     * @param $value
     * @throws \ReflectionException
     */
    public function setPropertyOfObject($object, string $propertyName, $value) {
        $reflection = new \ReflectionClass($object);
        $property = $reflection->getProperty($propertyName);
        $property->setAccessible(true);
        $property->setValue($object, $value);
    }

    /**
     * Helper function to create a Request object with content property
     * From: https://github.com/laravel/framework/issues/12496#issuecomment-374868111
     * @param $method
     * @param $content
     * @param string $uri
     * @param array $server
     * @param array $parameters
     * @param array $cookies
     * @param array $files
     * @return \Illuminate\Http\Request
     */
    protected static function createRequest(
        $method,
        $content,
        $parameters = [],
        $server = ['CONTENT_TYPE' => 'application/json'],
        $uri = '/test',
        $cookies = [],
        $files = []
    ) {
        $request = new \Illuminate\Http\Request;
        return $request->createFromBase(
            \Symfony\Component\HttpFoundation\Request::create(
                $uri,
                $method,
                $parameters,
                $cookies,
                $files,
                $server,
                $content
            )
        );
    }

    /**
     * @return string
     */
    protected function getRandomId()
    {
        try {
            return bin2hex(random_bytes(16));
        } catch (\Exception $e) {}
    }
}