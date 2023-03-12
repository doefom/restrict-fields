<?php

namespace Doefom\RestrictFields\Tests\Feature;

use Doefom\RestrictFields\Tests\TestCase;

class RestrictVisibilityHiddenTest extends TestCase
{

    /** @test */
    public function is_field_hidden_for_user_a()
    {
        $this->actingAs($this->userA);

        $event = $this->dispatchEventEntryA();

        // Assert there is no field 'test_field'
        $this->assertNull($event->blueprint->field('test_field'));
    }

    /** @test */
    public function is_field_not_hidden_for_user_b()
    {
        $this->actingAs($this->userB);

        $event = $this->dispatchEventEntryA();

        // Assert there still is a field 'test_field'
        $this->assertNotNull($event->blueprint->field('test_field'));
    }

    /** @test */
    public function is_field_not_hidden_for_user_c()
    {
        $this->actingAs($this->userC);

        $event = $this->dispatchEventEntryA();

        // Assert there still is a field 'test_field'
        $this->assertNotNull($event->blueprint->field('test_field'));
    }

}
