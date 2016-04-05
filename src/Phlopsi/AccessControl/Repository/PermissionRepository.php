<?php
declare(strict_types=1);

namespace Phlopsi\AccessControl\Repository;

/**
 * @author Patrick Fischer <nbphobos@gmail.com>
 */
interface PermissionRepository
{
    public function create(string $permission_id): Permission;
    public function delete(Permission $permission);
    public function retrieve(string $permission_id): Permission;
    public function retrieveList(): array;
}
