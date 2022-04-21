<?php

namespace Tests\Unit\App\Http\Controllers\Api;

use PHPUnit\Framework\TestCase;

use App\Http\Controllers\Api\CategoryController;
use Costa\Core\Modules\Category\UseCases\ListCategoryUseCase;
use Costa\Core\Modules\Category\UseCases\DTO\List\Output;
use Illuminate\Http\Request;
use Mockery;

class CategoryControllerTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_index()
    {
        $mockRequest = Mockery::mock(Request::class);
        $mockRequest->shouldReceive('get')->andReturn('teste');
        $mockRequest->shouldReceive('all')->andReturn([]);

        $mockOutput = Mockery::mock(Output::class, [
            [], 1, 1, 1, 1, 1, 1, 1
        ]);

        $mockUsecase = Mockery::mock(ListCategoryUseCase::class);
        $mockUsecase->shouldReceive('execute')->andReturn($mockOutput);

        $controller = new CategoryController();
        $response = $controller->index($mockRequest, $mockUsecase);

        $this->assertIsObject($response->resource);
        $this->assertArrayHasKey('meta', $response->additional);

        $mockSpy = Mockery::spy(ListCategoryUseCase::class);
        $mockSpy->shouldReceive('execute')->andReturn($mockOutput);
        $controller->index($mockRequest, $mockSpy);

        $mockSpy->shouldHaveReceived('execute');

        Mockery::close();
    }
}
