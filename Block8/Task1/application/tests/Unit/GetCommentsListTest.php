<?php

namespace Tests\Unit;

use App\Http\Resources\Comment\CommentResource;
use App\Repositories\CommentRepository;
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
