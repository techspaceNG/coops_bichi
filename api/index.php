<?php
/**
 * Vercel Entry Point
 * This file bridges Vercel's serverless function requests to our Front Controller.
 */

// Define that we are running in a serverless environment
define('SERVERLESS_ENVIRONMENT', true);

// Include the standard entry point
require __DIR__ . '/../public/index.php';
