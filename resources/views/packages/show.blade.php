@extends('packages.app')

@section('content')
<div class="container">
    <h1>Package Details</h1>
    <div class="card mb-4">
        <div class="card-body">
            <h3 class="card-title">{{ $package->name }}</h3>
            <p class="card-text">
                <strong>Price:</strong> ${{ number_format($package->price, 2) }}
            </p>
            <p class="card-text">
                <strong>Duration:</strong> {{ $package->duration }}
            </p>
            <p class="card-text">
                <strong>Status:</strong> 
                <span class="{{ $package->status ? 'text-success' : 'text-danger' }}">
                    {{ $package->status ? 'Active' : 'Inactive' }}
                </span>
            </p>
            <p class="card-text">
                <strong>Features:</strong>
                @if(is_array($package->features))
                    <ul>
                        @foreach($package->features as $feature)
                            <li>{{ $feature }}</li>
                        @endforeach
                    </ul>
                @else
                    {{ $package->features }}
                @endif
            </p>
        </div>
    </div>
    <a href="{{ route('packages') }}" class="btn btn-secondary"><i class="bi bi-house" > </i>Back</a>
    <a href="{{ route('packages.edit', $package->id) }}" class="btn btn-warning"><i class="bi bi-pencil" > </i>Edit</a>
    <form action="{{ route('packages.destroy', $package->id) }}" method="POST" class="d-inline">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger"><i class="bi bi-trash" > </i>Delete</button>
    </form>
</div>
@endsection
