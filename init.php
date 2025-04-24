<?php
// File: /mvc/init.php
namespace mvc;

require_once 'vendor/autoload.php'; // Include Composer's autoloader if using Composer
require_once 'Autoloader.php'; // Include the custom autoloader

Autoloader::register(); // Register the custom autoloader