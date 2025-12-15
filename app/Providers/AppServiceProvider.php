<?php

namespace App\Providers;

use App\Events\EventType;
use App\Listeners\Moderation\ModerationAcceptedListener;
use App\Listeners\Moderation\ModerationDeclinedListener;
use App\Listeners\Post\CreatePostListener;
use App\Listeners\Post\DeletePostListener;
use App\Listeners\Post\PostLikedListener;
use App\Listeners\Post\PostUpdatedListener;
use Illuminate\Support\ServiceProvider;
use App\Services\EventNotifierService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(EventNotifierService::class, function ($app) {
            return EventNotifierService::getInstance();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(EventNotifierService $notifier): void
    {
        $notifier->add(EventType::CREATE_POST, [new CreatePostListener(), 'handle']);
        $notifier->add(EventType::UPDATE_POST, [new PostUpdatedListener(), 'handle']);
        $notifier->add(EventType::DELETE_POST, [new DeletePostListener(), 'handle']);
        $notifier->add(EventType::LIKE_POST, [new PostLikedListener(), 'handle']);

        $notifier->add(EventType::ACCEPT_MODERATION, [new ModerationAcceptedListener(), 'handle']);
        $notifier->add(EventType::DECLINE_MODERATION, [new ModerationDeclinedListener(), 'handle']);
    }
}
