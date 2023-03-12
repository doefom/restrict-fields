<?php

namespace Doefom\RestrictFields;

use Doefom\RestrictFields\Listeners\RestrictFields;
use Statamic\Events\EntryBlueprintFound;
use Statamic\Facades\Role;
use Statamic\Providers\AddonServiceProvider;

class ServiceProvider extends AddonServiceProvider
{
    protected $publishAfterInstall = false;

    protected $listen = [
        EntryBlueprintFound::class => [RestrictFields::class]
    ];

    public function register()
    {
        $this->registerConfig();
    }

    public function bootAddon()
    {
        $fieldTypes = config('restrict_fields.field_types');

        $roles = Role::all()->map(fn(\Statamic\Contracts\Auth\Role $role) => $role->handle());
        $configField = [
            'collapse' => false,
            'previews' => true,
            'sets' => [
                'restriction' => [
                    'display' => 'restriction',
                    'fields' => [
                        [
                            'handle' => 'restrict_visibility',
                            'field' => [
                                'options' => [
                                    'read_only' => 'Read Only',
                                    'hidden' => 'Hidden',
                                ],
                                'multiple' => false,
                                'max_items' => 1,
                                'clearable' => true,
                                'searchable' => false,
                                'taggable' => false,
                                'push_tags' => false,
                                'cast_booleans' => false,
                                'display' => 'Restrict Visibility',
                                'type' => 'select',
                                'icon' => 'select',
                                'instructions' => 'Select how you would like to restrict the visibility of this field for certain roles.',
                                'width' => 50,
                                'listable' => 'hidden',
                                'instructions_position' => 'above',
                                'visibility' => 'visible',
                                'required' => true,
                            ],
                        ],
                        [
                            'handle' => 'restrict_for_roles',
                            'field' => [
                                'options' => $roles, // TODO: Use handle and label for nicer display options
                                'multiple' => true,
                                'clearable' => true,
                                'searchable' => true,
                                'taggable' => false,
                                'push_tags' => false,
                                'cast_booleans' => false,
                                'display' => 'Restrict for Roles',
                                'type' => 'select',
                                'icon' => 'select',
                                'instructions' => 'The roles to apply the restriction to.',
                                'width' => 50,
                                'listable' => 'hidden',
                                'instructions_position' => 'above',
                                'visibility' => 'visible',
                                'required' => true,
                            ],
                        ],
                    ],
                ],
            ],
            'display' => 'Restrictions',
            'type' => 'replicator',
            'icon' => 'replicator',
            'listable' => 'hidden',
            'instructions_position' => 'above',
            'visibility' => 'visible',
        ];
        $configFieldHandle = 'restrictions';

        foreach ($fieldTypes as $fieldType) {
            $fieldType::appendConfigField($configFieldHandle, $configField);
        }
    }

    private function registerConfig()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/restrict_fields.php', 'restrict_fields');

        $this->publishes([
            __DIR__ . '/../config/restrict_fields.php' => config_path('restrict_fields.php'),
        ], 'restrict-fields');
    }

}
