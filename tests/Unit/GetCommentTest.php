<?php 


namespace Tests\Unit;

use App\Http\Requests\Comment\CreateRequest;
use Tests\TestCase;
use Illuminate\Http\Request;
use App\Repositories\CommentRepository;
use App\Http\Resources\Comment\CommentResource;

class GetCommentTest extends TestCase
{
    public function testMain()
    {
       $commentRepository = new CommentRepository();
       $request = new Request();
       $comment = $commentRepository->get($request, 1);
       $this->assertInstanceOf(CommentResource::class, $comment);
    }
}