<?php

namespace Tests\Unit;

use App\Http\Resources\Comment\CommentResource;
use App\Repositories\CommentRepository;
use Illuminate\Http\Request;
use Tests\TestCase;

class GetCommentTest extends TestCase
{
    public function test_main()
    {
        $commentRepository = new CommentRepository;
        $request = new Request;
        $comment = $commentRepository->get($request, 1);
        $this->assertInstanceOf(CommentResource::class, $comment);
    }
}
