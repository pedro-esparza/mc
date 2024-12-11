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
                    <img src="{{ Avatar::create($player->name)->setDimension(48)->setFontSize(24) }}" />
                    @if ($player->id == auth()->id() && !str_contains(url()->current(), url('/player').'/'))
                    My profile
                    @else
                    Player "{{ $player->name }}"
                    @endif
                    {!! app('App\Http\Controllers\UserController')::onlineStatus($player->id) !!}
                </div>
                <div class="card-body">
                    <h5>Name: {{ $player->name }}</h5>
                    @if ($player->id == auth()->id() && !str_contains(url()->current(), url('/player').'/'))
                    <h5>Email: {!! app('App\Http\Controllers\UserController')::renderPlayerEmail($player->id) !!}</h5>
                    @endif
                    <h5>Date time joined: {{ $player->created_at }}</h5>
                    <h5>Last seen at: {{ $player->last_seen_at }}</h5>
                    <h5>Ranking: {!! app('App\Http\Controllers\UserController')::renderPlayerRank($player->id) !!}</h5>
                    <h5>Points: <span id="points">{!! app('App\Http\Controllers\UserController')::renderPoints($player->id) !!}</span></h5>
                    <h5>Won: <span id="winPoints">{!! app('App\Http\Controllers\UserController')::renderWinMatchPoints($player->id) !!}</span></h5>
                    <h5>Drawed: <span id="drawPoints">{!! app('App\Http\Controllers\UserController')::renderDrawMatchPoints($player->id) !!}</span></h5>
                    <h5>Lost: <span id="losePoints">{!! app('App\Http\Controllers\UserController')::renderLoseMatchPoints($player->id) !!}</span></h5>
                    <h5>Total matches: <span id="totalPoints">{!! app('App\Http\Controllers\UserController')::renderTotalMatchPoints($player->id) !!}</span></h5>
                    @if ($playerRooms->total() > 0)
                    <span style="background-color: #ffffff; margin-top: -70px;" class="d-block w-100 pb-5 mb-5" id="result-board"></span>
                    <h2 data-step="1" data-intro="Danh sách các trận đấu của kỳ thủ '{{ $player->name }}'" class="mt-3"><i class="fas fa-list-ul"></i> Results</h2>
                    <div class="table-responsive mb-3">
                        <table class="table table-striped table-hover" id="results-table">
                            <thead>
                                <tr>
                                    <th scope="col">Room name</th>
                                    <th scope="col">Host</th>
                                    <th scope="col">Guest</th>
                                    <th scope="col">Turn</th>
                                    <th scope="col">Result</th>
                                    <th scope="col">Play</th>
                                    <th scope="col">Last played</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{ $playerRooms->links('vendor.pagination.match') }}
                                @foreach($playerRooms as $room)
                                <tr data-code="{{ $room->code }}" data-fen="{{ $room->fen }}">
                                    <th scope="row" class="roomCode"><a class="text-dark showPromotion" target="_blank" href="{{ url('/room/') }}/{{ $room->code }}/watch">{{ ((isset($room->name) && $room->name != '') ? $room->name: $room->code) }}</a></th>
                                    <td class="host-name">
                                        {!! app('App\Http\Controllers\UserController')::renderPlayerName($room->host_id) !!}
                                    </td>
                                    <td class="guest-name">
                                        {!! app('App\Http\Controllers\UserController')::renderPlayerName($room->guest_id) !!}
                                    </td>
                                    <td class="text-center">
                                        @if (str_contains($room->fen, ' w '))
                                        <span class="text-dark">White</span>
                                        @elseif (str_contains($room->fen, ' b '))
                                        <span class="text-dark">Black</span>
                                        @endif
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
                                    <td>
                                        @if (!isset($room->result))
                                            @if (auth()->check())
                                                @if (isset($room->guest_id))
                                                <a class="btn btn-sm btn-dark" href="javascript:joinMatch('{{ $room->code }}')"><i class="fad fa-mouse"></i> Play</a>
                                                @else
                                                <a class="btn btn-sm btn-dark pulse-red" href="javascript:joinMatch('{{ $room->code }}')"><i class="fad fa-mouse"></i> Play</a>
                                                @endif
                                            @else
                                                @if (isset($room->guest_id))
                                                <a class="btn btn-sm btn-dark showPromotion" href="{{ url('/dang-nhap') }}"><i class="fad fa-sign-in"></i> Login</a>
                                                @else
                                                <a class="btn btn-sm btn-dark pulse-red showPromotion" href="{{ url('/dang-nhap') }}"><i class="fad fa-sign-in"></i> Login</a>
                                                @endif
                                            @endif
                                        @else
                                            <span class="text-dark">Finished</span>
                                        @endif
                                    </td>
                                    <td class="date">{{ $room->modified_at }}</td>
                                </tr>
                                <script>
                                    function joinMatch(roomCode) {
                                        var hostId = '';
                                        var guestId = '';
                                        $.ajax({
                                            type: "POST",
                                            url: '{{ url('/api') }}/getRoomIds',
                                            data: {
                                                'room-code': roomCode
                                            },
                                            dataType: 'json'
                                        }).done(function(data){
                                            hostId = data.host_id;
                                            guestId = data.guest_id;
                                            console.log(data);
                                            console.log(data.host_id);
                                            console.log(data.guest_id);
                                            if (hostId != '{{ auth()->id() }}' && guestId != '{{ auth()->id() }}') {
                                                $.ajax({
                                                    type: "POST",
                                                    url: '{{ url('/api') }}/joinRoom',
                                                    data: {
                                                        'room-code': roomCode,
                                                        'guest_id': '{{ auth()->id() }}'
                                                    },
                                                    dataType: 'text'
                                                }).done(function() {
                                                    bootbox.alert({
                                                        message: "Prepare to join the room!",
                                                        size: 'small',
                                                        centerVertical: true,
                                                        closeButton: false,
                                                        buttons: {
                                                            ok: {
                                                                className: 'btn-dark pulse-red',
                                                                label: 'Oki'
                                                            }
                                                        },
                                                        callback: function(){
                                                            window.location.href = '{{ url('/room/') }}' + '/' + roomCode + '/invited';
                                                        }
                                                    });
                                                });
                                            } else if (guestId == '{{ auth()->id() }}') {
                                                bootbox.alert({
                                                    message: "Get back to the room!",
                                                    size: 'small',
                                                    centerVertical: true,
                                                    closeButton: false,
                                                    buttons: {
                                                        ok: {
                                                            className: 'btn-dark pulse-red',
                                                            label: 'Oki'
                                                        }
                                                    },
                                                    callback: function(){
                                                        window.location.href = '{{ url('/room/') }}' + '/' + roomCode + '/invited';
                                                    }
                                                });
                                            } else if (hostId == '{{ auth()->id() }}') {
                                                bootbox.alert({
                                                    message: "Return to your room!",
                                                    size: 'small',
                                                    centerVertical: true,
                                                    closeButton: false,
                                                    buttons: {
                                                        ok: {
                                                            className: 'btn-dark pulse-red',
                                                            label: 'Oki'
                                                        }
                                                    },
                                                    callback: function(){
                                                        window.location.href = '{{ url('/room/') }}' + '/' + roomCode;
                                                    }
                                                });
                                            }
                                        });
                                    }
                                </script>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <script>

                    </script>
                    @endif
                </div>
                <div class="text-center mx-auto" style="width: fit-content;" data-step="2" data-intro="Mở trang này trên điện thoại">
                @include('layout.partials.app.qrCode')
                </div>
            </div>
        </div>
    </div>
</div>
@include('layout.partials.app.fb')
@endsection
