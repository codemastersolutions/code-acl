<?php

use CodeMaster\CodeAcl\Http\Controllers\RolesController;
use CodeMaster\CodeAcl\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

Route::get(UsersController::URL_PERMISSIONS, [UsersController::class, 'permissions'])->name('users.permissions');
Route::post(UsersController::URL_PERMISSIONS, [UsersController::class, 'givePermissions'])->name('users.give-permissions');
Route::delete(UsersController::URL_PERMISSIONS, [UsersController::class, 'revokePermissions'])->name('users.revoke-permissions');

Route::get(UsersController::URL_MODULES, [UsersController::class, 'modules'])->name('users.modules');
Route::post(UsersController::URL_MODULES, [UsersController::class, 'giveModules'])->name('users.give-modules');
Route::delete(UsersController::URL_MODULES, [UsersController::class, 'revokeModules'])->name('users.revoke-modules');

Route::get(UsersController::URL_SYSTEMS, [UsersController::class, 'systems'])->name('users.systems');
Route::post(UsersController::URL_SYSTEMS, [UsersController::class, 'giveSystems'])->name('users.give-systems');
Route::delete(UsersController::URL_SYSTEMS, [UsersController::class, 'revokeSystems'])->name('users.revoke-systems');

Route::get(UsersController::URL_ROLES, [UsersController::class, 'roles'])->name('users.roles');
Route::post(UsersController::URL_ROLES, [UsersController::class, 'giveRoles'])->name('users.give-roles');
Route::delete(UsersController::URL_ROLES, [UsersController::class, 'revokeRoles'])->name('users.revoke-roles');

Route::get(RolesController::URL_PERMISSIONS, [RolesController::class, 'permissions'])->name('roles.permissions');
Route::post(RolesController::URL_PERMISSIONS, [RolesController::class, 'givePermissions'])->name('roles.give-permissions');
Route::delete(RolesController::URL_PERMISSIONS, [RolesController::class, 'revokePermissions'])->name('roles.revoke-permissions');

Route::resource('modules', \ModulesController::class)->except(['create', 'edit']);
Route::resource('permissions', \PermissionsController::class)->except(['create', 'edit']);
Route::resource('roles', \RolesController::class)->except(['create', 'edit']);
Route::resource('systems', \SystemsController::class)->except(['create', 'edit']);
