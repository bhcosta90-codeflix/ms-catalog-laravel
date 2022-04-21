<?php

namespace Tests\Feature\App\Repositories\Eloquent;

use App\Models\CastMember as Model;
use App\Repositories\Eloquent\CastMemberRepository as Repository;
use App\Repositories\Eloquent\CastMemberRepository;
use Costa\Core\Modules\CastMember\Entities\CastMember as Entity;
use Costa\Core\Modules\CastMember\Repositories\CastMemberRepositoryInterface as RepositoryInterface;
use Costa\Core\Utils\Exceptions\NotFoundDomainException;
use Costa\Core\Utils\Contracts\PaginationInterface;
use Tests\TestCase;

class CastMemberEloquentRepositoryTest extends TestCase
{
    protected $repository;

    protected function setUp(): void
    {
        parent::setUp();

        /** @var \App\Repositories\Eloquent\CastMemberRepository */
        $this->repository = new Repository(new Model());
    }

    public function testImplementInterfaceRepository()
    {
        $this->assertInstanceOf(CastMemberRepository::class, $this->repository);
    }
}
