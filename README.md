[![PHP 7.0](https://img.shields.io/badge/PHP-7.0-8080c0.svg)](https://secure.php.net/)
[![Build Status](https://travis-ci.org/phlopsi/access-control.svg?branch=0.1.0)](https://travis-ci.org/phlopsi/access-control)
[![Coverage Status](https://coveralls.io/repos/phlopsi/access-control/badge.svg?branch=0.1.0&service=github)](https://coveralls.io/github/phlopsi/access-control?branch=0.1.0)

# Phlopsi's AccessControl API
## Usage Examples
### How to create a user/role/permission?
```php
$access_control = new \Phlopsi\AccessControl\AccessControl();

$user = $access_control->createUser('phlopsi');
$role = $access_control->createRole('admin');

$access_control->createPermission('user_management/view');
```

### How to delete a user/role/permission?
```php
$access_control = new \Phlopsi\AccessControl\AccessControl();

$access_control->deleteUser('phlopsi');
$access_control->deleteRole('admin');
$access_control->deletePermission('user_management/view');
```

### How to add/remove a permission to/from a role?
```php
$access_control = new \Phlopsi\AccessControl\AccessControl();

$role = $access_control->retrieveRole('admin');

$role->addPermission('user_management/view');
$role->removePermission('user_management/view');
```

### How to add/remove a user to/from a role?
```php
$access_control = new \Phlopsi\AccessControl\AccessControl();

$role = $access_control->retrieveRole('admin');

$role->addUser('phlopsi');
$role->removeUser('phlopsi');
```

### How to check, if a user has a permission?
```php
$access_control = new \Phlopsi\AccessControl\AccessControl();

$user           = $access_control->retrieveUser('phlopsi');
$has_permission = $user->hasPermission('user_management/view');

if ($has_permission) {
    // Do stuff, that needs permission
} else {
    // Prompt login, redirect, output error message, etc.
}
```

### How to get a list of all permissions/roles/users?
```php
$access_control = new \Phlopsi\AccessControl\AccessControl();

$permissions = $access_control->retrievePermissionList();
$roles       = $access_control->retrieveRoleList();
$users       = $access_control->retrieveUserList();
```
