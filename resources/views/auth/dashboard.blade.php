@extends ('auth.layouts' )
@section('content')
    <div class="row justify-content-center mt-5">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>
                <div class="card-body">
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success">
                            {{ $message }}
                        </div>
                    @else
                        <div class="alert alert-success">
                            You are logged in!
                        </div>
                    @endif
                    <div class="card">
                        <div class="card-header">Edit Data Buku</div>
                        <div class="card-body">
                            <h5 class="card-title">Menu Edit Data Buku</h5>
                            <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                            <a href="{{ route('buku.index') }}" class="btn btn-primary">Edit</a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
