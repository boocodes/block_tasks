<?php 


namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Comment;
use App\Repositories\CommentRepository;
use Illuminate\Http\Request;
use App\Http\Resources\Comment\CommentResource;


class GetCommentsListTest extends TestCase
{
    public function testMain()
    {
       $commentRepository = new CommentRepository();
       $request = new Request();
       $commentsList = $commentRepository->getAll($request);
       $this->assertInstanceOf(CommentResource::class, $commentsList[0]);
    }
}