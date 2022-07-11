@php
echo '<?php';
@endphp


namespace {{ $namespace }};

use Illuminate\Database\Eloquent\Model;

class {{ $className }} extends Model
{
    protected $guarded = ['id'];

    protected $fillable = [
    @forelse($fillables as $fillable)
        '{{ $fillable }}',
    @empty

    @endforelse
    ];

@if($active)
    public function scopeActive()
    {
        return $this->where('active', 1);
    }
@endif
}
