<?php

namespace Tests\Unit;

use Final2\App\Http\Requests\Comment\CreateRequest;
use Final2\App\Services\CommentService;
use Illuminate\Container\Container;
use Tests\TestCase;

class CreateCommentTest extends TestCase
{
    public function test_main()
    {
        $commentService = new CommentService;
        $newCommentData =
            [
                'task_id' => '1',
                'user_id' => '1',
                'body' => 'Test data from unit',
            ];
        $request = CreateRequest::create('/comments', 'POST', $newCommentData);
        $request->setContainer(Container::getInstance());
        $request->setRedirector(app('redirect'));
        $request->validateResolved();
        $result = $commentService->create($request);
        $this->assertEquals($result->getStatusCode(), 201);
        $this->assertEquals($result->getContent(), '');
    }
}
