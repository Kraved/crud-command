@php
    echo '<?php';
@endphp


namespace {{ $namespace }};

use Illuminate\Foundation\Http\FormRequest;

class {{ $className }}Request extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
    @forelse($fields as $field)
        '{{ $field }}' => 'nullable',
    @empty
    @endforelse
    ];

        return $rules;
    }

    public function attributes()
    {
        return [

        ];
    }
}
