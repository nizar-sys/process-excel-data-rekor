<x-app-layout title="Home">
    <div class="text-center">
        <div class="text-center">
            <br>
            <h3>Buat Rekening Koran</h3>
        </div>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <form method="POST" action="{{ route('store-rekor') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <input type="text" placeholder="Input Nomor Rekening" class="form-control" name="no_rek"
                                maxlength="50">
                            <br>
                            <br>
                            <div class="mb-3">
                                <input type="text" placeholder="Input Nama Rekening" class="form-control"
                                    name="nama_rek">
                            </div>
                            <br>
                            <div class="col-lg-12 py-3">
                                <label for="rekor"> Upload File Rekening Koran (Format: Excel)</label>
                                <input type="file" id="rekor" class="form-control" style="padding: 3px;"
                                    name="rekor" required />
                            </div>
                        </div>
                        <div class="mb-3 text-center">
                            <button type="submit" class="btn btn-primary">SUBMIT</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>

        <br>
        <br>
        <br>
        <marquee behavior="scroll" direction="left">
            <span style="color: red;">Selamat bekerja </span>
            <span style="color: green;">Semangat </span>
            <span style="color: blue;">cayooo </span>
        </marquee>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (isset($rekor))
            <div class="alert alert-info">
                <p>Data yang baru saja diunggah:</p>
                <p>Nomor Rekening: {{ $rekor->no_rek }}</p>
                <p>Nama Rekening: {{ $rekor->nama_rek }}</p>
            </div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            var searchForm = document.getElementById('search-form-nav');


            searchForm.addEventListener('submit', function(event) {

                event.preventDefault();


                var searchQuery = searchForm.querySelector('input[name="nama_rek"]').value;


                window.location.href = "http://rekorapp.test/search-rekor?nama_rek=" + encodeURIComponent(
                    searchQuery);
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</x-app-layout>
