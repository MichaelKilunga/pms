@extends('pharmacies.app')

@section('content')
    <div class="container">
        {{-- <h1>Your Businesses</h1> --}}
        <!-- resources/views/business/select.blade.php -->
        <form action="{{ route('pharmacies.set') }}" method="POST">
            @csrf
            <label for="business_id">Select pharmacy:</label>
            <select name="pharmacy_id" id="pharmacy_id">
                <option value="">Select Pharmacy    </option>
                @foreach ($pharmacies as $pharmacy)
                    <option value="{{ $pharmacy->id }}">{{ $pharmacy->id }}. {{ $pharmacy->name }}</option>
                @endforeach
            </select>
            <button type="submit">Select</button>
        </form>
    </div>
@endsection
