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

use org\bitbucket\phlopsi\access_control\propel\RoleQuery as PropelRoleQuery;
use org\bitbucket\phlopsi\access_control\propel\SessionType as PropelSessionType;

class SessionType
{
    private $session_type;

    public function __construct(PropelSessionType $session_type)
    {
        $this->session_type = $session_type;
    }

    public function addRole($role_id)
    {
        $role_id = (string) $role_id;

        if (empty($role_id)) {
            throw new \InvalidArgumentException('$role_id converts to an empty string!');
        }

        $role = PropelRoleQuery::create()->findOneByExternalId($role_id);

        if (is_null($role)) {
            throw new EntityNotFoundException('Role "' . $role_id . '" not found!');
        }

        $this->session_type->addRole($role);
    }

    public function removeRole($role_id)
    {
        $role_id = (string) $role_id;

        if (empty($role_id)) {
            throw new \InvalidArgumentException('$role_id converts to an empty string!');
        }

        $role = PropelRoleQuery::create()->findOneByExternalId($role_id);

        if (is_null($role)) {
            throw new EntityNotFoundException('Role "' . $role_id . '" not found!');
        }

        $this->session_type->removeRole($role);
    }

}
