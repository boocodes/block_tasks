<?php
namespace App\Http\Controllers;



use App\Models\Task;

class TaskController extends Controller
{
    public function create()
    {

    }

    public function get()
    {
        $res = Task::all();
        dd($res->toArray()[0]);
    }

    public function getById($id)
    {

    }

    public function update()
    {

    }

    public function delete()
    {

    }
}
