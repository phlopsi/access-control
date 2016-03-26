<?php
declare(strict_types=1);

namespace Phlopsi\AccessControl\Repository\Proxy;

use Phlopsi\AccessControl\Repository\Repository;
use Phlopsi\ExceptionTranslator\FunctionWrapper;

/**
 * @author Patrick Fischer <nbphobos@gmail.com>
 */
class ExceptionTranslatorRepositoryProxy implements Repository
{
    private $repository;
    private $functionWrapper;

    public function __construct(Repository $repository, FunctionWrapper $functionWrapper)
    {
        $this->repository = $repository;
        $this->functionWrapper = $functionWrapper;
    }

    public function create($entity_id)
    {
        return $this->functionWrapper(function () use ($entity_id) {
            return $this->repository->create($entity_id);
        });
    }

    public function delete($entity)
    {
        $this->functionWrapper(function () use ($entity) {
            $this->repository->delete($entity);
        });
    }

    public function retrieve($entity_id)
    {
        return $this->functionWrapper(function () use ($entity_id) {
            return $this->repository->retrieve($entity_id);
        });
    }

    public function retrieveList()
    {
        return $this->functionWrapper(function () {
            return $this->repository->retrieveList();
        });
    }
}
