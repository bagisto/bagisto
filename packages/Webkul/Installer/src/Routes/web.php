<?php

use Illuminate\Support\Facades\Route;

Route::get('install', [Webkul\Installer\Http\Controllers\InstallerController::class, 'index'])->name('installer.index');