<?php 


namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Http\Request;
use App\Repositories\CommentRepository;

class GetCommentTest extends TestCase
{
    public function testMain()
    {
       $commentRepository = new CommentRepository();
       $request = new Request();
       $comment = $commentRepository->get($request, 1);
       $this->assertIsArray($comment);
    }
}