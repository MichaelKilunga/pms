@extends('sales.app')

@section('content')

<form action="{{ route('reports.generate') }}" method="POST">
    @csrf
    <label for="type">Report Type:</label>
    <select name="type" required>
        <option value="day">Day</option>
        <option value="month">Month</option>
        <option value="year">Year</option>
    </select>

    <label for="value">Select Date:</label>
    <input type="date" name="value" required>

    <label for="format">Format:</label>
    <select name="format" required>
        <option value="pdf">PDF</option>
        <option value="excel">Excel</option>
    </select>

    <button type="submit">Generate Report</button>
</form>

@endsection
