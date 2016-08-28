<?php

namespace Jumilla\Addomnipot\Laravel;

use Jumilla\Generators\Php\Constant;
use Jumilla\Generators\Php\ClassName;
use Jumilla\Generators\FileGenerator;

const ADDON_VERSION = 5;

class Generator
{
    public function __construct() {
        $this->DEFAULTS = [
            'namespace' => '',
            'directories' => [
                // path
            ],
            'files' => [
                // path
            ],
            'paths' => [
                // role => path
            ],
            'providers' => [
                // class
            ],
            'console' => [
                'commands' => [
                    // class
                ],
            ],
            'http' => [
                'middlewares' => [
                    // class
                ],
                'route_middlewares' => [
                    // name => class
                ],
            ],
            'routes' => [
            ],
            'includes_global_aliases' => true,
            'aliases' => [
                // name => class
            ],
        ];
    }

    /**
     * @param string $path
     * @param string $type
     * @param array  $properties
     */
    public function generateAddon($path, $type, array $properties)
    {
        $generator = FileGenerator::make($path, __DIR__.'/../stubs/'.$type);

        $method = 'generate'.studly_case($type);

        call_user_func([$this, $method], $generator, $properties);
    }

    protected function generateMinimum(FileGenerator $generator, array $properties)
    {
        $this->generateAddonConfig($generator, $properties['namespace'], [
            'namespace' => new Constant('__NAMESPACE__'),
        ]);
    }

    protected function generateSimple(FileGenerator $generator, array $properties)
    {
        $generator->directory('classes', function ($generator) use ($properties) {
            $generator->directory('Providers')
                ->file('AddonServiceProvider.php')->template('AddonServiceProvider.php', $properties);
            $generator->directory('Providers')
                ->file('RouteServiceProvider.php')->template('RouteServiceProvider.php', $properties);

            $generator->keepDirectory('Controllers');

            $generator->keepDirectory('Services');
        });

        $generator->keepDirectory('config');

        $this->generateLang($generator, $properties, function ($generator) use ($properties) {
            $generator->gitKeepFile();
        });

        $generator->keepDirectory('views');

        $generator->phpBlankFile('helpers.php');
        $generator->phpBlankFile('routes.php');

        $this->generateAddonConfig($generator, $properties['namespace'], [
            'namespace' => new Constant('__NAMESPACE__'),
            'directories' => [
                'classes',
            ],
            'files' => [
                'helpers.php',
            ],
            'paths' => [
                'config' => 'config',
                'lang' => 'lang',
                'views' => 'views',
            ],
            'providers' => [
                new ClassName('Providers\AddonServiceProvider'),
                new ClassName('Providers\RouteServiceProvider'),
            ],
            'http' => [
                'middlewares' => [
                ],
                'route_middlewares' => [
                ],
            ],
            'routes' => [
                'domain' => new Constant("env('APP_ADDON_DOMAIN')"),
                'prefix' => new Constant("env('APP_ADDON_PATH', '".$properties['addon_name']."')"),
                'middleware' => [],
                'files' => [
                    'routes.php'
                ],
            ],
        ], $this->DEFAULTS);
    }

    protected function generateAsset(FileGenerator $generator, array $properties)
    {
        $generator->directory('assets', function ($generator) use ($properties) {
        });

        $generator->file('gulpfile.js')->template('gulpfile.js', $properties);

        $this->generateAddonConfig($generator, $properties['namespace'], [
            'paths' => [
                'assets' => 'assets',
            ],
        ]);
    }

    protected function generateLibrary(FileGenerator $generator, array $properties)
    {
        $generator->directory('classes', function ($generator) use ($properties) {
            $migration_class = $properties['addon_class'].'_1_0';

            $generator->directory('Providers')
                ->file('AddonServiceProvider.php')->template('AddonServiceProvider.php', $properties);
            $generator->directory('Providers')
                ->file('DatabaseServiceProvider.php')->template('DatabaseServiceProvider.php', array_merge($properties, ['migration_class_name' => $migration_class]));

            $generator->keepDirectory('Commands');

            $generator->directory('Database/Migrations')
                ->file($migration_class.'.php')->template('Migration.php', array_merge($properties, ['class_name' => $migration_class]));
            $generator->keepDirectory('Database/Seeds');

            $generator->keepDirectory('Services');
        });

        $generator->keepDirectory('config');

        $this->generateLang($generator, $properties, function ($generator) use ($properties) {
            $generator->phpConfigFile('messages.php', []);
        });

        $generator->directory('tests', function ($generator) use ($properties) {
            $generator->file('TestCase.php')->template('TestCase.php', $properties);
        });

        $generator->phpBlankFile('helpers.php');

        $this->generateAddonConfig($generator, $properties['namespace'], [
            'namespace' => new Constant('__NAMESPACE__'),
            'directories' => [
                'classes',
            ],
            'files' => [
                'helpers.php',
            ],
            'paths' => [
                'config' => 'config',
                'lang' => 'lang',
                'tests' => 'tests',
            ],
            'providers' => [
                new ClassName('Providers\AddonServiceProvider'),
                new ClassName('Providers\DatabaseServiceProvider'),
            ],
        ], $this->DEFAULTS);
    }

