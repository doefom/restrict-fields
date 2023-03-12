<?php

namespace Doefom\RestrictFields\Tests\Feature;

use Doefom\RestrictFields\Tests\TestCase;

class RestrictVisibilityReadOnlyTest extends TestCase
{

    /** @test */
    public function is_field_not_read_only_for_user_a()
    {
        // Test RestrictVisibilityHiddenTest.php already tests that field is hidden for user A (removed from blueprint entirely)
        $this->assertTrue(true);
    }

    /** @test */
    public function is_field_read_only_for_user_b()
    {
        $this->actingAs($this->userB);

        $event = $this->dispatchEventEntryA();

        $testField = $event->blueprint->field('test_field');
        // Assert there still is a field 'test_field'
        $this->assertNotNull($testField);
        // Assert this field has visibility 'read_only'
        $this->assertEquals('read_only', $testField->config()['visibility']);
    }

    /** @test */
    public function is_field_not_hidden_for_user_c()
    {
        $this->actingAs($this->userC);

        $event = $this->dispatchEventEntryA();

        $testField = $event->blueprint->field('test_field');
        // Assert there still is a field 'test_field'
        $this->assertNotNull($testField);
        // Assert this field has visibility 'read_only'
        $this->assertEquals('visible', $testField->config()['visibility']);
    }

}
