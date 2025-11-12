<?php

namespace App\Livewire\Concerns;

use Illuminate\Support\Facades\Auth;

trait WithRoleCheck
{
    /**
     * Check if user has specific permission
     */
    public function hasPermission($permission)
    {
        return Auth::user()->can($permission);
    }

    /**
     * Check if user has specific role
     */
    public function hasRole($role)
    {
        return Auth::user()->hasRole($role);
    }

    /**
     * Check if user is Direksi (read-only access)
     */
    public function isDireksi()
    {
        return $this->hasRole('direksi');
    }

    /**
     * Check if user is Superadmin (full access)
     */
    public function isSuperadmin()
    {
        return $this->hasRole('superadmin');
    }

    /**
     * Check if user can edit data
     */
    public function canEdit()
    {
        return !$this->isDireksi();
    }

    /**
     * Check if user can delete data
     */
    public function canDelete()
    {
        return !$this->isDireksi();
    }

    /**
     * Check if user can export data
     */
    public function canExport()
    {
        return $this->hasPermission('export-data');
    }

    /**
     * Abort if user doesn't have permission
     */
    protected function authorizePermission($permission)
    {
        if (!$this->hasPermission($permission)) {
            abort(403, 'Anda tidak memiliki akses untuk melakukan aksi ini.');
        }
    }

    /**
     * Abort if user cannot edit
     */
    protected function authorizeEdit()
    {
        if (!$this->canEdit()) {
            abort(403, 'Direksi tidak memiliki akses untuk mengubah data.');
        }
    }

    /**
     * Abort if user cannot delete
     */
    protected function authorizeDelete()
    {
        if (!$this->canDelete()) {
            abort(403, 'Direksi tidak memiliki akses untuk menghapus data.');
        }
    }

    /**
     * Abort if user cannot view
     */
    protected function authorizeView()
    {
        $this->authorizePermission('view-data');
    }

    /**
     * Mount method to check permissions
     */
    public function mountWithRoleCheck()
    {
        // Check if user can view data (all users can view)
        $this->authorizePermission('view-data');
    }
}
