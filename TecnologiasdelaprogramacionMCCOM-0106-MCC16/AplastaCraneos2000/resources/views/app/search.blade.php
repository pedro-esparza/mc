@extends('layout.app')

@section('content')
<div class="container">
    <div class="row justify-content-center text-center mb-4">
        <div class="col-12">
            <!-- CO_res -->
            <ins class="adsbygoogle"
            style="display:block"
            data-ad-client="ca-pub-3585118770961536"
            data-ad-slot="7831723879"
            data-ad-format="auto"
            data-full-width-responsive="true"></ins>
            <script>
            (adsbygoogle = window.adsbygoogle || []).push({});
            </script>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-search"></i> Players' search
                </div>
                <div class="card-body">
                    @include('layout.partials.app.createRoom')
                    <span style="background-color: #ffffff; margin-top: -70px;" class="d-block w-100 pb-5 mb-5" id="result-board"></span>
                    <h2 data-step="2" data-intro="Tìm kiếm kỳ thủ theo tên và email"><i class="fas fa-search"></i> Players' search ({!! app('App\Http\Controllers\UserController')::renderOnlinePlayers() !!})</h2>
                    <form action="{{ route('searchPlayers') }}" method="GET">
                        @csrf
                        <div class="input-group mb-3" id="search-form">
                            <input data-step="3" data-intro="Điền vào từ khóa cần tìm" name="query" type="text" class="form-control form-control-lg" id="keyword" aria-label="Who are you looking for?" placeholder="Who are you looking for?" value="{{ isset($_GET['query']) ? $_GET['query'] : '' }}">
                            <button data-step="4" data-intro="Ấn để bắt đầu tìm kiếm" class="btn btn-dark btn-lg" type="submit"><i class="fad fa-search"></i><span> Search</span></button>
                            <button data-step="5" data-intro="Ấn để quay lại trang mặc định" class="btn btn-light btn-lg" type="button" onclick="javascript:window.location.href='{{ url('/search') }}'"><i class="fad fa-chevron-left"></i><span> Return</span></button>
                        </div>
                    </form>
                    <script>
                        $(document).ready(function() {
                            $('input#keyword').focus();
                        });
                    </script>
                    @if (isset($results) && count($results) > 0)
                    <span class="lead">{{ $results->total() }} player{{ $results->total() > 1 ? 's' : '' }} found</span>
                    <div data-step="6" data-intro="Kết quả tìm kiếm" class="table-responsive">
                        <table class="table table-striped table-hover" id="rankingTable">
                            <thead>
                                <tr>
                                    <th scope="col">Name</th>
                                    {{-- <th scope="col">Email</th> --}}
                                    <th scope="col">Time joined</th>
                                    <th scope="col">Last seen at</th>
                                    <th scope="col">Points</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{ $results->links('vendor.pagination.search') }}
                                @foreach ($results as $result)
                                <tr data-user="{{ $result->id }}">
                                    <th scope="row" class="name">{!! app('App\Http\Controllers\UserController')::renderPlayerName($result->id) !!}</th>
                                    {{-- <td class="email">{!! app('App\Http\Controllers\UserController')::renderPlayerEmail($result->id) !!}</td> --}}
                                    <td>{{ $result->created_at }}</td>
                                    <td>{{ $result->last_seen_at }}</td>
                                    <td class="points">{!! app('App\Http\Controllers\UserController')::renderPoints($result->id) !!}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="alert alert-secondary lead" role="alert">
                        No player found!
                    </div>
                    @endif
                </div>
                <div class="text-center mx-auto" style="width: fit-content;" data-step="7" data-intro="Mở trang này trên điện thoại">
                @include('layout.partials.app.qrCode')
                </div>
            </div>
        </div>
    </div>
</div>
@include('layout.partials.app.fb')
@endsection
