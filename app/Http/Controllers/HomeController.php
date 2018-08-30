<?php

namespace App\Http\Controllers;

use App\Task;

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

    /**Saving a new task*/
    public function saveTask()
    {
        /**If the request is not AJAX then abort the request*/
        if (!request()->ajax()) {
            abort(404);
        }

        /**Validation of the mandatory fields*/
        request()->validate([
            'task_description' => 'required',
            'duration' => 'required'
        ]);

        /**Preparing the data for insert.*/
        $data = [
            'user_id' => auth()->id(),
            'task_description' => request('task_description'),
            'duration' => request('duration'),
            'created_at' => request('created_at'),
            'task_id' => (isset(request()->task_id)) ? request('task_id') : null
        ];

        /**Returning a response based on whether the request was successful or not.*/
        if ($this->task->addTask($data)) {
            return response('Successfully added/edited task', 200);
        } else
            return response('Error while saving task', 500);
    }

    /**Get all saved tasks*/
    public function getSavedTasks()
    {
        /**If the request is not AJAX then abort the request*/
        if (!request()->ajax())
            abort(404);

        /**If the tasks are not filtered by dates then return all tasks*/
        /**Else return the tasks filtered by the passed dates*/
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
        /**If the request is not AJAX then abort the request*/
        if (!request()->ajax()) {
            abort(404);
        }

        /**Returning a response whether the deletion was successful or not.*/
        return ($this->task->deleteTaskByID(request('id'))) ?
            response('Successfully deleted task!', 200) :
            response('Nothing to delete!', 500);
    }
}
