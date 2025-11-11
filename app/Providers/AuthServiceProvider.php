<?php

namespace App\Providers;

 use Illuminate\Support\Facades\Gate;
 use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('is-big-boss', function ($user) {
            return $user->id == 1;
        });

        Gate::define('owner-or-user-task', function ($user, $task) {
            return $user->is_admin || $user->id == $task->owner->id || $user->id == $task->user->id;
        });

        Gate::define('owner-task', function ($user, $task) {
            return $user->is_admin || $user->id == $task->owner->id;
        });

        Gate::define('owner-or-user-message', function ($user, $message) {
            return $user->is_admin || $user->id == $message->owner->id || $user->id == $message->user->id;
        });

        Gate::define('owner-message', function ($user, $message) {
            return $user->is_admin || $user->id == $message->owner->id;
        });

        Gate::define('owner-or-user-message-not-admin', function ($user, $message) {
            return $user->id == $message->owner->id || $user->id == $message->user->id;
        });

        Gate::define('owner-message-not-admin', function ($user, $message) {
            return $user->id == $message->owner->id;
        });

        Gate::define('user-message-not-admin', function ($user, $message) {
            return $user->id == $message->user->id;
        });

        Gate::define('user-edit', function ($user, $editingUser) {
            return $user->is_admin || $user->id == $editingUser->id;
        });

        Gate::define('customer-edit', function ($user, $customer) {
            return $user->is_admin || $customer->type < 4;
        });

        Gate::define('check-rights', function ($user, Model $model, $field) {
            return ($user->is_admin || $model[$field] == $user->id);
        });

        Gate::define('is-admin', function ($user) {
            return $user->is_admin;
        });
    }
}
