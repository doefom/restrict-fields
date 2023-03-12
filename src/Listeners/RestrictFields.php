<?php

namespace Doefom\RestrictFields\Listeners;

use Illuminate\Support\Collection;
use Statamic\Events\EntryBlueprintFound;
use Statamic\Facades\User;
use Statamic\Fields\Blueprint;
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
        $itemsWithRestrictionForCurrentUser = $this->getFieldsWithRestrictionsForCurrentUser($event->blueprint);
        $this->applyRestrictions($itemsWithRestrictionForCurrentUser, $event->blueprint);
    }

    private function getFieldsWithRestrictionsForCurrentUser(Blueprint $blueprint): Collection
    {
        return $blueprint->fields()->items()->filter(function (array $item) {
            return Arr::has($item, 'field.restrictions') && $this->hasRestrictionsForUser($item, User::current());
        });
    }

    private function hasRestrictionsForUser(array $item, \Statamic\Contracts\Auth\User $user): bool
    {
        $restrictions = Arr::get($item, 'field.restrictions');
        $restrictedRoles = collect($restrictions)
            ->pluck('restrict_for_roles')
            ->flatten();
        // Return true if user has at least on of the roles restricted. Else return false.
        return $restrictedRoles->some(function ($role) use ($user) {
            return $user->hasRole($role);
        });
    }

    private function applyRestrictions(Collection $items, Blueprint $blueprint)
    {
        foreach ($items as $item) {
            $restrictions = Arr::get($item, 'field.restrictions');
            foreach ($restrictions as $restriction) {
                $type = Arr::get($restriction, 'restrict_visibility');
                if ($type === 'read_only') {
                    $this->applyReadOnlyRestriction($item, $blueprint);
                }
                if ($type === 'hidden') {
                    $this->applyHiddenRestriction($item, $blueprint);
                }
            }
        }
    }

    private function applyReadOnlyRestriction(array $item, Blueprint $blueprint)
    {
        // TODO: Continue here. Seems like code runs up to this point
        return;
    }

    private function applyHiddenRestriction(array $item, Blueprint $blueprint)
    {
        $blueprint->removeField(Arr::get($item, 'handle'));
    }

}
