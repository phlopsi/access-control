<?php
declare(strict_types=1);

namespace Phlopsi\AccessControl\Repository;

/**
 * @author Patrick Fischer <nbphobos@gmail.com>
 */
interface Repository
{
    public function create($entity_id);
    public function delete($entity);
    public function retrieve($entity_id);
    public function retrieveList();
}
