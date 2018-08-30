<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['user_id', 'task_description', 'duration', 'created_at', 'updated_at'];

    /**Create new task or update if exists*/
    public function addTask($data)
    {
        /**
         * updateOrCreate checks if there's a record with the passed user_id and task_id. If there is, the record is updated,
         * otherwise a new record is created with the passed data.
         */
        return $this->updateOrCreate(
            [
                'user_id' => $data['user_id'],
                'id' => $data['task_id']
            ],
            [
                'task_description' => $data['task_description'],
                'duration' => $data['duration'],
                'created_at' => ($data['created_at']) ?
                    date('Y-m-d H:i:s', strtotime($data['created_at'])) :
                    date('Y-m-d H:i:s')
            ])->save();
    }

    /**If the data array is not empty (it's populated with dates) then return the tasks between the two dates*/
    /**If the data array is empty then return all tasks*/
    public function getTasks($data = [])
    {
        if (count($data) > 0) {
            $tasks = $this->where('user_id', auth()->id())
                ->where('created_at', '>=', $data['from'])
                ->where('created_at', '<=', $data['to'])
                ->get()->toArray();

        } else {
            $tasks = $this->where('user_id', auth()->id())->get()->toArray();
        }

        if (count($tasks) > 0)
            return $tasks;
        else
            return [];
    }

    /**Deleting task by its ID*/
    public function deleteTaskByID($id)
    {
        if ($this->destroy(json_decode($id)) > 0) {
            return true;
        } else
            return false;
    }
}
