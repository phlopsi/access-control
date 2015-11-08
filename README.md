[![Build Status](https://travis-ci.org/phlopsi/access-control.svg?branch=0.1.0)](https://travis-ci.org/phlopsi/access-control)
[![Coverage Status](https://coveralls.io/repos/phlopsi/access-control/badge.svg?branch=0.1.0&service=github)](https://coveralls.io/github/phlopsi/access-control?branch=0.1.0)

# Phlopsi's AccessControl API
## How to create a ...
### ... user?
```php
$access_control = new AccessControl();
$user = $access_control->createUser('phlopsi');
```

### ... role?
```php
$access_control = new AccessControl();
$role = $access_control->createRole('admin');
```

### ... permission?
```php
$access_control = new AccessControl();
$access_control->createPermission('user_management/view');
```

## How to delete a ...
### ... user?
```php
$access_control = new AccessControl();
$access_control->deleteUser('phlopsi');
```

### ... role?
```php
$access_control = new AccessControl();
$access_control->deleteRole('admin');
```

### ... permission?
```php
$access_control = new AccessControl();
$access_control->deletePermission('user_management/view');
```

## How to add a permission to a role?
```php
$access_control = new AccessControl();
$role = $access_control->retrieveRole('admin');
$role->addPermission('user_management/view');
```

### How to remove a permission from a role?
```php
$access_control = new AccessControl();
$role = $access_control->retrieveRole('admin');
$role->removePermission('user_management/view');
```

## How to add a role to a user?
```php
$access_control = new AccessControl();
$user = $access_control->retrieveUser('phlopsi');
$user->addRole('admin');
```

### How to remove a role from a user?
```php
$access_control = new AccessControl();
$user = $access_control->retrieveUser('phlopsi');
$user->removeRole('admin');
```

## How to check, if a user has a permission?
```php
$access_control = new AccessControl();
$user = $access_control->retrieveUser('phlopsi');
$has_permission = $user->hasPermission('user_management/view');

if ($has_permission) {
    // Do stuff, that needs permission
} else {
    // Prompt login, output error message, etc.
}
```
