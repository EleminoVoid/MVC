<?php
// File: /mvc/Autoloader.php
namespace mvc;

class Autoloader {
    public static function register() {
        spl_autoload_register(function ($class) {
            // Define the base directory for the namespace prefix
            $prefix = 'mvc\\'; // Change this to your main namespace
            $base_dir = __DIR__ . '/'; // Base directory for the namespace

            // Check if the class uses the namespace prefix
            $len = strlen($prefix);
            if (strncmp($prefix, $class, $len) !== 0) {
                return; // Move to the next registered autoloader
            }

            // Get the relative class name
            $relative_class = substr($class, $len);

            // Replace the namespace prefix with the base directory
            // Replace namespace separators with directory separators
            $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

            // If the file exists, require it
            if (file_exists($file)) {
                require $file;
            }
        });
    }
}