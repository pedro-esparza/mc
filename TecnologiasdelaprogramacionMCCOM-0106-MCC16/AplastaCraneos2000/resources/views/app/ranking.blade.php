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
                    <i class="fas fa-star"></i> Ranking table
                </div>
                <div class="card-body">
                    @include('layout.partials.app.createRoom')
                    <span style="background-color: #ffffff; margin-top: -70px;" class="d-block w-100 pb-5 mb-5" id="result-board"></span>
                    <h2 data-step="2" data-intro="Danh sách xếp hạng đầy đủ" class="mt-3"><i class="fas fa-star"></i> Ranking table of {{ $users->total() }} player{{ $users->total() > 0 ? 's' : '' }} ({!! app('App\Http\Controllers\UserController')::renderOnlinePlayers() !!})</h2>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="rankingTable">
                            <thead>
                                <tr>
                                    <th scope="col">Name</th>
                                    {{-- <th scope="col">Email</th> --}}
                                    <th scope="col">Date time joined</th>
                                    <th scope="col">Last seen at</th>
                                    <th scope="col">Points</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{ $users->links('vendor.pagination.match') }}
                                @foreach($users as $user)
                                <tr data-user="{{ $user->id }}">
                                    <th scope="row" class="name">{!! app('App\Http\Controllers\UserController')::renderPlayerName($user->id) !!}</th>
                                    {{-- <td class="email">{!! app('App\Http\Controllers\UserController')::renderPlayerEmail($user->id) !!}</td> --}}
                                    <td>{{ $user->created_at }}</td>
                                    <td>{{ $user->last_seen_at }}</td>
                                    <td class="points">{!! app('App\Http\Controllers\UserController')::renderPoints($user->id) !!}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="text-center mx-auto" style="width: fit-content;" data-step="3" data-intro="Mở trang này trên điện thoại">
                @include('layout.partials.app.qrCode')
                </div>
            </div>
        </div>
    </div>
</div>
@include('layout.partials.app.fb')
@endsection
