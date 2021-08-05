<?php

/** @var Framework\ErrorHandler\HandlersCollection $collection */

$collection->register(\Throwable::class, Framework\ErrorHandler\DefaultHandler::class);