    protected function generateApi(FileGenerator $generator, array $properties)
    {
        $generator->directory('classes', function ($generator) use ($properties) {
            $generator->directory('Providers')
                ->file('AddonServiceProvider.php')->template('AddonServiceProvider.php', $properties);
            $generator->directory('Providers')
                ->file('RouteServiceProvider.php')->template('RouteServiceProvider.php', $properties);

            $generator->keepDirectory('Commands');

            $generator->directory('Controllers')
                ->file('Controller.php')->template('Controller.php', $properties);
            $generator->keepDirectory('Http/Middleware');

            $generator->keepDirectory('Services');
        });

        $generator->keepDirectory('config');

        $this->generateLang($generator, $properties, function ($generator) use ($properties) {
            $generator->phpConfigFile('messages.php', []);
            $generator->phpConfigFile('vocabulary.php', []);
            $generator->phpConfigFile('methods.php', []);
        });

        $generator->directory('specs')->phpConfigFile('methods.php', []);

        $generator->directory('tests', function ($generator) use ($properties) {
            $generator->file('TestCase.php')->template('TestCase.php', $properties);
        });

        $generator->phpBlankFile('helpers.php');
        $generator->file('routes.php')->template('routes.php', $properties);

        $this->generateAddonConfig($generator, $properties['namespace'], [
            'namespace' => new Constant('__NAMESPACE__'),
            'directories' => [
                'classes',
            ],
            'files' => [
                'helpers.php',
            ],
            'paths' => [
                'config' => 'config',
                'lang' => 'lang',
                'specs' => 'specs',
                'tests' => 'tests',
            ],
            'providers' => [
                new ClassName('Providers\AddonServiceProvider'),
                new ClassName('Providers\RouteServiceProvider'),
            ],
            'http' => [
                'middlewares' => [
                ],
                'route_middlewares' => [
                ],
            ],
            'routes' => [
                'domain' => new Constant("env('APP_ADDON_DOMAIN')"),
                'prefix' => new Constant("env('APP_ADDON_PATH', '".$properties['addon_name']."')"),
                'middleware' => ['api'],
                'files' => [
                    'routes.php'
                ],
            ],
        ], $this->DEFAULTS);
    }

    protected function generateUi(FileGenerator $generator, array $properties)
    {
        $generator->directory('classes', function ($generator) use ($properties) {
            $migration_class = $properties['addon_class'].'_1_0';

            $generator->templateDirectory('Controllers', $properties);
            $generator->keepDirectory('Middleware');

            $generator->templateDirectory('Providers', array_merge($properties, ['migration_class_name' => $migration_class]));

            $generator->keepDirectory('Services');
        });

        $generator->keepDirectory('config');

        $generator->keepDirectory('assets');

        $this->generateLang($generator, $properties, function ($generator) use ($properties) {
            $generator->phpConfigFile('messages.php', []);
            $generator->phpConfigFile('vocabulary.php', []);
            $generator->phpConfigFile('forms.php', []);
        });

        $generator->directory('specs')->phpConfigFile('forms.php', []);

        $generator->templateDirectory('views', $properties);

        $generator->templateDirectory('tests', $properties);

        $generator->phpBlankFile('helpers.php');
        $generator->templateFile('routes.php', $properties);

        $this->generateAddonConfig($generator, $properties['namespace'], [
            'namespace' => new Constant('__NAMESPACE__'),
            'directories' => [
                'classes',
            ],
            'files' => [
                'helpers.php',
            ],
            'paths' => [
                'config' => 'config',
                'assets' => 'assets',
                'lang' => 'lang',
                'specs' => 'specs',
                'views' => 'views',
                'tests' => 'tests',
            ],
            'providers' => [
                new ClassName('Providers\AddonServiceProvider'),
                new ClassName('Providers\RouteServiceProvider'),
            ],
            'http' => [
                'middlewares' => [
                ],
                'route_middlewares' => [
                ],
            ],
            'routes' => [
                'domain' => new Constant("env('APP_ADDON_DOMAIN')"),
                'prefix' => new Constant("env('APP_ADDON_PATH', '".$properties['addon_name']."')"),
                'namespace' => new Constant("__NAMESPACE__.'\\Controllers'"),
                'middleware' => ['web'],
                'files' => [
                    'routes.php'
                ],
            ],
        ], $this->DEFAULTS);
    }

