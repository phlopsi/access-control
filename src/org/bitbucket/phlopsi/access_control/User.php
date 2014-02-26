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
use org\bitbucket\phlopsi\access_control\propel\User as PropelUser;

class User
{
    private $user;

    public function __construct(PropelUser $user)
    {
        $this->user = $user;
    }

    public function hasPermission($permission_id)
    {
        $permission_id = (string) $permission_id;

        if (empty($permission_id)) {
            throw new \InvalidArgumentException('$permission converts to an empty string!');
        }

        $roles = $this->user->getRoles();

        //TODO more efficiency!
        foreach ($roles as $role) {
            $permission = PropelPermissionQuery::create()
                ->filterByRole($role)
                ->findByExternalId($permission_id);

            if (!is_null($permission)) {
                return true;
            }

            $descendant_roles = $role->getDescendants();

            foreach ($descendant_roles as $descendant_role) {
                $permission = PropelPermissionQuery::create()
                    ->filterByRole($descendant_role)
                    ->findByExternalId($permission_id);

                if (!is_null($permission)) {
                    return true;
                }
            }
        }

        return false;
    }

}
