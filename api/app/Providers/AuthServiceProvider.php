<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Http\Request;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot(): void {
        $this->registerPolicies();

        $this->app['auth']->viaRequest('token', static function (Request $request) {
            $token = $request->input('api_token');

            if (!$token) {
                $chunk = explode(' ', $request->header('Authorization'));

                if (isset($chunk[1])) {
                    $token = $chunk[1];
                }
            }


            if (!$token) {
                return NULL;
            }

            return User::where('api_token', $token)->whereRaw('`expires` > NOW()')->first();
        });
    }
}
