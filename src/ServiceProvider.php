<?php

namespace Doefom\RestrictFields;

use Doefom\RestrictFields\Listeners\RestrictFields;
use Statamic\Events\EntryBlueprintFound;
use Statamic\Facades\Role;
use Statamic\Forms\Form;
use Statamic\Providers\AddonServiceProvider;

class ServiceProvider extends AddonServiceProvider
{

    protected $listen = [
        EntryBlueprintFound::class => [RestrictFields::class]
    ];

    public function bootAddon()
    {
        // TODO: Make field types configurable in config.
        $fieldTypes = [
            \Statamic\Fieldtypes\Arr::class,
            \Statamic\Fieldtypes\Assets\Assets::class,
            \Statamic\Fieldtypes\Bard::class,
            \Statamic\Fieldtypes\ButtonGroup::class,
            \Statamic\Fieldtypes\Checkboxes::class,
            \Statamic\Fieldtypes\Code::class,
            \Statamic\Fieldtypes\Collections::class,
            \Statamic\Fieldtypes\Color::class,
            \Statamic\Fieldtypes\Date::class,
            \Statamic\Fieldtypes\Entries::class,
            // TODO: \Statamic\Fieldtypes\Form::class,
            \Statamic\Fieldtypes\Grid::class,
            \Statamic\Fieldtypes\Hidden::class,
            \Statamic\Fieldtypes\Html::class,
            \Statamic\Fieldtypes\Integer::class,
            \Statamic\Fieldtypes\Link::class,
            \Statamic\Fieldtypes\Lists::class,
            \Statamic\Fieldtypes\Markdown::class,
            \Statamic\Fieldtypes\Radio::class,
            \Statamic\Fieldtypes\Range::class,
            \Statamic\Fieldtypes\Replicator::class,
            \Statamic\Fieldtypes\Revealer::class,
            \Statamic\Fieldtypes\Section::class,
            \Statamic\Fieldtypes\Select::class,
            \Statamic\Fieldtypes\Sites::class,
            \Statamic\Fieldtypes\Slug::class,
            \Statamic\Fieldtypes\Structures::class,
            \Statamic\Fieldtypes\Table::class,
            // TODO: \Statamic\Fieldtypes\Tags::class,
            \Statamic\Fieldtypes\Taxonomies::class,
            \Statamic\Fieldtypes\Template::class,
            \Statamic\Fieldtypes\Terms::class,
            \Statamic\Fieldtypes\Text::class,
            \Statamic\Fieldtypes\Textarea::class,
            \Statamic\Fieldtypes\Time::class,
            \Statamic\Fieldtypes\Toggle::class,
            \Statamic\Fieldtypes\UserGroups::class,
            \Statamic\Fieldtypes\UserRoles::class,
            \Statamic\Fieldtypes\Users::class,
            \Statamic\Fieldtypes\Video::class,
            \Statamic\Fieldtypes\Yaml::class,
        ];

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
}
