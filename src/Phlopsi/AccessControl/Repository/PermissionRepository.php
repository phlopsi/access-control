<?php
declare(strict_types=1);

namespace Phlopsi\AccessControl\Repository;

use Phlopsi\AccessControl\Entity\Permission;

/**
 * @author Patrick Fischer <nbphobos@gmail.com>
 */
interface PermissionRepository extends Repository
{
    public function create(string $permission_id): Permission;
    public function delete(Permission $permission);
    public function retrieve(string $permission_id): Permission;
    public function retrieveList(): array;
}
