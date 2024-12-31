@extends('reports.app')

@section('content')
<div style="max-width: 600px; margin: 20px auto; font-family: Arial, sans-serif;">
    <h2 style="text-align: center; color: #4CAF50; margin-bottom: 20px;">Generate Sales Report</h2>
    
    <form action="{{ route('reports.generate') }}" method="POST" style="background: #f9f9f9; padding: 20px; border: 1px solid #ddd; border-radius: 8px;">
        @csrf
        <div style="margin-bottom: 15px;">
            <label for="type" style="font-weight: bold; display: block; margin-bottom: 5px;">Report Type:</label>
            <select name="type" id="type" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                <option value="day">Day</option>
                <option value="week">Week</option>
                <option value="month">Month</option>
                <option value="year">Year</option>
            </select>
        </div>

        <div style="margin-bottom: 15px;">
            <label for="value" style="font-weight: bold; display: block; margin-bottom: 5px;">Select Date:</label>
            <input type="date" name="value" id="value" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
        </div>

        <div style="margin-bottom: 15px;">
            <label for="format" style="font-weight: bold; display: block; margin-bottom: 5px;">Format:</label>
            <select name="format" id="format" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                <option value="pdf">PDF</option>
                <option value="excel">Excel</option>
            </select>
        </div>

        <div style="text-align: center;">
            <button type="submit" style="background: #4CAF50; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px;">
                Generate Report
            </button>
        </div>
    </form>
</div>
@endsection
