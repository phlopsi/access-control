<?php
declare(strict_types=1);

namespace Phlopsi\AccessControl\Repository;

use Phlopsi\AccessControl\Entity\Role;

/**
 * @author Patrick Fischer <nbphobos@gmail.com>
 */
interface RoleRepository extends Repository
{
    public function create(string $permission_id): Role;
    public function delete(Role $permission);
    public function retrieve(string $permission_id): Role;
    public function retrieveList(): array;
}
