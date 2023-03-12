<?php

namespace Doefom\RestrictFields\Tests\Feature;

use Doefom\RestrictFields\Tests\TestCase;

class BootTest extends TestCase
{

    /** @test */
    public function is_applied_to_all_field_types()
    {
        $fieldTypes = collect(config('restrict_fields.field_types'));
        $isAppliedToAllFieldTypes = $fieldTypes->every(function ($fieldType) {
            $ft = new $fieldType();
            // Every field must have one item with the handle 'restrictions'
            return $ft->configFields()->items()->some(fn($item) => $item['handle'] === 'restrictions');
        });

        $this->assertTrue($isAppliedToAllFieldTypes);
    }
}
