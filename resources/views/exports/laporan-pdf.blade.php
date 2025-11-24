<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keuangan</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10pt;
            color: #333;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 15px;
        }
        
        .header h1 {
            font-size: 20pt;
            color: #1e40af;
            margin-bottom: 5px;
        }
        
        .header p {
            font-size: 9pt;
            color: #666;
        }
        
        .summary {
            margin-bottom: 25px;
            background-color: #f3f4f6;
            padding: 15px;
            border-radius: 5px;
        }
        
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }
        
        .summary-row:last-child {
            margin-bottom: 0;
            font-weight: bold;
            font-size: 11pt;
            padding-top: 8px;
            border-top: 1px solid #d1d5db;
        }
        
        .label {
            font-weight: 600;
        }
        
        .value {
            text-align: right;
        }
        
        .income {
            color: #059669;
        }
        
        .expense {
            color: #dc2626;
        }
        
        .balance {
            color: #2563eb;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        
        thead {
            background-color: #2563eb;
            color: white;
        }
        
        th {
            padding: 10px 8px;
            text-align: left;
            font-size: 10pt;
            font-weight: 600;
        }
        
        td {
            padding: 8px;
            border-bottom: 1px solid #e5e7eb;
        }
        
        tbody tr:nth-child(even) {
            background-color: #f9fafb;
        }
        
        tbody tr:hover {
            background-color: #f3f4f6;
        }
        
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 8pt;
            font-weight: 600;
        }
        
        .badge-income {
            background-color: #d1fae5;
            color: #065f46;
        }
        
        .badge-expense {
            background-color: #fee2e2;
            color: #991b1b;
        }
        
        .amount {
            font-weight: 600;
            text-align: right;
        }
        
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            font-size: 8pt;
            color: #9ca3af;
        }
        
        .date-col {
            width: 15%;
        }
        
        .category-col {
            width: 20%;
        }
        
        .description-col {
            width: 35%;
        }
        
        .type-col {
            width: 15%;
            text-align: center;
        }
        
        .amount-col {
            width: 15%;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Keuangan</h1>
        <p>Dicetak pada: {{ now()->format('d F Y, H:i') }} WIB</p>
    </div>

    @php
        $totalIncome = $data->where('transaction_type', 'income')->sum('total_amount');
        $totalExpense = $data->where('transaction_type', 'expense')->sum('total_amount');
        $balance = $totalIncome - $totalExpense;
    @endphp

    <div class="summary">
        <div class="summary-row">
            <span class="label">Total Pemasukan:</span>
            <span class="value income">Rp {{ number_format($totalIncome, 0, ',', '.') }}</span>
        </div>
        <div class="summary-row">
            <span class="label">Total Pengeluaran:</span>
            <span class="value expense">Rp {{ number_format($totalExpense, 0, ',', '.') }}</span>
        </div>
        <div class="summary-row">
            <span class="label">Saldo:</span>
            <span class="value balance">Rp {{ number_format($balance, 0, ',', '.') }}</span>
        </div>
    </div>

    @if($data->count() > 0)
        <table>
            <thead>
                <tr>
                    <th class="date-col">Tanggal</th>
                    <th class="category-col">Kategori</th>
                    <th class="description-col">Deskripsi</th>
                    <th class="type-col">Tipe</th>
                    <th class="amount-col">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $transaction)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d/m/Y') }}</td>
                        <td>{{ $transaction->category->name ?? '-' }}</td>
                        <td>{{ $transaction->description }}</td>
                        <td class="type-col">
                            <span class="badge badge-{{ $transaction->transaction_type }}">
                                {{ $transaction->transaction_type === 'income' ? 'Pemasukan' : 'Pengeluaran' }}
                            </span>
                        </td>
                        <td class="amount {{ $transaction->transaction_type === 'income' ? 'income' : 'expense' }}">
                            Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p style="text-align: center; color: #9ca3af; padding: 40px 0;">
            Tidak ada data transaksi untuk periode ini.
        </p>
    @endif

    <div class="footer">
        <p>Laporan ini dihasilkan secara otomatis oleh Sistem Kelola Uang</p>
    </div>
</body>
</html>
