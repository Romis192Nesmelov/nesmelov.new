<?php

namespace App\Providers;

 use App\Models\Customer;
 use App\Models\Message;
 use App\Models\SubTask;
 use App\Models\Task;
 use App\Models\User;
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

        Gate::define('is-big-boss', function (User $user) {
            return $user->id == 1;
        });

        Gate::define('owner-or-user-task', function (User $user, Task|SubTask $task) {
            return $user->is_admin || $user->id == $task->owner->id || $user->id == $task->user->id;
        });

        Gate::define('owner-task', function (User $user, Task $task) {
            return $user->is_admin || $user->id == $task->owner->id;
        });

        Gate::define('owner-or-user-message', function (User $user, Message $message) {
            return $user->is_admin || $user->id == $message->owner->id || $user->id == $message->user->id;
        });

        Gate::define('owner-message', function (User $user, Message $message) {
            return $user->is_admin || $user->id == $message->owner->id;
        });

        Gate::define('owner-or-user-message-not-admin', function (User $user, Message $message) {
            return $user->id == $message->owner->id || $user->id == $message->user->id;
        });

        Gate::define('owner-message-not-admin', function (User $user, $message) {
            return $user->id == $message->owner->id;
        });

        Gate::define('user-message-not-admin', function (User $user, $message) {
            return $user->id == $message->user->id;
        });

        Gate::define('user-edit', function (User $user, User $editingUser) {
            return $user->is_admin || $user->id == $editingUser->id;
        });

        Gate::define('customer-edit', function (User $user, Customer $customer) {
            return $user->is_admin || $customer->type < 4;
        });

        Gate::define('check-rights', function (User $user, Model $model, string $field) {
            return ($user->is_admin || $model[$field] == $user->id);
        });

        Gate::define('is-admin', function (User $user) {
            return $user->is_admin;
        });
    }
}
