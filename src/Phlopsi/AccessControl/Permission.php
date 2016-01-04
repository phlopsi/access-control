<?php
/**
 * @author Patrick Fischer <nbphobos@gmail.com>
 */

declare(strict_types = 1);

namespace Phlopsi\AccessControl;

use Phlopsi\AccessControl\Propel\Permission as PropelPermission;

class Permission
{
    /**
     * @var \Phlopsi\AccessControl\Propel\Permission
     */
    private $permission;

    /**
     * @param \Phlopsi\AccessControl\Propel\Permission $permission
     *
     * @codeCoverageIgnore
     */
    public function __construct(PropelPermission $permission)
    {
        $this->permission = $permission;
    }

    /**
     * @return string
     *
     * @codeCoverageIgnore
     */
    public function getId(): string
    {
        return $this->permission->getExternalId();
    }
}
