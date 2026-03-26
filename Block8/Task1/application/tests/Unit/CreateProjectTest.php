<?php


namespace Tests\Unit;

use Tests\TestCase;
use App\Services\ProjectService;
use App\Http\Requests\Project\CreateRequest;


class CreateProjectTest extends TestCase
{
    public function testMain()
    {
        $projectService = new ProjectService();
        $newCommentData =
            [
                'name' => 1,
                'owner_id' => 1,
            ];
        $request = new CreateRequest($newCommentData);
        $result = $projectService->create($request);
        $this->assertEquals($result->getStatusCode(), 201);
        $this->assertEquals($result->getContent(), '');
    }
}
