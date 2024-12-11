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
                    <i class="fas fa-gamepad-alt"></i> Tournament
                </div>
                <div class="card-body">
                    @include('layout.partials.app.createRoom')
                    <span style="background-color: #ffffff; margin-top: -70px;" class="d-block w-100 pb-5 mb-5" id="result-board"></span>
                    <h2 data-step="2" data-intro="Danh sách 10 kỳ thủ nhiều điểm nhất"><i class="fas fa-medal"></i> TOP 10</h2>
                    <div class="table-responsive">
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
                                @foreach($matchUsers as $user)
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
                    <span style="background-color: #ffffff; margin-top: -70px;" class="d-block w-100 pb-5 mb-5" id="result-board"></span>
                    <h2 data-step="3" data-intro="Danh sách các ván đấu đang diễn ra" class="mt-3"><i class="fas fa-list"></i> {{ $playingRooms->total() }} ongoing matches ({!! app('App\Http\Controllers\UserController')::renderOnlinePlayers() !!})</h2>
                    <div class="table-responsive mb-3">
                        <table class="table table-striped table-hover" id="results-table">
                            <thead>
                                <tr>
                                    <th scope="col">Room name</th>
                                    <th scope="col">Host</th>
                                    <th scope="col">Guest</th>
                                    <th scope="col">Turn</th>
                                    <th scope="col">Play</th>
                                    <th scope="col">Last played</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{ $playingRooms->links('vendor.pagination.match') }}
                                @foreach($playingRooms as $room)
                                <tr data-code="{{ $room->code }}" data-fen="{{ $room->fen }}" data-name="{{ $room->name }}">
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
                                        @if (!isset($room->result))
                                            @if (auth()->check())
                                                @if (isset($room->guest_id))
                                                <a class="btn btn-sm btn-dark" href="javascript:joinMatch('{{ $room->code }}')"><i class="fad fa-mouse"></i> Play</a>
                                                @else
                                                <a class="btn btn-sm btn-dark" href="javascript:joinMatch('{{ $room->code }}')"><i class="fad fa-mouse"></i> Play</a>
                                                @endif
                                            @else
                                                @if (isset($room->guest_id))
                                                <a class="btn btn-sm btn-dark showPromotion" href="{{ url('/dang-nhap') }}"><i class="fad fa-sign-in"></i> Login</a>
                                                @else
                                                <a class="btn btn-sm btn-dark showPromotion" href="{{ url('/dang-nhap') }}"><i class="fad fa-sign-in"></i> Login</a>
                                                @endif
                                            @endif
                                        @else
                                            <span class="text-dark">Finished</span>
                                        @endif
                                    </td>
                                    <td>{{ $room->modified_at }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if (auth()->check())
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
                                                className: 'btn-dark',
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
                                            className: 'btn-dark',
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
                                            className: 'btn-dark',
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
                    <script>
                        $(document).ajaxStart(function(){
                            $('body').addClass('waiting');
                        }).ajaxComplete(function(){
                            $('body').removeClass('waiting');
                        })
                    </script>
                    @endif

                    {{-- {{ __('You are logged in!') }} --}}
                </div>
                <div class="text-center mx-auto" style="width: fit-content;" data-step="4" data-intro="Mở trang này trên điện thoại">
                @include('layout.partials.app.qrCode')
                </div>
            </div>
        </div>
    </div>
</div>
@include('layout.partials.app.fb')
@endsection
