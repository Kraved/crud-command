@php
    echo '<?php';
@endphp


namespace App\Services;

use App\Exceptions\BusinessException;
use App\Models\{{ $className }};

class {{ $className }}Service
{
    private $model;

    public function __construct({{ $className }} $model)
    {
        $this->model = $model;
    }
@if($active)
    public function getAllActive{{ $className }}s()
    {
        return $this->model->active()->get();
    }
@else
    public function getAll{{ $className }}s()
    {
        return $this->model->all();
    }
@endif

    public function get{{ $className }}ById($id)
    {
        return $this->model->find($id);
    }

    public function store($data)
    {
        ${{ lcfirst($className) }} = $this->model->make($data);

        if (!${{ lcfirst($className) }}->save()) {
            throw new BusinessException('Не удалось сохранить {{ $className }}');
        }

        return ${{ lcfirst($className) }};
    }

    public function update($data)
    {
        ${{ lcfirst($className) }} = $this->model->find($data['id']);

        if (!${{ lcfirst($className) }}) {
            throw new BusinessException('Не найден {{ $className }}!');
        }

        if (!${{ lcfirst($className) }}->update($data)) {
            throw new BusinessException('Не удалось изменить {{ $className }}!');
        }

        return ${{ lcfirst($className) }};
    }

    public function delete($id) {
        ${{ lcfirst($className) }} = $this->model->find($id);

        if (!${{ lcfirst($className) }}) {
            throw new BusinessException('Не найден {{ $className }}!');
        }

        if (!${{ lcfirst($className) }}->delete()) {
            throw new BusinessException('Не удалось удалить {{ $className }}!');
        }
    }
}