<x-app-layout title="Hasil Proses">
    <x-slot name="header">
        <h3 class="text-center">Hasil Proses Data Rekor</h3>
    </x-slot>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h4 class="text-center">Data Rekening Koran yang Diproses</h4>
                <br>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Tanggal Transaksi</th>
                            <th>Keterangan</th>
                            <th>Debit</th>
                            <th>Kredit</th>
                            <th>Saldo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($processedData as $data)
                            <tr>
                                <td>{{ $data->tanggal_transaksi }}</td>
                                <td>{{ $data->keterangan }}</td>
                                <td>{{ $data->debit }}</td>
                                <td>{{ $data->kredit }}</td>
                                <td>{{ $data->saldo }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
