<style>
    table {
        width: 100%;
    }

    th {
        height: 1000;
    }

    .specialChar {
        font-family: "DejaVu Sans, sans-serif";
    }
</style>
<table>
    @php
        $historyData = App\Models\Tm1\KasLapakDetail::orderBy('id', 'asc')->where('kas_lapak_id', $data->id)->get();
        $saldoHistory = [];  // Store saldo for each transaction
        $currentSaldo = 0;   // Initialize starting saldo
        
        // Step 1: Calculate the saldo in correct (ascending) order
        foreach ($historyData as $detail) {
            if ($detail->tipe == 1) {  // Debit: Subtract from saldo
                $currentSaldo -= $detail->total;
            } else {  // Credit: Add to saldo
                $currentSaldo += $detail->total;
            }
            
            // Store the calculated saldo for each detail (by ID)
            $saldoHistory[$detail->id] = $currentSaldo;
        }
    @endphp
    <thead>
        <tr>
            <th style="border:1px solid black;text-align: center;font-weight:bold;font-size:20px;"
                colspan="6">MUTASI LAPORAN KEUANGAN {{ $data->lapak->name }} - {{ $data->month->translatedFormat('d F Y') }}</th>
        </tr>
        <tr>
            <th style="border:1px solid black;text-align: center;font-weight:bold;" colspan="2"></th>
            <th style="border:1px solid black;text-align: center;font-weight:bold;">
                IDR {{ number_format($data->details()->where('tipe', 2)
                ->get()
                ->sum('total')) }}
            </th>
            <th style="border:1px solid black;text-align: center;font-weight:bold;">
                IDR {{ number_format($data->details()->where('tipe', 1)
                ->get()
                ->sum('total')) }}
            </th>
            <th style="border:1px solid black;text-align: center;font-weight:bold;">
                IDR {{ number_format($currentSaldo) }}
            </th>
            <th style="border:1px solid black;text-align: center;font-weight:bold;"></th>
        </tr>
        <tr>
            <th
                style="background-color:#d3d3d3;border:1px solid black;text-align: center;font-weight:bold;width:100px;">
                Tanggal
            </th>
            <th
                style="background-color:#d3d3d3;border:1px solid black;text-align: center;font-weight:bold;width:250px;">
                Keterangan
            </th>
            <th
                style="background-color:#fed8b1;border:1px solid black;text-align: center;font-weight:bold;width:150px;">
                Debet (IDR)
            </th>
            <th
                style="background-color:#fed8b1;border:1px solid black;text-align: center;font-weight:bold;width:150px;">
                Kredit (IDR)
            </th>
            <th
                style="background-color:#d1ffbd;border:1px solid black;text-align: center;font-weight:bold;width:150px;">
                Saldo (IDR)
            </th>
            <th
                style="background-color:#d3d3d3;border:1px solid black;text-align: center;font-weight:bold;width:150px;">
                Info Tambahan
            </th>
        </tr>
    </thead>
    <tbody>
       
        @foreach ($data->details as $detail)
            <tr>
                <td style="border:1px solid black;text-align: center;">{{ $detail->tgl_input->format('d/m/Y') }}</td>
                <td style="border:1px solid black;text-align: center;">{{ $detail->keterangan }}</td>
                @if($detail->tipe == 2)
                    <td style="border:1px solid black;text-align: center;">{{ number_format($detail->total) }}</td>
                @else
                    <td style="border:1px solid black;text-align: center;"></td>
                @endif
                
                @if($detail->tipe == 1)
                    <td style="border:1px solid black;text-align: center;">{{ number_format($detail->total) }}</td>
                @else
                    <td style="border:1px solid black;text-align: center;"></td>
                @endif
                <td style="border:1px solid black;text-align: center;">{{ number_format($saldoHistory[$detail->id]) }}</td>
                <td style="border:1px solid black;text-align: center;">{{ $detail->description }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
