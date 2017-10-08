<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class Todo extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->todo_title,
            'status' => $this->todo_status == 'P' ? 'Pending' : 'Done'
        ];
    }
}
