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

        // One field...
        Text::appendConfigField('hide_for_roles', [
            'type' => 'select',
            'display' => 'Hide for Roles',
            'options' => $roles,
            'multiple' => true,
            'clearable' => true,
        ]);

    }
}
