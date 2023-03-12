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
            $restrictions = $this->getRestrictionsForCurrentUser($item);
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
        // Use the contents clone to modify contents and later override original contents
        $contents = $blueprint->contents();

        // Loop through all sections to get the correct section handle
        foreach ($blueprint->sections()->keys() as $sectionKey) {
            if (!$blueprint->hasFieldInSection(Arr::get($item, 'handle'), $sectionKey)) {
                continue;
            }
            // Loop through all fields in section to override the restricted field's visibility
            foreach ($contents['sections'][$sectionKey]['fields'] as &$field) {
                if (Arr::get($field, 'handle') === Arr::get($item, 'handle')) {
                    $field['field']['visibility'] = 'read_only';
                }
            }
        }

        // Override modified contents in blueprint
        $blueprint->setContents($contents);
    }

    private function applyHiddenRestriction(array $item, Blueprint $blueprint)
    {
        $blueprint->removeField(Arr::get($item, 'handle'));
    }

    private function getRestrictionsForCurrentUser(array $item): Collection
    {
        $restrictions = collect(Arr::get($item, 'field.restrictions'));
        return $restrictions->filter(function (array $restriction) {
            $restrictForRoles = Arr::get($restriction, 'restrict_for_roles');
            return collect($restrictForRoles)->some(fn($role) => User::current()->hasRole($role));
        });
    }

}
