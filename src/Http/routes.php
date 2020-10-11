<?php

use CodeMaster\CodeAcl\Http\Controllers\RolesController;
use CodeMaster\CodeAcl\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

Route::get('users/{user}/permissions', [UsersController::class, 'permissions'])->name('users.permissions');
Route::post('users/{user}/permissions', [UsersController::class, 'givePermissions'])->name('users.give-permissions');
Route::delete('users/{user}/permissions', [UsersController::class, 'revokePermissions'])->name('users.revoke-permissions');

Route::get('users/{user}/roles', [UsersController::class, 'roles'])->name('users.roles');
Route::post('users/{user}/roles', [UsersController::class, 'giveRoles'])->name('users.give-roles');
Route::delete('users/{user}/roles', [UsersController::class, 'revokeRoles'])->name('users.revoke-roles');

Route::get('roles/{role}/permissions', [RolesController::class, 'permissions'])->name('roles.permissions');
Route::post('roles/{role}/permissions', [RolesController::class, 'givePermissions'])->name('roles.give-permissions');
Route::delete('roles/{role}/permissions', [RolesController::class, 'revokePermissions'])->name('roles.revoke-permissions');

Route::resource('permissions', PermissionsController::class)->except(['create', 'edit']);
Route::resource('roles', \RolesController::class)->except(['create', 'edit']);
