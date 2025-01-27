<?php

namespace WireUi\Providers;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\{ServiceProvider, Str};
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\View\ComponentAttributeBag;
use Livewire\WireDirective;
use WireUi\Facades\{WireUi, WireUiDirectives};
use WireUi\Support\WireUiTagCompiler;

class WireUiServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->registerConfig();
        $this->registerBladeDirectives();
        $this->registerBladeComponents();
        $this->registerTagCompiler();
        $this->registerMacros();
    }

    public function register()
    {
        $this->app->singleton('WireUi', WireUi::class);
        $loader = AliasLoader::getInstance();
        $loader->alias('WireUi', WireUi::class);
    }

    protected function registerTagCompiler()
    {
        if (method_exists($this->app['blade.compiler'], 'precompiler')) {
            $this->app['blade.compiler']->precompiler(function ($string) {
                return app(WireUiTagCompiler::class)->compile($string);
            });
        }
    }

    protected function registerConfig(): void
    {
        $rootDir = __DIR__ . '/../..';

        $this->loadViewsFrom("{$rootDir}/resources/views", 'wireui');
        $this->loadTranslationsFrom("{$rootDir}/src/lang", 'wireui');
        $this->mergeConfigFrom("{$rootDir}/src/config/wireui.php", 'wireui');
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        $this->publishes(
            ["{$rootDir}/src/config/wireui.php" => config_path('wireui.php')],
            'wireui.config'
        );

        $this->publishes(
            ["{$rootDir}/resources/views" => resource_path('views/vendor/wireui')],
            'wireui.resources'
        );

        if (is_dir(resource_path('lang'))) {
            $this->publishes(
                ["{$rootDir}/resources/lang" => resource_path('lang/vendor/wireui')],
                'wireui.lang'
            );
        }

        if (is_dir(base_path('lang'))) {
            $this->publishes(
                ["{$rootDir}/src/lang" => $this->app->langPath('vendor/wireui')],
                'wireui.lang'
            );
        }
    }

    protected function registerBladeDirectives(): void
    {
        Blade::directive('confirmAction', static function (string $expression): string {
            return WireUiDirectives::confirmAction($expression);
        });

        Blade::directive('notify', static function (string $expression): string {
            return WireUiDirectives::notify($expression);
        });

        Blade::directive('wireUiScripts', static function (): string {
            return WireUiDirectives::scripts();
        });

        Blade::directive('wireUiStyles', static function (): string {
            return WireUiDirectives::styles();
        });

        Blade::directive('boolean', static function ($value): string {
            return WireUiDirectives::boolean($value);
        });
    }

    protected function registerBladeComponents(): void
    {
        $this->callAfterResolving(BladeCompiler::class, static function (BladeCompiler $blade): void {
            foreach (config('wireui.components') as $component) {
                $blade->component($component['class'], $component['alias']);
            }
        });
    }

    protected function registerMacros(): void
    {
        ComponentAttributeBag::macro('wireModifiers', function () {
            /** @var ComponentAttributeBag $this */

            /** @var WireDirective $model */
            $model = $this->wire('model');

            return [
                'defer'    => $model->modifiers()->contains('defer'),
                'lazy'     => $model->modifiers()->contains('lazy'),
                'debounce' => [
                    'exists' => $model->modifiers()->contains('debounce'),
                    'delay'  => (string) Str::of($model->modifiers()->get(1, '750'))->replace('ms', ''),
                ],
            ];
        });
    }
}
