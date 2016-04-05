<?php
declare(strict_types=1);

namespace Phlopsi\AccessControl\Repository;

use Phlopsi\AccessControl\Entity\Permission;
use Phlopsi\AccessControl\Exception\LengthException;
use Phlopsi\AccessControl\Propel\Base\BasePermissionRepository;
use Phlopsi\AccessControl\Propel\Map\PermissionEntityMap;

/**
 * @author Patrick Fischer <nbphobos@gmail.com>
 */
class DefaultPermissionRepository implements PermissionRepository
{
    /**
     * @var \Phlopsi\AccessControl\Propel\Base\BasePermissionRepository
     */
    private $repository;

    /**
     * @param \Phlopsi\AccessControl\Propel\Base\BasePermissionRepository $repository
     *
     * @codeCoverageIgnore
     */
    public function __construct(BasePermissionRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Creates a new permission in the database
     *
     * It will throw an exception if the permission already exists or if it couldn't be created.
     * It will return a Permission object, if the permission was successfully created.
     *
     * @param string $permission_id
     *
     * @return \Phlopsi\AccessControl\Entity\Permission
     */
    public function create(string $permission_id): Permission
    {
        if (0 === \strlen($permission_id)) {
            throw new LengthException(LengthException::ARGUMENT_IS_EMPTY_STRING);
        }

        $new_permission = $this->repository->createObject();
        $new_permission->setExternalId($permission_id);

        $this->repository->save($new_permission);

        return new Permission($new_permission);
    }

    /**
     * Deletes an existing permission from the database
     *
     * @param \Phlopsi\AccessControl\Entity\Permission $permission
     */
    public function delete(Permission $permission)
    {
        $propel_permission = $permission->getInternalObject();
        $this->repository->remove($propel_permission);
    }

    /**
     * Retrieves an existing permission from the database
     *
     * It will throw an exception if the permission could not be found.
     * It will return a Permission object, if the permission was successfully retrieved.
     *
     * @param string $permission_id
     *
     * @return \Phlopsi\AccessControl\Entity\Permission
     */
    public function retrieve(string $permission_id): Permission
    {
        if (0 === \strlen($permission_id)) {
            throw new LengthException(LengthException::ARGUMENT_IS_EMPTY_STRING);
        }

        $permission = $this->repository->createQuery()
            ->requireOneByExternalId($permission_id);

        return new Permission($permission);
    }

    /**
     * Retrieves a list of all existing permissions from the database
     *
     * @return string[]
     */
    public function retrieveList(): array
    {
        return $this->repository->createQuery()
            ->select(PermissionEntityMap::COL_EXTERNALID)
            ->find()
            ->getData();
    }
}
