@php
$serviceName = $className . 'Service';
$serviceVarName = '$' . lcfirst($className) . 'Service';
@endphp

@php
    echo '<?php';
@endphp


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\{{ $serviceName }};
use App\Http\Requests\{{ $className }}Request;

class {{ $className }}Controller extends Controller
{
    private {{ $serviceVarName }};

    public function __construct({{ $serviceName }} {{ $serviceVarName }})
    {
        $this->{{ ltrim($serviceVarName, '$') }} = {{ $serviceVarName }};
    }

    public function index()
    {
    @if($active)
    ${{ lcfirst($className) }}s = $this->{{ ltrim($serviceVarName, '$') }}->getAllActive{{$className}}s();
    @else
    ${{ lcfirst($className) }}s = $this->{{ ltrim($serviceVarName, '$') }}->getAll{{$className}}s();
    @endif

        return view('{{ lcfirst($className) }}s.index', compact('{{ lcfirst($className) }}s'));
    }

    public function show($id)
    {
        ${{ lcfirst($className) }} = $this->{{ ltrim($serviceVarName, '$') }}->get{{$className}}ById($id);

        return view('{{ lcfirst($className) }}s.show', compact('{{ lcfirst($className) }}'));
    }

    public function create()
    {
        return view('{{ lcfirst($className) }}s.create');
    }

    public function store({{ $className }}Request $request)
    {
        $data = $request->only([
        @forelse($fields as $field)
            '{{ $field }}',
        @empty
        @endforelse
        ]);

        if($this->{{ ltrim($serviceVarName, '$') }}->store($data)) {
            return view('{{ lcfirst($className) }}s.index')
               ->with(['success' => '{{ $className }} успешно сохранен.']);
        }
    }

    public function edit($id)
    {
        ${{ lcfirst($className) }} = $this->{{ ltrim($serviceVarName, '$') }}->get{{$className}}ById($id);

        return view('{{ lcfirst($className) }}s.edit', compact('{{ lcfirst($className) }}'));
    }


    public function update({{ $className }}Request $request, $id)
    {
        $data = $request->only([
        @forelse($fields as $field)
            '{{ $field }}',
        @empty
        @endforelse
        ]);

        $data['id'] = $id;

        if($this->{{ ltrim($serviceVarName, '$') }}->update($data)) {
            return view('{{ lcfirst($className) }}s.index')
                ->with(['success' => '{{ $className }} успешно изменен.']);
        }
    }

    public function destroy($id)
    {
        if($this->{{ ltrim($serviceVarName, '$') }}->delete($id)) {
            return view('{{ lcfirst($className) }}s.index')
                ->with(['success' => '{{ $className }} успешно удален.']);
        }
    }
}
