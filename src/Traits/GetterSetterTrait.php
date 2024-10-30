<?php

namespace Link1515\JobNotification\Traits;

use Link1515\JobNotification\Exceptions\PropertyNotFoundException;

trait GetterSetterTrait
{
    public function __call($methodName, $args)
    {
        if ($this->isGetter($methodName)) {
            return $this->getter($methodName);
        }

        if ($this->isSetter($methodName)) {
            return $this->setter($methodName, $args);
        }

        throw new \BadMethodCallException('Call to undefined method ' . get_class($this) . '::' . $methodName . '()');
    }

    private function isGetter(string $methodName): bool
    {
        return preg_match('/^get.*/', $methodName);
    }

    private function getter($methodName)
    {
        $propName = lcfirst(preg_replace('/^get(.*)/', '$1', $methodName));

        if (!property_exists($this, $propName)) {
            throw new PropertyNotFoundException($propName);
        }
        return $this->$propName;
    }

    private function isSetter(string $methodName): bool
    {
        return preg_match('/^set.*/', $methodName);
    }

    private function setter($methodName, $args)
    {
        $propName = lcfirst(preg_replace('/^set(.*)/', '$1', $methodName));

        if (!property_exists($this, $propName)) {
            throw new PropertyNotFoundException($propName);
        }

        $this->$propName = $args[0];
        return $this;
    }
}
