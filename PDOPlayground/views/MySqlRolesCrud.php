<?php
// Define Roles/Permissions/Actions
$adminRole = new \DarlingCms\classes\privilege\Role(
    'Administrator',
    [
        new \DarlingCms\classes\privilege\Permission(
            'Manage Apps',
            [
                new \DarlingCms\classes\privilege\Action('Enable Apps', 'Turn apps on.'),
                new \DarlingCms\classes\privilege\Action('Disable Apps', 'Turn apps off.'),
                new \DarlingCms\classes\privilege\Action('Create Apps', 'Create new apps.'),
                new \DarlingCms\classes\privilege\Action('Remove Apps', 'Uninstall apps.')
            ]
        ),
        new \DarlingCms\classes\privilege\Permission(
            'Manage Themes',
            [
                new \DarlingCms\classes\privilege\Action('Enable Themes', 'Turn themes on.'),
                new \DarlingCms\classes\privilege\Action('Disable Themes', 'Turn themes off.'),
                new \DarlingCms\classes\privilege\Action('Create Themes', 'Create new themes.'),
                new \DarlingCms\classes\privilege\Action('Remove Themes', 'Uninstall themes.')
            ]
        ),
        new \DarlingCms\classes\privilege\Permission(
            'Manage Js Libraries',
            [
                new \DarlingCms\classes\privilege\Action('Enable Js Libraries', 'Turn Js Libraries on.'),
                new \DarlingCms\classes\privilege\Action('Disable Js Libraries', 'Turn Js Libraries off.'),
                new \DarlingCms\classes\privilege\Action('Create Js Libraries', 'Create Js Libraries apps.'),
                new \DarlingCms\classes\privilege\Action('Remove Js Libraries', 'Uninstall Js Libraries.')
            ]
        ),
        new \DarlingCms\classes\privilege\Permission(
            'Manage Public Site Content',
            [
                new \DarlingCms\classes\privilege\Action('Create Public Site Content', 'Create Public Site Content'),
                new \DarlingCms\classes\privilege\Action('Read Public Site Content', 'Read Public Site Content'),
                new \DarlingCms\classes\privilege\Action('Update Public Site Content', 'Update Public Site Content'),
                new \DarlingCms\classes\privilege\Action('Delete Public Site Content', 'Delete Public Site Content')
            ]
        ),
        new \DarlingCms\classes\privilege\Permission(
            'Manage Private Site Content',
            [
                new \DarlingCms\classes\privilege\Action('Create Private Site Content', 'Create Private Site Content'),
                new \DarlingCms\classes\privilege\Action('Read Private Site Content', 'Read Private Site Content'),
                new \DarlingCms\classes\privilege\Action('Update Private Site Content', 'Update Private Site Content'),
                new \DarlingCms\classes\privilege\Action('Delete Private Site Content', 'Delete Private Site Content')
            ]
        ),
        new \DarlingCms\classes\privilege\Permission(
            'Manage Own Content',
            [
                new \DarlingCms\classes\privilege\Action('Create Own Content', 'Create Own Content'),
                new \DarlingCms\classes\privilege\Action('Read Own Content', 'Read Own Content'),
                new \DarlingCms\classes\privilege\Action('Update Own Content', 'Update Own Content'),
                new \DarlingCms\classes\privilege\Action('Delete Own Content', 'Delete Own Content')
            ]
        ),
        new \DarlingCms\classes\privilege\Permission(
            'Manage Roles',
            [
                new \DarlingCms\classes\privilege\Action('Create Roles', 'Create Roles'),
                new \DarlingCms\classes\privilege\Action('Read Roles', 'Read Roles'),
                new \DarlingCms\classes\privilege\Action('Update Roles', 'Update Roles'),
                new \DarlingCms\classes\privilege\Action('Delete Roles', 'Delete Roles')
            ]
        ),
        new \DarlingCms\classes\privilege\Permission(
            'Manage Permissions',
            [
                new \DarlingCms\classes\privilege\Action('Create Permissions', 'Create Permissions'),
                new \DarlingCms\classes\privilege\Action('Read Permissions', 'Read Permissions'),
                new \DarlingCms\classes\privilege\Action('Update Permissions', 'Update Permissions'),
                new \DarlingCms\classes\privilege\Action('Delete Permissions', 'Delete Permissions')
            ]
        ),
        new \DarlingCms\classes\privilege\Permission(
            'Manage Actions',
            [
                new \DarlingCms\classes\privilege\Action('Create Actions', 'Create Actions'),
                new \DarlingCms\classes\privilege\Action('Read Actions', 'Read Actions'),
                new \DarlingCms\classes\privilege\Action('Update Actions', 'Update Actions'),
                new \DarlingCms\classes\privilege\Action('Delete Actions', 'Delete Actions')
            ]
        ),
        new \DarlingCms\classes\privilege\Permission(
            'Manage Users',
            [
                new \DarlingCms\classes\privilege\Action('Create Users', 'Create Users'),
                new \DarlingCms\classes\privilege\Action('Read Users', 'Read Users'),
                new \DarlingCms\classes\privilege\Action('Update Users', 'Update Users'),
                new \DarlingCms\classes\privilege\Action('Delete Users', 'Delete Users')
            ]
        )
    ]
);
$registeredUserRole = new \DarlingCms\classes\privilege\Role(
    'Registered User',
    [
        new \DarlingCms\classes\privilege\Permission(
            'View Public Site Content',
            [new \DarlingCms\classes\privilege\Action('Read Public Site Content', 'Read Public Site Content')]
        ),
        new \DarlingCms\classes\privilege\Permission(
            'View Private Site Content',
            [new \DarlingCms\classes\privilege\Action('Read Private Site Content', 'Read Private Site Content')]
        ),
        new \DarlingCms\classes\privilege\Permission(
            'Manage Own Content',
            [
                new \DarlingCms\classes\privilege\Action('Create Own Content', 'Create Own Content'),
                new \DarlingCms\classes\privilege\Action('Read Own Content', 'Read Own Content'),
                new \DarlingCms\classes\privilege\Action('Update Own Content', 'Update Own Content'),
                new \DarlingCms\classes\privilege\Action('Delete Own Content', 'Delete Own Content')
            ]
        )
    ]
);