    protected function generateUiSample(FileGenerator $generator, array $properties)
    {
        $generator->directory('classes', function ($generator) use ($properties) {
            $migration_class = $properties['addon_class'].'_1_0';

            $generator->templateDirectory('Controllers', $properties);
            $generator->keepDirectory('Middleware');

            $generator->templateDirectory('Providers', array_merge($properties, ['migration_class_name' => $migration_class]));

            $generator->keepDirectory('Services');
        });

        $generator->keepDirectory('config');

        $generator->keepDirectory('assets');

        $this->generateLang($generator, $properties, function ($generator) use ($properties) {
            $generator->phpConfigFile('messages.php', []);
            $generator->phpConfigFile('vocabulary.php', []);
            $generator->phpConfigFile('forms.php', []);
        });
        $generator->directory('lang/en')->file('messages.php')->template('lang/en-messages.php', $properties);
        if (in_array('ja', $properties['languages'])) {
            $generator->directory('lang/ja')->file('messages.php')->template('lang/ja-messages.php', $properties);
        }

        $generator->directory('specs')->phpConfigFile('forms.php', []);

        $generator->templateDirectory('views', $properties);

        $generator->templateDirectory('tests', $properties);

        $generator->phpBlankFile('helpers.php');
        $generator->templateFile('routes.php', $properties);

        $this->generateAddonConfig($generator, $properties['namespace'], [
            'namespace' => new Constant('__NAMESPACE__'),
            'directories' => [
                'classes',
            ],
            'files' => [
                'helpers.php',
            ],
            'paths' => [
                'config' => 'config',
                'assets' => 'assets',
                'lang' => 'lang',
                'specs' => 'specs',
                'views' => 'views',
                'tests' => 'tests',
            ],
            'providers' => [
                new ClassName('Providers\AddonServiceProvider'),
                new ClassName('Providers\RouteServiceProvider'),
            ],
            'http' => [
                'middlewares' => [
                ],
                'route_middlewares' => [
                ],
            ],
            'routes' => [
                'domain' => new Constant("env('APP_ADDON_DOMAIN')"),
                'prefix' => new Constant("env('APP_ADDON_PATH', '".$properties['addon_name']."')"),
                'namespace' => new Constant("__NAMESPACE__.'\\Controllers'"),
                'middleware' => ['web'],
                'files' => [
                    'routes.php'
                ],
            ],
        ], $this->DEFAULTS);
    }

