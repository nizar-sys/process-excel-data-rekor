<x-app-layout title="Upload">
    <x-slot name="header">
        <h3 class="text-center">Olah Data Rekor</h3>
    </x-slot>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h4 class="text-center">Data Rekening Koran</h4>
                <br>
                <br>
                <div class="alert alert-info">
                    <p>Nomor Rekening: {{ $no_rek }}</p>
                    <p>Nama Rekening: {{ $nama_rek }}</p>
                </div>
                <br>
                <table class="table">
                    <thead>
                        <tr>
                            <th>POSTAT</th>
                            <th>PORECO</th>
                            <th>PODTVL</th>
                            <th>POREFN</th>
                            <th>PODTPO</th>
                            <th>POTCRO</th>
                            <th>PODESC</th>
                            <th>POAMNT</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($uploadedFiles as $uploadedFile)
                            <tr>
                                <td>{{ $uploadedFile->POSTAT }}</td>
                                <td>{{ $uploadedFile->PORECO }}</td>
                                <td>{{ $uploadedFile->PODTVL }}</td>
                                <td>{{ $uploadedFile->POREFN }}</td>
                                <td>{{ $uploadedFile->PODTPO }}</td>
                                <td>{{ $uploadedFile->POTCRO }}</td>
                                <td>{{ $uploadedFile->PODESC }}</td>
                                <td>{{ $uploadedFile->POAMNT }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <button type="button" class="btn btn-primary" onclick="processAllData()">Proses Semua Data</button>
            </div>
        </div>
    </div>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        function processAllData() {
            $.ajax({
                url: '{{ route('data.process') }}',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response && response.message) {
                        alert(response.message);
                        window.location.href = '{{ route('data.hasil') }}';
                    } else {
                        alert('Respon tidak valid atau tidak terdefinisi.');
                    }
                },
                error: function(xhr) {
                    var errorMessage = "Terjadi kesalahan saat memproses semua data.";
                    if (xhr && xhr.responseText) {
                        errorMessage += "\nError: " + xhr.responseText;
                    }
                    alert(errorMessage);
                    console.log(xhr);
                }
            });
        }
    </script>
</x-app-layout>
