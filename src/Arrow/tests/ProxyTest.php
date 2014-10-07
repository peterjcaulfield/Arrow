<?php

use Mockery as m;

class ProxyTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        Arrow\Proxy::clearResolvedInstances();
        Arrow\Proxy::setProxiedContainer(null);
    }

    public function testProxyCallsUnderlyingProxiedObject()
    {
        $container = array();

        $mock = m::mock('StdClass');

        $container['foo'] = $mock;

        $mock->shouldReceive('bar')->once()->andReturn('baz');

        Arrow\Proxy::setProxiedContainer($container);

        $this->assertEquals('baz', ProxyStub::bar());
    }
}

class ProxyStub extends Arrow\Proxy
{
    public static function getProxyAccessor()
    {
        return 'foo';
    }
}
