<?php

namespace Doefom\RestrictFields\Tests\Feature;

use Doefom\RestrictFields\Listeners\RestrictFields;
use Doefom\RestrictFields\Tests\TestCase;
use Statamic\Events\EntryBlueprintFound;

class RestrictVisibilityHiddenTest extends TestCase
{

    /** @test */
    public function is_field_hidden_for_user_a()
    {
        $this->actingAs($this->userA);

        $event = $this->dispatchEventEntryA();

        // Assert there are no fields with the handle 'rating'
        $this->assertFalse(
            $event->blueprint->fields()->items()->some(fn(array $item) => $item['handle'] === 'rating')
        );
    }

    /** @test */
    public function is_field_not_hidden_for_user_b()
    {
        $this->actingAs($this->userB);

        $event = $this->dispatchEventEntryA();

        // Assert there still is a field with the handle 'rating'
        $this->assertTrue(
            $event->blueprint->fields()->items()->some(fn(array $item) => $item['handle'] === 'rating')
        );
    }

    /** @test */
    public function is_field_not_hidden_for_user_c()
    {
        $this->actingAs($this->userC);

        $event = $this->dispatchEventEntryA();

        // Assert there still is a field with the handle 'rating'
        $this->assertTrue(
            $event->blueprint->fields()->items()->some(fn(array $item) => $item['handle'] === 'rating')
        );
    }

    private function dispatchEventEntryA()
    {
        $event = new EntryBlueprintFound($this->blueprintA, $this->entryAUserA);
        (new RestrictFields())->handle($event);

        return $event;
    }

}
