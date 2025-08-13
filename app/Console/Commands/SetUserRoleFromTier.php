<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Webkul\User\Models\Admin; // ✅ CHANGED: Use the correct Admin model
use Illuminate\Support\Facades\Log;

class SetUserRoleFromTier extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'user:set-role-from-tier';

    /**
     * The console command description.
     */
    protected $description = "Set the first admin's role based on the SITE_TIER value in the .env file";

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $desiredRoleName = config('app.site_tier');

        if (!$desiredRoleName) {
            $this->error('SITE_TIER is not defined in your .env file or config/app.php.');
            return self::FAILURE;
        }

        $admin = \Webkul\User\Models\Admin::orderBy('id', 'asc')->first();

        if (!$admin) {
            $this->error("No admins found in the database.");
            return self::FAILURE;
        }

        // ✅ ADDED: Trim whitespace from the role name to prevent errors.
        $trimmedRoleName = trim($desiredRoleName);

        // ✅ CHANGED: Use the trimmed role name for the database query.
        $newRole = \Webkul\User\Models\Role::where('name', $trimmedRoleName)->first();

        if (!$newRole) {
            // ✅ CHANGED: Use the trimmed role name in the warning message.
            $currentRole = $admin->role;
            $this->warn("Warning: The role '{$trimmedRoleName}' was not found in the database.");
            $this->warn("The admin's role remains unchanged: '{$currentRole->name}'.");
            return self::SUCCESS;
        }

        $admin->role_id = $newRole->id;
        $admin->save();
        
        $this->info("Successfully set the admin's role to '{$newRole->name}'.");

        return self::SUCCESS;
    }
}