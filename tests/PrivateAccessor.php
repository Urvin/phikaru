<?php


namespace urvin\phikaru\tests;


class PrivateAccessor
{

    /**
     * @param $object
     * @param string $name
     * @return mixed
     * @throws \ReflectionException
     */
    public function getPropertyValue($object, string $name)
    {
        $reflector = new \ReflectionClass($object);
        $property = $reflector->getProperty($name);
        $property->setAccessible(true);
        return $property->getValue($object);
    }

    /**
     * @param $object
     * @param string $name
     * @param array $params
     * @return mixed
     * @throws \ReflectionException
     */
    public function getPropertyMethodResult($object, string $name, array $params)
    {
        $reflector = new \ReflectionClass($object);
        $method = $reflector->getMethod( $name );
        $method->setAccessible( true );
        return $method->invokeArgs($object, $params);
    }
}