<?php

/*
|--------------------------------------------------------------------------
| Define constant
|--------------------------------------------------------------------------
|
| Define constant variable to use for whole app
| Make sure run `composer dump-autoload` to load this file
|
*/

// Common
define('SLASH', '/');
define('UPLOAD_PATH', 'upload');
define('MAX_UPLOAD_SIZE', 30720);
define('MAX_PDF_UPLOAD_SIZE', 30720);
define('MAX_PORTFOLIO_THUMBNAIL_UPLOAD_SIZE', 2048);
define('NUM_CHAR_BACKUP_KEY', 20);
define('SEARCH_SKILL', 'skills');
define('SEARCH_OCCUPATION', 'occupations');
define('SEARCH_COMPANY', 'companies');
define('SEARCH_APPLICATION', 'applications');

// Admin
define('ADMIN', 'admin');
define('ADMIN_GUARD', 'admin-api');
define('ADMIN_UPLOAD_PATH', UPLOAD_PATH . DIRECTORY_SEPARATOR . ADMIN . DIRECTORY_SEPARATOR);
define('ADMIN_LIMIT_PER_PAGE', 25);

// Profile
define('PROFILE', 'profile');
define('PROFILE_GUARD', 'profile-api');
define('PROFILE_CODE_PREFIX', 'PF');
define('PROFILE_UPLOAD_PATH', UPLOAD_PATH . DIRECTORY_SEPARATOR . PROFILE . DIRECTORY_SEPARATOR);
define('PROFILE_LIMIT_PER_PAGE', 25);
define('CAREER_LIMIT_PER_PAGE', 25);
define('PROFILE_APPLICATION_LIMIT_PER_PAGE', 25);
define('PROFILE_SKILL_LIMIT_PER_PAGE', 25);

// Portfolio
define('PORTFOLIO', 'portfolio');
define('PORTFOLIO_UPLOAD_PATH', UPLOAD_PATH . DIRECTORY_SEPARATOR . PORTFOLIO . DIRECTORY_SEPARATOR);
define('PORTFOLIO_LIMIT_PER_PAGE', 25);

// Manager import
define('MANAGER', 'manager');
define('IMPORT_EMAIL_COLUMN_NUMBER', 0);
define('IMPORT_LOGS_PATH', 'import_logs' . DIRECTORY_SEPARATOR);

// Review
define('REVIEW', 'review');
define('REVIEW_UPLOAD_PATH', UPLOAD_PATH . DIRECTORY_SEPARATOR . REVIEW . DIRECTORY_SEPARATOR);
define('REVIEW_LIMIT_PER_PAGE', 25);

// Roles
define('ADMIN_ROLE_REGULAR', 'regular');
define('ADMIN_ROLE_MANAGER', 'manager');
define('ADMIN_ROLE_OPERATOR', 'operator');


//Status
define('ACCOUNT_NON_ACTIVE', '0');
define('STATUS_ACTIVED', '1');

//Social type
define('FACEBOOK','facebook');
define('GOOGLE','google');
define('TWITTER','twitter');
define('MICROSOFT','uuid');
define('EMAIL','email');

//URL config
define('GRAPH_URL_MICROSOFT','https://graph.microsoft.com/v1.0/me/');
define('GRAPH_URL_GOOGLE','https://www.googleapis.com/oauth2/v3/userinfo');
define('GRAPH_URL_TWITTER','https://www.googleapis.com/oauth2/v1/tokeninfo');
define('GRAPH_URL_FACEBOOK','https://graph.facebook.com/v5.0/me');

//Domain name
define('DOMAIN_CMS','https://localhost:8080');

define('EXPIRE_CREATE_PASSWORD',45);

// HTTP STATUS
define('STATUS_NOT_FOUND',404);

// OBJECT PROFILE
define('OBJECT_TYPE_SKILL','skill');
define('OBJECT_TYPE_COURSES','courses');
define('OBJECT_TYPE_PROJECT','project');
define('OBJECT_TYPE_CLUB','club');
define('OBJECT_TYPE_PUBLICATION','publication');
define('OBJECT_TYPE_EDUACTION','eduaction');
define('OBJECT_TYPE_CERTIFICATES','certificate');
define('OBJECT_TYPE_AWARDS','award');
define('OBJECT_TYPE_PORTFOLIOS','portfolios');
define('OBJECT_TYPE_LANGUAGES','language');
define('OBJECT_TYPE_INSTRODUCTION','instroduction');
define('OBJECT_TYPE_AMBITION','ambition');

//Group Show
define('GROUP_PERMISSION_PRIVATE', 'private');
define('GROUP_PERMISSION_PUBLIC', 'public');
define('GROUP_PERMISSION_ONLY_RECRUITER', 'recruiter');
define('GROUP_PERMISSION_CONNECTION', 'connection');
define('GROUP_PERMISSION_TWO_CONNECTION', 'two_connections');
define('GROUP_PERMISSION_ONLY_WANTEDLY_USER', 'only_wantedly_user');