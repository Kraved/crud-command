<?php

namespace Kraved\CrudCommand\Console;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class MakeCrudEntitiesCommand extends Command
{
    protected $signature = 'make:crud {name} {fields?} {--active}';

    protected $description = 'Создает все необходимые сущности для круда';

    private $filesystem;
    private $className;
    private $fields;
    private $activeField;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;

        parent::__construct();
    }


    public function handle()
    {
        $this->className = $this->argument('name');

        if ($this->argument('fields')) {
            $this->fields = explode(',', $this->argument('fields'));
        }

        $this->activeField = $this->option('active');

        if ($this->activeField) {
            $this->fields[] = 'active';
        }

        $this->createModel();
        $this->createController();
        $this->createMigration();
        $this->createPolicy();
        $this->createRequest();
        $this->createService();
        $this->createBusinessException();
        $this->createViews();
    }

    public function createController()
    {
        $path = 'app/Http/Controllers/' . $this->className . 'Controller.php';

        $data = [
            'className' => $this->className,
            'namespace' => 'App\Http\Controllers',
            'fields' => $this->fields ?? [],
            'active' => $this->activeField,
        ];

        $view = view()->file($this->getTemplatePath('controller'), $data);

        if (!$this->filesystem->exists($path)) {
            $this->filesystem->put($path, $view->render(), 1);
        } else {
            $this->info('Файл ' . $path . ' уже существует');
            return;
        }

        $this->info('Файл ' . $path . ' создан');
    }

    public function createMigration()
    {
        $path = 'database/migrations/' . Carbon::now()->format('Y_m_d_his') . '_create_' . lcfirst($this->className) . 's_table.php';

        $data = [
            'className' => 'Create' . $this->className . 'sTable',
            'entityName' => $this->className,
            'fields' => $this->fields ?? [],
        ];

        $view = view()->file($this->getTemplatePath('migration'), $data);

        if (!$this->filesystem->exists($path)) {
            $this->filesystem->put($path, $view->render());
        } else {
            $this->info('Файл ' . $path . ' уже существует');
            return;
        }

        $this->info('Файл ' . $path . ' создан');
    }

    public function createModel()
    {
        $path = 'app/Models/' . $this->className . '.php';

        $data = [
            'className' => $this->className,
            'namespace' => 'App\Models',
            'fillables' => $this->fields ?? [],
            'active' => $this->activeField,
        ];

        $view = view()->file($this->getTemplatePath('model'), $data);

        if (!$this->filesystem->exists($path)) {
            $this->filesystem->put($path, $view->render());
        } else {
            $this->info('Файл ' . $path . ' уже существует');
            return;
        }

        $this->info('Файл ' . $path . ' создан');
    }

    public function createPolicy()
    {
        $this->call("make:policy", ['name' => $this->className . 'Policy', '--model' => "Models\\" . $this->className]);
    }

    public function createRequest()
    {
        $path = 'app/Http/Requests/' . $this->className . 'Request.php';

        $data = [
            'className' => $this->className,
            'namespace' => 'App\Http\Requests',
            'fields' => $this->fields ?? [],
        ];

        $view = view()->file($this->getTemplatePath('request'), $data);

        if (!$this->filesystem->exists($path)) {
            $this->filesystem->put($path, $view->render());
        } else {
            $this->info('Файл ' . $path . ' уже существует');
            return;
        }

        $this->info('Файл ' . $path . ' создан');
    }

    public function createService()
    {
        $path = 'app/Services/' . $this->className . 'Service.php';

        $this->makeDirectory($path);

        $data = [
            'className' => $this->className,
            'namespace' => 'App\Http\Requests',
            'fields' => $this->fields ?? [],
            'active' => $this->activeField,
        ];

        $view = view()->file($this->getTemplatePath('service'), $data);

        if (!$this->filesystem->exists($path)) {
            $this->filesystem->put($path, $view->render());
        } else {
            $this->info('Файл ' . $path . ' уже существует');
            return;
        }

        $this->info('Файл ' . $path . ' создан');
    }

    public function createBusinessException()
    {
        $path = 'app/Exceptions/BusinessException.php';

        if (!file_exists($path)) {

            $view = view()->file($this->getTemplatePath('business_exception'));

            $this->filesystem->put($path, $view->render());

            $this->info('Файл ' . $path . ' создан');
        }
    }

    public function createViews()
    {

        $path = 'resources/views/' . lcfirst($this->className) . 's/index.blade.php';
        $this->makeDirectory($path);
        $this->filesystem->put($path, "<?php \n dd($" . lcfirst($this->className) . 's);');
        $this->info('Файл ' . $path . ' создан');

        $path = 'resources/views/' . lcfirst($this->className) . 's/create.blade.php';
        $this->filesystem->put($path, "<?php \n dd($" .lcfirst($this->className) . ');');
        $this->info('Файл ' . $path . ' создан');

        $path = 'resources/views/' . lcfirst($this->className) . 's/edit.blade.php';
        $this->filesystem->put($path, "<?php \n dd($" . lcfirst($this->className) . ');');
        $this->info('Файл ' . $path . ' создан');

        $path = 'resources/views/' . lcfirst($this->className) . 's/show.blade.php';
        $this->filesystem->put($path, "<?php \n dd($" . lcfirst($this->className) . ');');
        $this->info('Файл ' . $path . ' создан');
    }


    public function makeDirectory($path)
    {
        if (! $this->filesystem->isDirectory(dirname($path))) {
            $this->filesystem->makeDirectory(dirname($path), 0777, true, true);
        }

        return $path;
    }

    public function isDirectory($directory)
    {
        return is_dir($directory);
    }

    public function getTemplatePath($type)
    {
        return __DIR__ . "/templates/$type.blade.php";
    }



}
