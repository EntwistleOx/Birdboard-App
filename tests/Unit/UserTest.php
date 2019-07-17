<?php

namespace Tests\Unit;

use App\User;
use Tests\TestCase;
use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function a_user_has_projects()
    {
        $user = factory(User::class)->create();
        $this->assertInstanceOf(Collection::class, $user->projects);
    }

    /** @test */
    public function a_user_has_accesible_projects()
    {
        $john = $this->signIn();
        ProjectFactory::ownedBy($john)->create();

        $this->assertCount(1, $john->accesibleProjects());

        $sabrina = factory(User::class)->create();
        $nick = factory(User::class)->create();

        ProjectFactory::ownedBy($sabrina)->create()->invite($nick);

        $this->assertCount(1, $john->accesibleProjects());

        ProjectFactory::ownedBy($sabrina)->create()->invite($john);

        $this->assertCount(2, $john->accesibleProjects());

    }


}

