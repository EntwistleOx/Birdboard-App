<?php

namespace Tests\Feature;

use App\Project;
use Tests\TestCase;
use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ManageProjectsTest extends TestCase
{

    use WithFaker, RefreshDatabase;

    /** @test */
    public function guest_cannot_manage_projects()
    {
        $project = ProjectFactory::create();
        $this->get('/projects')->assertRedirect('login');
        $this->get('/projects/create')->assertRedirect('login');
        $this->get($project->path().'/edit')->assertRedirect('login');
        $this->get($project->path())->assertRedirect('login');
        $this->post('/projects', $project->toArray())->assertRedirect('login');
    }

    /** @test */
    public function a_user_can_create_a_project()
    {
        $user = $this->signIn();
        $this->get('/projects/create')->assertStatus(200);

        $attributes = factory(Project::class)->raw(['owner_id' => $user]);

        $this->followingRedirects()->post('/projects', $attributes)
        ->assertSee($attributes['title'])
        ->assertSee($attributes['description'])
        ->assertSee($attributes['notes']);

        $this->assertDatabaseHas('projects', $attributes);
    }

    /** @test */
    public function a_user_can_see_all_projects_they_have_been_invited_to_on_their_dashboard()
    {
        $project = tap(ProjectFactory::create())->invite($this->signIn());
        $this->get('/projects')
            ->assertSee($project->title);

    }


    /** @test */
    public function a_user_can_delete_a_project()
    {
        $this->withoutExceptionHandling();

        $project = ProjectFactory::create();

        $this->actingAs($project->owner)
             ->delete($project->path())
             ->assertRedirect('/projects');

        $this->assertDatabaseMissing('projects', $project->only('id'));
    }

    /** @test */
    public function guest_cannot_delete_a_project()
    {

        $project = ProjectFactory::create();

        $this->delete($project->path())
             ->assertRedirect('/login');

        $user = $this->signIn();

        $this->delete($project->path())
             ->assertStatus(403);

        $project->invite($user);

        $this->actingAs($user)
             ->delete($project->path())
             ->assertStatus(403);

    }


    /** @test */
    public function a_user_can_update_a_project()
    {
        $this->withoutExceptionHandling();
        $project = ProjectFactory::create();
        $attributes = [
            'title' => 'changed',
            'description' => 'changed',
            'notes' => 'Changed'
        ];
        $this->actingAs($project->owner)
             ->patch($project->path(),$attributes)
             ->assertRedirect($project->path());

        $this->get($project->path().'/edit')->assertStatus(200);
        $this->assertDatabaseHas('projects', $attributes);
    }

    /** @test */
    public function a_user_can_update_projects_notes()
    {
        $project = ProjectFactory::create();
        $attributes = [
            'notes' => 'Changed'
        ];
        $this->actingAs($project->owner)
             ->patch($project->path(),$attributes);

        $this->assertDatabaseHas('projects', $attributes);
    }



    /** @test */
    public function a_user_can_view_their_project()
    {
        $project = ProjectFactory::create();
        $this->actingAs($project->owner)
             ->get($project->path())
             ->assertSee($project->title)
             ->assertSee($project->description);
    }

    /** @test */
    public function a_user_cannot_view_others_project()
    {
        $this->signIn();
        $project = ProjectFactory::create();
        $this->get($project->path())->assertStatus(403);
    }

    /** @test */
    public function a_user_cannot_update_others_project()
    {
        $this->signIn();
        $project = ProjectFactory::create();
        $this->patch($project->path())->assertStatus(403);
    }

    /** @test */
    public function a_project_requires_a_title()
    {
        $this->signIn();
        $attributes = factory(Project::class)->raw(['title' => '']);
        $this->post('/projects', $attributes)->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_project_requires_a_description()
    {
        $this->signIn();
        $attributes = factory(Project::class)->raw(['description' => '']);
        $this->post('/projects', $attributes)->assertSessionHasErrors('description');
    }



}
