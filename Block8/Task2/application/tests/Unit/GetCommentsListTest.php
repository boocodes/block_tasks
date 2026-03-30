<?php

namespace Tests\Unit;

use Final2\App\Http\Resources\Comment\CommentResource;
use Final2\App\Repositories\CommentRepository;
use Illuminate\Http\Request;
use Tests\TestCase;

class GetCommentsListTest extends TestCase
{
    public function test_main()
    {
        $commentRepository = new CommentRepository;
        $request = new Request;
        $commentsList = $commentRepository->getAll($request);
        $this->assertInstanceOf(CommentResource::class, $commentsList[0]);
    }
}
