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
    <thead>
        <tr>
            <th style="border:1px solid black;text-align: center;font-weight:bold;font-size:20px;"
                colspan="13">TIMBANGAN SAM {{ $data->lapak->name }}</th>
        </tr>
        <tr>
            <th
                style="background-color:#d3d3d3;border:1px solid black;text-align: center;font-weight:bold;width:100px;">
                Tgl Masuk
            </th>
            <th
                style="background-color:#d3d3d3;border:1px solid black;text-align: center;font-weight:bold;width:100px;">
                Supplier
            </th>
            <th
                style="background-color:#d3d3d3;border:1px solid black;text-align: center;font-weight:bold;width:100px;">
                No Kendaraan
            </th>
            <th
                style="background-color:#d3d3d3;border:1px solid black;text-align: center;font-weight:bold;width:100px;">
                Jenis Kendaraan
            </th>
            <th
                style="background-color:#d1ffbd;border:1px solid black;text-align: center;font-weight:bold;width:100px;">
                Gross
            </th>
            <th
                style="background-color:#d1ffbd;border:1px solid black;text-align: center;font-weight:bold;width:100px;">
                Tare
            </th>
            <th
                style="background-color:#d1ffbd;border:1px solid black;text-align: center;font-weight:bold;width:100px;">
                Bruto
            </th>
            <th
                style="background-color:#d1ffbd;border:1px solid black;text-align: center;font-weight:bold;width:100px;">
                Refaksi
            </th>
            <th
                style="background-color:#d1ffbd;border:1px solid black;text-align: center;font-weight:bold;width:100px;">
                Potongan
            </th>
            <th
                style="background-color:#fed8b1;border:1px solid black;text-align: center;font-weight:bold;width:100px;">
                Netto
            </th>
            <th
                style="background-color:#fed8b1;border:1px solid black;text-align: center;font-weight:bold;width:100px;">
                Harga (IDR)
            </th>
            <th
                style="background-color:#fed8b1;border:1px solid black;text-align: center;font-weight:bold;width:150px;">
                Jumlah (IDR)
            </th>
            <th
                style="background-color:#d3d3d3;border:1px solid black;text-align: center;font-weight:bold;width:120px;">
                Fee Agen Bruto (IDR)
            </th>
            <th
                style="background-color:#d3d3d3;border:1px solid black;text-align: center;font-weight:bold;width:120px;">
                Fee Agen (IDR)
            </th>
            <th
                style="background-color:#d3d3d3;border:1px solid black;text-align: center;font-weight:bold;width:120px;">
                Biaya Bongkar dan Ampera (IDR)
            </th>
            <th
                style="background-color:#d1ffbd;border:1px solid black;text-align: center;font-weight:bold;width:150px;">
                Total Dibayar (IDR)
            </th>
            @foreach ($data->details as $detail)
                @forelse ($detail->parts as $part)
                <th
                    style="background-color:#d3d3d3;border:1px solid black;text-align: center;font-weight:bold;width:120px;">
                    {{ $part->pembayaran->name }} (IDR)
                </th>
                @empty
                @endforelse
            @endforeach
            <th
                style="background-color:#d1ffbd;border:1px solid black;text-align: center;font-weight:bold;width:150px;">
                Hasil AKhir (IDR)
            </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data->details as $detail)
            <tr>
                <td style="border:1px solid black;text-align: center;">{{ $detail->tgl_masuk->format('d/m/Y') }}</td>
                <td style="border:1px solid black;text-align: center;">{{ $detail->supplier }}</td>
                <td style="border:1px solid black;text-align: center;">{{ $detail->kendaraan->no_kendaraan }}</td>
                <td style="border:1px solid black;text-align: center;">{{ $detail->kendaraan->name }}</td>
                <td style="border:1px solid black;text-align: center;">{{ number_format($detail->gross) }}</td>
                <td style="border:1px solid black;text-align: center;">{{ number_format($detail->tere) }}</td>
                <td style="border:1px solid black;text-align: center;">{{ number_format($detail->bruto) }}</td>
                <td style="border:1px solid black;text-align: center;">{{ number_format($detail->refaksi) }} %</td>
                <td style="border:1px solid black;text-align: center;">{{ number_format($detail->potongan) }}</td>
                <td style="border:1px solid black;text-align: center;">{{ number_format($detail->netto) }}</td>
                <td style="border:1px solid black;text-align: center;">{{ number_format($detail->harga) }}</td>
                <td style="border:1px solid black;text-align: center;">{{ number_format($detail->jumlah) }}</td>
                <td style="border:1px solid black;text-align: center;">{{ number_format($detail->fee_agen_bruto) }}</td>
                <td style="border:1px solid black;text-align: center;">{{ number_format($detail->fee_agen) }}</td>
                <td style="border:1px solid black;text-align: center;">{{ number_format($detail->biaya_bongkar_ampera) }}</td>
                <td style="border:1px solid black;text-align: center;">{{ number_format($detail->total_dibayar) }}</td>
                @forelse ($detail->parts as $part)
                <td style="border:1px solid black;text-align: center;">{{ number_format($part->pembayaran->total) }}</td>
                @empty
                <td style="border:1px solid black;text-align: center;"></td>
                @endforelse
                <td style="border:1px solid black;text-align: center;">{{ number_format($detail->hasil_akhir) }}</td>
                {{-- <td class="specialChar" style="border:1px solid black;text-align: center; background-color:{{ $color }}">
                    ✔
                </td> --}}
            </tr>
        @endforeach
    </tbody>
</table>
