<?php

namespace Tests\Setup;

use App\Task;
use App\User;
use App\Project;

class ProjectFactory
{
    protected $taskCounts = 0;
    protected $user;

    public function create()
    {
        $project = factory(Project::class)->create([
            'owner_id' => $this->user ?? factory(User::class)
        ]);

        factory(Task::class, $this->taskCounts)->create([
            'project_id' => $project->id
        ]);

        return $project;
    }

    public function withTasks($count)
    {
        $this->taskCounts = $count;

        return $this;
    }

    public function ownedBy($user)
    {
        $this->user = $user;

        return $this;
    }
}


