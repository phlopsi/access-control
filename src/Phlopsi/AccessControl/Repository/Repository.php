<?php
declare(strict_types=1);

namespace Phlopsi\AccessControl\Repository;

/**
 * @author Patrick Fischer <nbphobos@gmail.com>
 */
interface Repository
{
    public function create(string $entity_id);
    public function delete($entity);
    public function retrieve(string $entity_id);
    public function retrieveList(): array;
}
