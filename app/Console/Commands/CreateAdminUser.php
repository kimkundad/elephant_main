<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

/**
 * Creates or updates a back office login.
 *
 * The admin screens can only list users, and the site has no password reset
 * flow, so this is how staff accounts are handed out and how a forgotten
 * password is replaced.
 */
class CreateAdminUser extends Command
{
    protected $signature = 'admin:user
        {email : The address they sign in with}
        {--name= : Display name, defaults to the part before the @}
        {--role=admin : admin or superAdmin}
        {--password= : Set this password instead of generating one}';

    protected $description = 'Create a back office user, or update the role and password of an existing one';

    public function handle(): int
    {
        $email = strtolower(trim((string) $this->argument('email')));
        $role = (string) $this->option('role');

        $validator = Validator::make(
            ['email' => $email, 'role' => $role],
            [
                'email' => ['required', 'email', 'max:255'],
                'role' => ['required', Rule::in(['admin', 'superAdmin'])],
            ]
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $message) {
                $this->error($message);
            }

            return self::FAILURE;
        }

        $password = (string) ($this->option('password') ?: Str::password(16, symbols: false));
        $name = (string) ($this->option('name') ?: Str::of(Str::before($email, '@'))->replace(['.', '_', '-'], ' ')->title());

        $user = User::firstWhere('email', $email);
        $existed = $user !== null;

        if ($existed) {
            $user->forceFill([
                'name' => $this->option('name') ? $name : $user->name,
                'password' => Hash::make($password),
            ])->save();
        } else {
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
            ]);
        }

        $user->syncRoles([Role::firstOrCreate(['name' => $role, 'guard_name' => 'web'])]);

        $this->info($existed ? "Updated {$email}" : "Created {$email}");
        $this->table(
            ['Name', 'Email', 'Role', 'Password'],
            [[$user->name, $user->email, $role, $password]]
        );
        $this->warn('The password is shown once. Pass it on over a private channel.');

        return self::SUCCESS;
    }
}
