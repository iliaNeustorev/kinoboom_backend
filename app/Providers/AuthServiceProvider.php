<?php

namespace App\Providers;

use App\Enums\Roles\Status as RoleStatus;
use App\Models\Comment as ModelsComment;
use Carbon\Carbon;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Auth\Notifications\ResetPassword;


class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        ResetPassword::createUrlUsing(function ($user, string $token) {
            return 'http://localhost:8080/reset-password?token='.$token;
        });

        VerifyEmail::toMailUsing(function ($notifiable, $url) {
          $spaUrl = "http://localhost:8080/verifyEmail?email_verify_url=".$url;
            return (new MailMessage)
                ->subject('Verify Email Address')
                ->line('Click the button below to verify your email address.')
                ->action('Verify Email Address', $spaUrl);
        });

        Gate::define('comment-update', function($user, ModelsComment $comment){
            return $user->id === $comment->user_id;
        });

        Gate::define('comment-timeout', function($user, ModelsComment $comment, int $hours){
            $checkingDate = $comment->created_at;
            $currentDate = Carbon::now();
            $difference = $checkingDate->diffInHours($currentDate);
            return $difference < $hours;
        });

        Gate::define('admin', function($user){
            return !$user->roles()->where('name', RoleStatus::USER)->count();
        });

        Gate::define('admin-main', function($user){
            return $user->roles()->where('name', RoleStatus::ADMIN)->count() > 0;
        });

        Gate::define('admin-moderator', function($user){
            return $user->roles()->where('name', RoleStatus::MODERATOR)->count() > 0;
        });
    }
}
