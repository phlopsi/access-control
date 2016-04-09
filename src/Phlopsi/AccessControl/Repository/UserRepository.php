<?php
declare(strict_types=1);

namespace Phlopsi\AccessControl\Repository;

use Phlopsi\AccessControl\Entity\User;

/**
 * @author Patrick Fischer <nbphobos@gmail.com>
 */
interface UserRepository extends Repository
{
    public function create(string $permission_id): User;
    public function delete(User $permission);
    public function retrieve(string $permission_id): User;
    public function retrieveList(): array;
}
