<?php

namespace Doefom\RestrictFields\Listeners;

use Statamic\Events\EntryBlueprintFound;
use Statamic\Facades\User;
use Statamic\Support\Arr;

class RestrictFields
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param EntryBlueprintFound $event
     * @return void
     */
    public function handle(EntryBlueprintFound $event)
    {
        $items = $event->blueprint->fields()->items();
        foreach ($items as $item) {
            // Check if field has 'hide_for_roles' config
            $hideForRoles = Arr::get($item, 'field.hide_for_roles');
            if (!$hideForRoles) {
                continue;
            }

            // Check if field should be hidden for the current user
            $hideForCurrentUser = collect($hideForRoles)
                ->contains(function (string $role) {
                    return User::current()->hasRole($role);
                });
            if (!$hideForCurrentUser) {
                continue;
            }

            // If field should be hidden, remove from the blueprint.
            $event->blueprint->removeField(Arr::get($item, 'handle'));
        }
    }
}
