<?php

/** @var Framework\ErrorHandler\HandlersCollection $collection */

$collection->register(Exception::class, Framework\ErrorHandler\DefaultHandler::class);
