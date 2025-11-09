@if(auth()->check())

    @if(Auth::user()->rol_id == 1)
        <form action="{{ route('establecimiento.select') }}" method="POST" class="m-0 p-0">
            @csrf
            <select name="establecimiento_id" class="form-select form-select-sm"
                    style="min-width: 200px;" onchange="this.form.submit()">

                @foreach(\App\Models\Establecimiento::orderBy('nombre')->get() as $est)
                    <option value="{{ $est->id }}"
                        {{ session('establecimiento_id') == $est->id ? 'selected' : '' }}>
                        {{ $est->nombre }}
                    </option>
                @endforeach

            </select>
        </form>
    @else
        <span class="text-muted small">
            {{ Auth::user()->establecimiento->nombre ?? 'Sin establecimiento' }}
        </span>
    @endif

@endif
