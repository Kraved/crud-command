@php
    echo '<?php';
@endphp


use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class {{ $className }} extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('{{ lcfirst($entityName) }}s', function (Blueprint $table) {
            $table->id('id');
        @forelse($fields as $field)
    $table->text('{{ $field }}');
        @empty
        @endforelse
    $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('{{ lcfirst($entityName) }}s');
    }
}