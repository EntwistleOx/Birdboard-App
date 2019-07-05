<?php

namespace Tests\Feature;

use Tests\TestCase;
use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TriggerActivityTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function creating_a_project()
    {
        #$this->withoutExceptionHandling();
        $project = ProjectFactory::create();
        $this->assertCount(1, $project->activity);
        $this->assertEquals('created', $project->activity->first()->description);
    }

    /** @test */
    public function updating_a_project()
    {
        $project = ProjectFactory::create();
        $project->update(['title' => 'changed']);
        $this->assertCount(2, $project->activity);
        $this->assertEquals('created', $project->activity->first()->description);
        $this->assertEquals('updated', $project->activity->last()->description);
    }

    /** @test */
    public function creating_a_new_task()
    {
        #$this->withoutExceptionHandling();
        $project = ProjectFactory::withTasks(1)->create();
        $this->assertCount(2, $project->activity);
        $this->assertEquals('created_task', $project->activity->last()->description);
    }

    /** @test */
    public function completing_a_task()
    {
        #$this->withoutExceptionHandling();
        $project = ProjectFactory::withTasks(1)->create();

        $this->actingAs($project->owner)
            ->patch($project->tasks->first()->path(),[
                'body' => 'changed',
                'completed' => true
            ]);

        #dd($project->fresh()->activity->toArray());
        $this->assertCount(3, $project->activity);
        $this->assertEquals('created', $project->activity->first()->description);
        $this->assertEquals('completed_task', $project->activity->last()->description);
    }

    /** @test */
    public function incompleting_a_task()
    {
        #$this->withoutExceptionHandling();
        $project = ProjectFactory::withTasks(1)->create();

        $this->actingAs($project->owner)
            ->patch($project->tasks->first()->path(),[
                'body' => 'changed',
                'completed' => true
            ]);

        #dd($project->fresh()->activity->toArray());
        $this->assertCount(3, $project->activity);

        $this->patch($project->tasks->first()->path(),[
                 'body' => 'changed',
                 'completed' => false
             ]);

        $project->refresh();
        dd($project->fresh()->activity->toArray());
        $this->assertCount(3, $project->activity);
        $this->assertEquals('created', $project->activity->first()->description);
        $this->assertEquals('incompleted_task', $project->activity->last()->description);
    }

    /** @test */
    public function deleting_a_task()
    {
        $project = ProjectFactory::withTasks(1)->create();
        $project->activity->first()->delete();
        $this->assertCount(3, $project->activity);
    }


}
