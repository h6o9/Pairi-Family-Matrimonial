<?php

namespace App\Providers;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Model::automaticallyEagerLoadRelationships();

        $setting = (object) [
            'favicon' => 'backend/img/favicon.png',
            'default_avatar' => 'backend/img/avatar.png',
            'timezone' => config('app.timezone'),
            'admin_login_prefix' => 'admin',
            'version' => '1.0.0',
            'is_queueable' => 'inactive',
            'last_update_date' => now()->format('Y-m-d'),
        ];

        config(['custom.admin_login_prefix' => 'admin']);
        View::share('setting', $setting);

        $this->registerBladeDirectives();
        Paginator::useBootstrapFour();
        $this->setPaginationForCollection();
        view()->share('nonce', base64_encode(random_bytes(16)));

        View::composer('*', function ($view) {
            $adminUser = auth()->guard('admin')->user();
            $view->with('adminUser', $adminUser);
            $view->with('isAdminLoggedIn', !is_null($adminUser));
        });
    }

    protected function setPaginationForCollection(): void
    {
        Collection::macro('paginate', function ($perPage = 16, $total = null, $page = null, $pageName = 'page'): LengthAwarePaginator {
            $page = $page ?: LengthAwarePaginator::resolveCurrentPage($pageName);

            return new LengthAwarePaginator(
                $this->forPage($page, $perPage)->values(),
                $total ?: $this->count(),
                $perPage,
                $page,
                [
                    'path' => LengthAwarePaginator::resolveCurrentPath(),
                    'pageName' => $pageName,
                ]
            );
        });
    }

    protected function registerBladeDirectives(): void
    {
        Blade::directive('adminCan', function ($permission) {
            return "<?php 
                \$adminUser = auth()->guard('admin')->user();
                if(\$adminUser && method_exists(\$adminUser, 'can') && \$adminUser->can({$permission})):
            ?>";
        });

        Blade::directive('endadminCan', fn () => '<?php endif; ?>');
    }
}