    protected function generateDebug(FileGenerator $generator, array $properties)
    {
        $generator->directory('classes', function ($generator) use ($properties) {
            $generator->directory('Providers')
                ->file('AddonServiceProvider.php')->template('AddonServiceProvider.php', $properties);
            $generator->directory('Providers')
                ->file('RouteServiceProvider.php')->template('RouteServiceProvider.php', $properties);

            $generator->keepDirectory('Commands');

            $generator->directory('Controllers')
                ->file('Controller.php')->template('Controller.php', $properties);
            $generator->directory('Controllers')
                ->file('DebugController.php')->template('DebugController.php', $properties);
            $generator->keepDirectory('Middleware');

            $generator->keepDirectory('Services');
        });

        $generator->keepDirectory('config');

        $this->generateLang($generator, $properties, function ($generator) use ($properties) {
            $generator->phpConfigFile('messages.php', []);
            $generator->phpConfigFile('vocabulary.php', []);
            $generator->phpConfigFile('forms.php', []);
            $generator->phpConfigFile('methods.php', []);
        });

        $generator->directory('specs')->phpConfigFile('forms.php', []);
        $generator->directory('specs')->phpConfigFile('methods.php', []);

        $generator->directory('views')
            ->file('index.blade.php')->template('index.blade.php', $properties);
        $generator->directory('views')
            ->file('layout.blade.php')->template('layout.blade.php', $properties);

        $generator->phpBlankFile('helpers.php');
        $generator->file('routes.php')->template('routes.php', $properties);

        $this->generateAddonConfig($generator, $properties['namespace'], [
            'namespace' => new Constant('__NAMESPACE__'),
            'directories' => [
                'classes',
            ],
            'files' => [
                'helpers.php',
            ],
            'paths' => [
                'config' => 'config',
                'lang' => 'lang',
                'specs' => 'specs',
                'views' => 'views',
                'tests' => 'tests',
            ],
            'providers' => [
                new ClassName('Providers\AddonServiceProvider'),
                new ClassName('Providers\RouteServiceProvider'),
            ],
            'http' => [
                'middlewares' => [
                ],
                'route_middlewares' => [
                ],
            ],
            'routes' => [
                'domain' => new Constant("env('APP_ADDON_DOMAIN')"),
                'prefix' => new Constant("env('APP_ADDON_PATH', 'debug')"),
                'namespace' => new Constant("__NAMESPACE__.'\\Controllers'"),
                'middleware' => ['web'],
                'files' => [
                    'routes.php'
                ],
            ],
        ], $this->DEFAULTS);
    }

    protected function generateGenerator(FileGenerator $generator, array $properties)
    {
        $generator->directory('classes', function ($generator) use ($properties) {
            $generator->directory('Providers')
                ->file('AddonServiceProvider.php')->template('AddonServiceProvider.php', $properties);
        });

        $generator->directory('config', function ($generator) use ($properties) {
            $generator->file('commands.php')->template('commands.php', $properties);
        });

        $generator->directory('stubs', function ($generator) use ($properties) {
            $generator->sourceFile('_console.stub');
            $generator->sourceFile('_controller.stub');
            $generator->sourceFile('_controller-resource.stub');
            $generator->sourceFile('_event.stub');
            $generator->sourceFile('_job.stub');
            $generator->sourceFile('_job-queued.stub');
            $generator->sourceFile('_listener.stub');
            $generator->sourceFile('_listener-queued.stub');
            $generator->sourceFile('_middleware.stub');
            $generator->sourceFile('_migration.stub');
            $generator->sourceFile('_migration-create.stub');
            $generator->sourceFile('_migration-update.stub');
            $generator->sourceFile('_model.stub');
            $generator->sourceFile('_policy.stub');
            $generator->sourceFile('_provider.stub');
            $generator->sourceFile('_request.stub');
            $generator->sourceFile('_seeder.stub');
            $generator->sourceFile('_test.stub');
        });

        $this->generateAddonConfig($generator, $properties['namespace'], [
            'namespace' => new Constant('__NAMESPACE__'),
            'directories' => [
                'classes',
            ],
            'paths' => [
                'config' => 'config',
            ],
            'providers' => [
                new ClassName('Providers\AddonServiceProvider'),
            ],
        ]);
    }