$basicUserRole = new \DarlingCms\classes\privilege\Role(
    'Basic User',
    [new \DarlingCms\classes\privilege\Permission(
        'View Public Site Content',
        [new \DarlingCms\classes\privilege\Action('Read Public Site Content', 'Read Public Site Content')]
    )]
);

// Create Roles
$roleCrud->create($adminRole);
$roleCrud->create($registeredUserRole);
$roleCrud->create($basicUserRole);

/*
echo '<h3>Roles:</h3>';
echo '<div style="' . $style . '">' . str_replace($ser, $rep, print_r($roleCrud->read($adminRole->getRoleName()), true)) . '</div>';
echo '<div style="' . $style . '">' . str_replace($ser, $rep, print_r($roleCrud->read($registeredUserRole->getRoleName()), true)) . '</div>';
echo '<div style="' . $style . '">' . str_replace($ser, $rep, print_r($roleCrud->read($basicUserRole->getRoleName()), true)) . '</div>';
*/

// Create Permissions
foreach ($adminRole->getPermissions() as $permission) {
    $permissionCrud->create($permission);
}
foreach ($basicUserRole->getPermissions() as $permission) {
    $permissionCrud->create($permission);
}
foreach ($registeredUserRole->getPermissions() as $permission) {
    $permissionCrud->create($permission);
}

// Create Actions
foreach ($adminRole->getPermissions() as $permission) {
    foreach ($permission->getActions() as $action) {
        $actionCrud->create($action);
    }
}
foreach ($basicUserRole->getPermissions() as $permission) {
    foreach ($permission->getActions() as $action) {
        $actionCrud->create($action);
    }
}
foreach ($registeredUserRole->getPermissions() as $permission) {
    foreach ($permission->getActions() as $action) {
        $actionCrud->create($action);
    }
}
