<?php

namespace Link1515\JobNotification\Exceptions;

class PropertyNotFoundException extends \Exception
{
    public function __construct($propertyName)
    {
        $message = "Property '$propertyName' not found.";
        parent::__construct($message);
    }
}
