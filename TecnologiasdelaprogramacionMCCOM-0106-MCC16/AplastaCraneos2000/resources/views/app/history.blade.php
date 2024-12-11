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
                    <i class="fas fa-archive"></i> History
                </div>
                <div class="card-body">
                    @include('layout.partials.app.createRoom')
                    <span style="background-color: #ffffff; margin-top: -70px;" class="d-block w-100 pb-5 mb-5" id="result-board"></span>
                    <h2 data-step="2" data-intro="Danh sách các ván đấu đã hoàn tất" class="mt-3"><i class="fas fa-archive"></i> History ({{ $playedRooms->total() }} match{{ $playedRooms->total() > 1 ? 'es' : '' }}, {!! app('App\Http\Controllers\UserController')::renderOnlinePlayers() !!})</h2>
                    <div class="table-responsive mb-3">
                        <table class="table table-striped table-hover" id="results-table">
                            <thead>
                                <tr>
                                    <th scope="col">Room name</th>
                                    <th scope="col">Host</th>
                                    <th scope="col">Guest</th>
                                    <th scope="col">Result</th>
                                    <th scope="col">Last played</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{ $playedRooms->links('vendor.pagination.match') }}
                                @foreach($playedRooms as $room)
                                <tr data-code="{{ $room->code }}" data-fen="{{ $room->fen }}">
                                    <th scope="row" class="roomCode"><a class="text-dark showPromotion" target="_blank" href="{{ url('/room/') }}/{{ $room->code }}/watch">{{ ((isset($room->name) && $room->name != '') ? $room->name: $room->code) }}</a></th>
                                    <td class="host-name">
                                        {!! app('App\Http\Controllers\UserController')::renderPlayerName($room->host_id) !!}
                                    </td>
                                    <td class="guest-name">
                                        {!! app('App\Http\Controllers\UserController')::renderPlayerName($room->guest_id) !!}
                                    </td>
                                    <td>
                                        @if ($room->result == '1')
                                            Host won
                                        @elseif ($room->result == '0')
                                            Drawed
                                        @elseif ($room->result == '-1')
                                            Guest won
                                        @else
                                            Ongoing
                                        @endif
                                    </td>
                                    <td>{{ $room->modified_at }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center mx-auto" style="width: fit-content;" data-step="3" data-intro="Mở trang này trên điện thoại">
                    @include('layout.partials.app.qrCode')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('layout.partials.app.fb')
@endsection