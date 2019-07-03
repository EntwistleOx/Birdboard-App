<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectTasksTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_cannot_add_tasks_to_projects()
    {
        $project = factory('App\Project')->create();
        $this->post($project->path() . '/tasks')->assertRedirect('login');
    }

    /** @test */
    public function only_the_owner_of_a_project_may_add_tasks()
    {
        $this->signIn();
        $project = factory('App\Project')->create();
        $this->post($project->path() . '/tasks', ['body' => 'Test task'])
            ->assertStatus(403);

        $this->AssertDatabaseMissing('tasks', ['body' => 'Test task']);
    }

    /** @test */
    public function only_the_owner_of_a_project_may_update_a_task()
    {
        $this->signIn();
        $project = factory('App\Project')->create();

        $task = $project->addTask('Test task');

        $this->patch($task->path(), ['body' => 'changed'])
            ->assertStatus(403);

        $this->AssertDatabaseMissing('tasks', ['body' => 'changed']);
    }


    /** @test */
    public function a_project_can_have_tasks()
    {
        $this->signIn();

        // opcion 1
        $project = factory('App\Project')->create(['owner_id' => auth()->id()]);

        // opcion 2
        #$project = auth()->user()->projects()->create(
        #    factory('App\Project')->raw()
        #);

        $this->post($project->path() . '/tasks', ['body' => 'Test task']);
        $this->get($project->path())
            ->assertSee('Test task');
    }

    /** @test */
    public function a_task_can_be_updated()
    {
        $this->withoutExceptionHandling();

        $this->signIn();

        $project = factory('App\Project')->create(['owner_id' => auth()->id()]);

        $task = $project->addTask('Test task');

        $this->patch($project->path() . '/tasks/' . $task->id,[
            'body' => 'changed',
            'completed' => true
        ]);

        $this->assertDatabaseHas('tasks', [
            'body' => 'changed',
            'completed' => true
        ]);
    }


    /** @test */
    public function a_task_requires_a_body()
    {
        $this->signIn();
        $project = factory('App\Project')->create(['owner_id' => auth()->id()]);
        $attributes = factory('App\Task')->raw(['body' => '']);
        $this->post($project->path() . '/tasks', $attributes)->assertSessionHasErrors('body');
    }

}