    protected function generateLaravel5(FileGenerator $generator, array $properties)
    {
        $generator->directory('classes', function ($generator) use ($properties) {
            $generator->directory('Providers')
                ->file('AddonServiceProvider.php')->template('AddonServiceProvider.php', $properties);
            $generator->directory('Providers')
                ->file('RouteServiceProvider.php')->template('RouteServiceProvider.php', $properties);

            $generator->keepDirectory('Console/Commands');

            $generator->directory('Http/Controllers')
                ->file('Controller.php')->template('Controller.php', $properties);
            $generator->keepDirectory('Http/Middleware');

            $generator->keepDirectory('Services');
        });

        $generator->keepDirectory('config');

        $generator->directory('database', function ($generator) use ($properties) {
            $generator->keepDirectory('factories');
            $generator->keepDirectory('migrations');
            $generator->keepDirectory('seeds');
        });

        $generator->directory('resources', function ($generator) use ($properties) {
            $generator->keepDirectory('assets');

            $this->generateLang($generator, $properties, function ($generator) use ($properties) {
                $generator->phpConfigFile('messages.php', []);
            });

            $generator->keepDirectory('views');
        });

        $generator->directory('routes', function ($generator) use ($properties) {
            $generator->file('web.php')->template('routes.php', $properties);
        });

        $generator->directory('tests', function ($generator) use ($properties) {
            $generator->file('TestCase.php')->template('TestCase.php', $properties);
        });

        $generator->phpBlankFile('helpers.php');

        $this->generateAddonConfig($generator, $properties['namespace'], [
            'namespace' => new Constant('__NAMESPACE__'),
            'directories' => [
                'classes',
            ],
            'files' => [
                'helpers.php',
            ],
            'paths' => [
                'config' => 'config',
                'assets' => 'resources/assets',
                'lang' => 'resources/lang',
                'views' => 'resources/views',
            ],
            'providers' => [
                new ClassName('Providers\AddonServiceProvider'),
                new ClassName('Providers\RouteServiceProvider'),
            ],
            'http' => [
                'middlewares' => [
                ],
                'route_middlewares' => [
                ],
            ],
            'routes' => [
                'domain' => new Constant("env('APP_ADDON_DOMAIN')"),
                'prefix' => new Constant("env('APP_ADDON_PATH', '".$properties['addon_name']."')"),
                'namespace' => new Constant("__NAMESPACE__.'\\Http\\Controllers'"),
                'middleware' => ['web'],
                'files' => [
                    'routes/web.php'
                ],
            ],
        ], $this->DEFAULTS);
    }

    protected function generateLaravel5Auth(FileGenerator $generator, array $properties)
    {
        $generator->directory('classes', function ($generator) use ($properties) {
            $generator->templateDirectory('Migrations', $properties);
            $generator->keepDirectory('Seeds');

            $generator->templateDirectory('Controllers', $properties);

            $generator->templateDirectory('Providers', $properties);

            $generator->keepDirectory('Services');

            $generator->templateFile('User.php', $properties);
        });

        $generator->keepDirectory('config');

        $generator->sourceDirectory('lang');
        $this->generateLang($generator, $properties, function ($generator) use ($properties) {
            $generator->phpConfigFile('messages.php', []);
            $generator->phpConfigFile('vocabulary.php', []);
            $generator->phpConfigFile('forms.php', []);
        });

        $generator->templateDirectory('views', $properties);

        $generator->templateDirectory('routes', $properties);

        $generator->templateDirectory('tests', $properties);

        $generator->phpBlankFile('helpers.php');

        $this->generateAddonConfig($generator, $properties['namespace'], [
            'namespace' => new Constant('__NAMESPACE__'),
            'directories' => [
                'classes',
            ],
            'files' => [
                'helpers.php',
            ],
            'paths' => [
                'config' => 'config',
                'assets' => 'assets',
                'lang' => 'lang',
                'views' => 'views',
                'tests' => 'tests',
            ],
            'providers' => [
                new ClassName('Providers\AddonServiceProvider'),
                new ClassName('Providers\DatabaseServiceProvider'),
                new ClassName('Providers\RouteServiceProvider'),
            ],
            'http' => [
                'middlewares' => [
                ],
                'route_middlewares' => [
                    'auth' => new ClassName('Middleware\Authenticate'),
                    'auth.basic' => new ClassName('Illuminate\Auth\Middleware\AuthenticateWithBasicAuth'),
                    'guest' => new ClassName('Middleware\RedirectIfAuthenticated'),
                ],
            ],
            'routes' => [
                'domain' => new Constant("env('APP_ADDON_DOMAIN')"),
                'prefix' => new Constant("env('APP_ADDON_PATH', '/')"),
                'namespace' => new Constant("__NAMESPACE__.'\\Controllers'"),
                'middleware' => ['web'],
                'files' => [
                    'routes/web.php'
                ],
                'landing' => '/',
                'home' => '/home',
                'login' => '/login',
            ],
        ], $this->DEFAULTS);
    }

    protected function generateLang(FileGenerator $generator, array $properties, callable $callable)
    {
        $generator->directory('lang', function ($generator) use ($properties, $callable) {
            foreach ($properties['languages'] as $lang) {
                $generator->directory($lang, $callable);
            }
        });
    }

    protected function generateAddonConfig(FileGenerator $generator, $namespace, array $data, array $defaults = null)
    {
        if ($defaults !== null) {
            $data = array_replace($defaults, $data);
        }

        $data = array_merge(['version' => ADDON_VERSION], $data);

        $generator->phpConfigFile('addon.php', $data, $namespace);
    }
}
