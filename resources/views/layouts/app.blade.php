<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    
    <title>{{$title ?? 'Home'}}</title>
</head>
<body>
<nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container">
            <a class="navbar-brand" href="https://www.bankmalukumalut.co.id/">
                <img src="logo BMM.svg" alt="" width="200" height="100">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarText">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="/">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="upload">Olah Rekening Koran</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="login">Login</a>
                    </li>
                </ul>
                <form id="search-form-nav" method="GET" action="{{ route('search-rekor') }}" class="d-flex">
                @csrf
                    <input type="text" placeholder="Cari Nama Rekening" class="form-control me-2" name="nama_rek">
                    <button type="submit" class="btn btn-primary">CARI</button>
                </form>
            </div>
            @if(isset($rekening))
        <div class="mt-4">
            <h5>Hasil Pencarian</h5>
            <div class="card">
                <div class="card-body">
                    @foreach($rekening as $rek)
                        <div class="mb-3">
                            <h6 class="card-title">Nomor Rekening: {{ $rek->no_rek }}</h6>
                            <p class="card-text">Nama Rekening: {{ $rek->nama_rek }}</p>
                            <p class="card-text">File Path: {{ $rek->file_path }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
        </div>
    </nav>
    {{$slot}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>