<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payslip #{{ $payslip->id }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #222; }
        h1 { font-size: 20px; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #f4f4f4; }
    </style>
</head>
<body>
<h1>Monthly Payslip</h1>
<p><strong>Employee:</strong> {{ $payslip->user?->name ?? $payslip->user?->email }}</p>
<p><strong>Period:</strong> {{ $periodStart->format('F Y') }}</p>

<table>
    <tr><th>Base salary</th><td>{{ number_format($salary?->amount ?? $payslip->base_amount, 2) }}</td></tr>
    <tr><th>Bonus</th><td>{{ number_format((float)$payslip->bonus, 2) }}</td></tr>
    <tr><th>Deductions</th><td>{{ number_format((float)$payslip->deductions, 2) }}</td></tr>
    <tr><th>Logged hours</th><td>{{ number_format($hoursWorked, 2) }}</td></tr>
    <tr><th>Net total</th><td>{{ number_format($computedNet, 2) }}</td></tr>
</table>
</body>
</html>
