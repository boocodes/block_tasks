<?php


namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Controllers\TaskController;
use App\Http\Requests\Comment\CreateRequest;
use App\Services\CommentService;

class CreateCommentTest extends TestCase
{
    public function testMain()
    {
        $commentService = new CommentService();
        $newCommentData =
            [
                'task_id' => 1,
                'user_id' => 1,
                'body' => 'Test data from unit'
            ];
        $request = new CreateRequest($newCommentData);
        $result = $commentService->create($request);
        $this->assertEquals($result->getStatusCode(), 201);
        $this->assertEquals($result->getContent(), '');
    }
}
