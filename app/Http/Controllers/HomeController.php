<?php

namespace App\Http\Controllers;

use App\Task;
use Illuminate\Http\Request;

/**
 * @property Task task
 */
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->task = new Task();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    /**Show the overview view*/
    public function overview()
    {
        return view('overview');
    }

    /**Save a new task*/
    public function saveTask()
    {
        if (!request()->ajax()) {
            abort(404);
        }

        request()->validate([
            'task_description' => 'required',
            'duration' => 'required'
        ]);

        $data = [
            'user_id' => auth()->id(),
            'task_description' => request('task_description'),
            'duration' => request('duration'),
            'created_at' => request('created_at')
        ];

        if ($this->task->addTask($data)) {
            return response('Successfully added task', 200);
        } else
            return response('Error while saving task', 400);
    }

    /**Get all saved tasks*/
    public function getSavedTasks()
    {
        if (!request()->ajax())
            abort(404);
        if (is_null(request()->dates) || array_sum(request()->dates) === 0)
            return $this->task->getTasks();
        else {
            $dates = request()->dates;
            $data = [
                'from' => date('Y-m-d H:i:s', strtotime($dates[0])),
                'to' => date('Y-m-d H:i:s', strtotime($dates[1]))
            ];
            return $this->task->getTasks($data);
        }
    }

    public function deleteTask()
    {
        if (!request()->ajax()) {
            abort(404);
        }

        return ($this->task->deleteTaskByID(request('id'))) ?
            response('Successfully deleted task!', 200) :
            response('Nothing to delete!', 500);
    }
}
