<?php
/**
 * Application Constants
 * 
 * This file contains all application-wide constants
 */

// Application version
define('APP_VERSION', '1.0.0');

// System status constants
define('STATUS_ACTIVE', 'active');
define('STATUS_INACTIVE', 'inactive');
define('STATUS_PENDING', 'pending');
define('STATUS_APPROVED', 'approved');
define('STATUS_REJECTED', 'rejected');
define('STATUS_COMPLETED', 'completed');
define('STATUS_CANCELLED', 'cancelled');

// User roles
define('ROLE_SUPERADMIN', 'superadmin');
define('ROLE_ADMIN', 'admin');
define('ROLE_MEMBER', 'member');

// Default values
define('DEFAULT_CURRENCY', 'NGN');
define('DEFAULT_LANGUAGE', 'en');
define('DEFAULT_TIMEZONE', 'Africa/Lagos');

// File paths for uploads
define('PROFILE_IMAGES_PATH', '/uploads/profiles/');
define('DOCUMENT_UPLOADS_PATH', '/uploads/documents/');

// Application paths
define('VIEWS_PATH', BASE_DIR . '/app/views'); 