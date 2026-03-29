<?php


namespace Tests\Unit;

use Tests\TestCase;
use App\Services\ProjectService;
use App\Http\Requests\Project\CreateRequest;
use Illuminate\Container\Container;

class CreateProjectTest extends TestCase
{
    public function testMain()
    {
        $projectService = new ProjectService();
        $newProjectDataData =
            [
                'name' => '1',
                'owner_id' => '1',
            ];
        $request = CreateRequest::create('/projects', 'POST', $newProjectDataData);
        $request->setContainer(Container::getInstance());
        $request->setRedirector(app('redirect'));
        $request->validateResolved();
        $result = $projectService->create($request);
        $this->assertEquals($result->getStatusCode(), 201);
        $this->assertEquals($result->getContent(), '');
    }
}
