<?php

namespace Backpack\CRUD\app\Library\CrudPanel\Traits;

use Backpack\CRUD\app\Exceptions\AccessDeniedException;
use Illuminate\Database\Eloquent\Model;

trait Access
{
    /**
     * Set an operation as having access using the Settings API.
     */
    public function allowAccess(array|string $operation): bool
    {
        foreach ((array) $operation as $op) {
            $this->set($op.'.access', true);
        }

        return $this->hasAccessToAll($operation);
    }

    /**
     * Disable the access to a certain operation, or the current one.
     */
    public function denyAccess(array|string $operation): bool
    {
        foreach ((array) $operation as $op) {
            $this->set($op.'.access', false);
        }

        return ! $this->hasAccessToAny($operation);
    }

    /**
     * Check if a operation is allowed for a Crud Panel. Return false if not.
     */
    public function hasAccess(string $operation, $entry = null): bool
    {
        $condition = $this->get($operation.'.access');

        if (is_callable($condition)) {
            // supply the current entry, if $entry is missing
            // this also makes sure the entry is null when missing
            $entry ??= $this->getCurrentEntry() ?: null;

            return $condition($entry);
        }

        return $condition ?? false;
    }

    /**
     * Check if any operations are allowed for a Crud Panel. Return false if not.
     */
    public function hasAccessToAny(array|string $operation_array, ?Model $entry = null): bool
    {
        foreach ((array) $operation_array as $key => $operation) {
            if ($this->hasAccess($operation, $entry) == true) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if all operations are allowed for a Crud Panel. Return false if not.
     */
    public function hasAccessToAll(array|string $operation_array, ?Model $entry = null): bool
    {
        foreach ((array) $operation_array as $key => $operation) {
            if (! $this->hasAccess($operation, $entry)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check if a operation is allowed for a Crud Panel. Fail if not.
     *
     * @throws \Backpack\CRUD\Exception\AccessDeniedException in case the operation is not enabled
     */
    public function hasAccessOrFail(string $operation, ?Model $entry = null): bool
    {
        if (! $this->hasAccess($operation, $entry)) {
            throw new AccessDeniedException(trans('backpack::crud.unauthorized_access', ['access' => $operation]));
        }

        return true;
    }

    /**
     * Get an operation's access condition, if set. A condition
     * can be anything, but usually a boolean or a callable.
     */
    public function getAccessCondition(string $operation): bool|callable|null
    {
        return $this->get($operation.'.access');
    }

    /**
     * Set the condition under which an operation is allowed for a Crud Panel.
     */
    public function setAccessCondition(array|string $operation, bool|callable|null $condition): void
    {
        foreach ((array) $operation as $op) {
            $this->set($op.'.access', $condition);
        }
    }

    /**
     * Check if an operation has an access condition already set.
     * A condition can be anything, but usually a boolean or a callable.
     */
    public function hasAccessCondition(string $operation): bool
    {
        return $this->get($operation.'.access') !== null;
    }

    /**
     * Remove the access to all available operations.
     */
    public function denyAllAccess(): void
    {
        $this->denyAccess($this->getAvailableOperationsList());
    }

    /**
     * Allow access only to operations in the array.
     * By denying access to all other operations.
     */
    public function allowAccessOnlyTo(array|string $operation): void
    {
        $this->denyAllAccess();
        $this->allowAccess($operation);
    }
}
