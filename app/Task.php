<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['user_id', 'task_description', 'duration', 'created_at', 'updated_at'];


    public function addTask($data)
    {
        return $this->updateOrCreate(
            [
                'user_id' => $data['user_id'],
                'id' => $data['id']
            ],
            [
                'task_description' => $data['task_description'],
                'duration' => $data['duration'],
                'created_at' => ($data['created_at']) ? date('Y-m-d H:i:s', strtotime($data['created_at'])) : date('Y-m-d H:i:s')
            ])->save();
    }

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
            return 0;
    }

    public function deleteTaskByID($id)
    {
        if ($this->destroy(json_decode($id)) > 0) {
            return true;
        } else
            return false;
    }
}
