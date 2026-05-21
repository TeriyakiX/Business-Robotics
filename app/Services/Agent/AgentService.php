<?php

declare(strict_types=1);

namespace App\Services\Agent;

use App\DTOs\Agent\AgentCreateDto;
use App\DTOs\Agent\AgentListDto;
use App\DTOs\Agent\AgentUpdateDto;
use App\Exceptions\Agent\AgentCreateForbiddenException;
use App\Exceptions\Agent\AgentDeleteForbiddenException;
use App\Exceptions\Agent\AgentNotFoundException;
use App\Exceptions\Agent\AgentUpdateForbiddenException;
use App\Models\Agent;
use App\Repositories\AgentRepository;
use App\Validators\AgentValidator;
use Illuminate\Database\Eloquent\Collection;

final readonly class AgentService
{
    public function __construct(
        private AgentRepository $repository,
        private AgentValidator $validator,
    ) {}

    public function list(AgentListDto $dto): Collection
    {
        return $this->repository->list($dto);
    }

    /**
     * @throws AgentNotFoundException
     */
    public function item(string $id): Agent
    {
        $agent = $this->repository->item($id);
        $this->validator->validateAgentExists($agent);

        return $agent;
    }

    /**
     * @throws AgentCreateForbiddenException
     */
    public function create(AgentCreateDto $dto): Agent
    {
        $this->validator->validateCreateData($dto->toArray());

        return $this->repository->create($dto);
    }

    /**
     * @throws AgentNotFoundException|AgentUpdateForbiddenException
     */
    public function update(string $id, AgentUpdateDto $dto): Agent
    {
        $agent = $this->repository->item($id);
        $this->validator->validateAgentExists($agent);

        $this->validator->validateUpdateData($dto->toArray());

        $updated = $this->repository->update($agent, $dto);

        if (!$updated) {
            throw new AgentUpdateForbiddenException();
        }

        return $updated;
    }

    /**
     * @throws AgentNotFoundException|AgentDeleteForbiddenException
     */
    public function delete(string $id): bool
    {
        $agent = $this->repository->item($id);
        $this->validator->validateAgentExists($agent);

        return $this->repository->delete($agent);
    }

    /**
     * @throws AgentNotFoundException
     */
    public function restore(string $id): bool
    {
        $agent = $this->repository->item($id);
        $this->validator->validateAgentExists($agent);

        return $this->repository->restore($agent);
    }
}
