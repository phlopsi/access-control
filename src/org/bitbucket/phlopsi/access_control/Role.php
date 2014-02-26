<?php
/*
 * The MIT License
 *
 * Copyright 2014 Patrick Fischer.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
namespace org\bitbucket\phlopsi\access_control;

use org\bitbucket\phlopsi\access_control\propel\PermissionQuery as PropelPermissionQuery;
use org\bitbucket\phlopsi\access_control\propel\Role as PropelRole;

class Role
{
    private $role;

    public function __construct(PropelRole $role)
    {
        $this->role = $role;
    }

    public function addPermission($permission_id)
    {
        $permission_id = (string) $permission_id;

        $permission = PropelPermissionQuery::create()->findOneByExternalId($permission_id);

        if (is_null($permission)) {
            throw new EntityNotFoundException('Permission "' . $permission_id . '" not found!');
        }

        $this->role->addPermission($permission);
    }

    public function removePermission($permission_id)
    {
        $permission = PropelPermissionQuery::create()->findOneByExternalId($permission_id);

        if (is_null($permission)) {
            throw new EntityNotFoundException('Permission "' . $permission_id . '" not found!');
        }

        $this->role->removePermission($permission);
    }

    public function removeRedundantPermissions()
    {
        
    }

    public function retrievePermissions()
    {
        
    }

}
