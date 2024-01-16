<?php

namespace App\Providers;

use App\Actions\Jetstream\DeleteUser;
use App\Models\usuarios;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;
use Laravel\Jetstream\Jetstream;

class JetstreamServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::loginView(function () {
            return view('inicioSesion');
        });

        Fortify::authenticateUsing(function (Request $request) {
            try {
                $user = usuarios::where('usuario', $request->usuario)->first();                
                if ($user && Hash::check($request->password, $user->password)) {
                    return $user;
                } else {
                    return false;
                }
            } catch (Exception $e) {
                dd($e);
            }
        });

        $this->configurePermissions();

        Jetstream::deleteUsersUsing(DeleteUser::class);
    }

    /**
     * Configure the permissions that are available within the application.
     */
    protected function configurePermissions(): void
    {
        Jetstream::defaultApiTokenPermissions(['read']);

        Jetstream::permissions([
            'create',
            'read',
            'update',
            'delete',
        ]);
    }
    
}
