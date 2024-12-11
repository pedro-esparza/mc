@if (session('status'))
    <div class="alert alert-success" role="alert">
        {{ session('status') }}
    </div>
@endif
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
@if ($errors->any())
    <div class="alert alert-warning">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
@if(auth()->check())
    <h2 class="mt-3"><i class="fas fa-gamepad-alt"></i> Tournament</h2>
    <form method="POST" id="create-form">
        <div class="form-group">
            @csrf
            <input name="room-code" type="hidden" value="{{ md5(time()) }}" disabled readonly>
            <button data-step="1" data-intro="Ấn vào đây để tạo phòng thi đấu với các kỳ thủ khác" type="submit" class="btn btn-dark btn-lg my-3"><i class="fad fa-plus-octagon"></i> Create a new room</button>
        </div>
    </form>
    <script>
    $('#create-form').on('submit', function(e){
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: '{{ url('/api') }}/hasRoomcode',
            data: {
                'room-code': '{{ md5(time()) }}'
            },
            dataType: 'text'
        }).done(function(data){
            if (data == 'no') {
                bootbox.prompt({
                    title: "Please create a name for your new Room:",
                    locale: 'en',
                    centerVertical: true,
                    closeButton: false,
                    maxlength: 32,
                    buttons: {
                        confirm: {
                            label: '<i class="fas fa-check"></i> Proceed',
                            className: 'btn-dark'
                        }
                    },
                    callback: function(roomName){
                        if (roomName != null) {
                            if (roomName.trim().length === 0 || roomName.length === 0) {
                                bootbox.alert({
                                    message: "Please enter a name!",
                                    size: 'small',
                                    locale: 'en',
                                    centerVertical: true,
                                    closeButton: false,
                                    buttons: {
                                    ok: {
                                        className: 'btn-dark'
                                    }
                                    },
                                    callback: function () {
                                        $('#create-form').trigger('submit');
                                    }
                                });
                            } else {
                                $.ajax({
                                    type: "POST",
                                    url: '{{ url('/api') }}/createRoom',
                                    data: {
                                        'room-code': '{{ md5(time()) }}',
                                        'room-name': roomName,
                                        'FEN': 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1',
                                        'pass': '',
                                        'host_id': '{{ auth()->id() }}'
                                    },
                                    dataType: 'text'
                                }).done(function() {
                                    bootbox.alert({
                                        message: "You have created the room!",
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
                                            window.location.href = '{{ url('/room/') }}' + '/' + '{{ md5(time()) }}';
                                        }
                                    });
                                });
                            }
                        }
                    }
                });
            } else if (data == 'yes') {
                bootbox.alert({
                    message: "The room code is duplicated. Please try again!",
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
                        setTimeout(() => {
                            location.reload();
                        }, 500);
                    }
                });
            }
        });
    })
    </script>
@else
<div class="alert alert-secondary" role="alert">
    <a data-step="1" data-intro="Ấn vào đây để đăng nhập vào thi đấu xếp hạng" class="showPromotion" href="{{ url('/login') }}">Login</a> to join
</div>
@endif