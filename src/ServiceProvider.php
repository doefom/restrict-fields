<?php

namespace Doefom\RestrictFields;

use Doefom\RestrictFields\Listeners\RestrictFields;
use Statamic\Events\EntryBlueprintFound;
use Statamic\Facades\Role;
use Statamic\Fieldtypes\Text;
use Statamic\Providers\AddonServiceProvider;

class ServiceProvider extends AddonServiceProvider
{

    protected $listen = [
        EntryBlueprintFound::class => [RestrictFields::class]
    ];

    public function bootAddon()
    {

        $roles = Role::all()->map(fn(\Statamic\Contracts\Auth\Role $role) => $role->handle());

//        Text::appendConfigField('restrict_visibility', [
//            'type' => 'select',
//            'display' => 'Restrict Visibility',
//            'options' => ['read_only', 'hidden'],
//            'multiple' => false,
//            'clearable' => true,
//            'width' => 33
//        ]);
//
//        Text::appendConfigField('restrict_for_roles', [
//            'type' => 'select',
//            'display' => 'Restrict for Roles',
//            'options' => $roles,
//            'multiple' => true,
//            'clearable' => true,
//            'width' => 66
//        ]);

        Text::appendConfigField('restrictions', [
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
        ]);

    }
}
