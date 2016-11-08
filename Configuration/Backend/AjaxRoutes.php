<?php

/**
 * Definitions for routes provided by EXT:be_acl
 */
return [
    // Dispatch the permissions actions
    'user_access_permissions' => [
        'path' => '/users/access/permissions',
        'target' => \Tx\BeAcl\Controller\PermissionAjaxController::class . '::dispatch'
    ]
];
