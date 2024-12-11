<!DOCTYPE html>
<html lang="en">

<head>
  @include('layout.partials.head')
</head>

<body class="{{ $bodyClass }}">
  @include('layout.partials.header')
  <main>
    <div class="container-fluid px-0" itemscope itemtype="http://schema.org/Game">
      <div class="container {{ isset($board) ? 'px-3 pb-0 pt-3' : 'p-3' }}">
        <audio id="nuoc-co">
          <source src="{{ URL::to('/') }}/sound/nuocCo.mp3" type="audio/mpeg">
          <source src="{{ URL::to('/') }}/sound/nuocCo.wav" type="audio/wav">
          Your browser does not support the audio element.
        </audio>
        <audio id="het-tran">
          <source src="{{ URL::to('/') }}/sound/hetTran.mp3" type="audio/mpeg">
          <source src="{{ URL::to('/') }}/sound/hetTran.wav" type="audio/wav">
          Your browser does not support the audio element.
        </audio>

        <div class="row">
          <div class="col-8">
            @yield('aboveBoard')

            <div id="chess-board" class="mx-auto h-auto"></div>
            <div id="promotion-dialog">
              <ol id="promote-to">
                <li class="ui-state-default"><span class="piece-name">q</span><img src="#" alt="q"
                    class="promotion-piece-q promotion-piece" /></li>
                <li class="ui-state-default"><span class="piece-name">r</span><img src="#" alt="r"
                    class="promotion-piece-r promotion-piece" /></li>
                <li class="ui-state-default"><span class="piece-name">n</span><img src="#" alt="n"
                    class="promotion-piece-n promotion-piece" /></li>
                <li class="ui-state-default"><span class="piece-name">b</span><img src="#" alt="b"
                    class="promotion-piece-b promotion-piece" /></li>
              </ol>
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
          }).done(function (data) {
            hostId = data.host_id;
            guestId = data.guest_id;
            if (hostId != '{{ auth()->id() }}' && guestId != '{{ auth()->id() }}') {
            $.ajax({
              type: "POST",
              url: '{{ url('/api') }}/joinRoom',
              data: {
              'room-code': roomCode,
              'guest_id': '{{ auth()->id() }}'
              },
              dataType: 'text'
            }).done(function () {
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
              callback: function () {
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
              callback: function () {
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
              callback: function () {
              window.location.href = '{{ url('/room/') }}' + '/' + roomCode;
              }
            });
            }
          });
          }
        </script>
      @endif

          </div>

          <div class="col-4 text-center d-flex align-items-center justify-content-center flex-column">
            @yield('aboveContent')            
            <div class="row">
              <input type="hidden" name="FEN" id="FEN" />
              <input type="hidden" name="piecesUrl" id="piecesUrl" value="{{ URL::to('/') }}" />
              @include('layout.partials.scripts')
              @yield('belowContent')
              @if (!isset($board))
          <script>
          function createRoom() {
            $.ajax({
            type: "POST",
            url: '{{ url('/api') }}/hasRoomcode',
            data: {
              'room-code': '{{ md5(time()) }}'
            },
            dataType: 'text'
            }).done(function (data) {
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
              callback: function (roomName) {
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
                    $('#create-match').trigger('click');
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
                  }).done(function () {
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
                    callback: function () {
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
              callback: function () {
                setTimeout(() => {
                location.reload();
                }, 500);
              }
              });
            }
            });
          }
          const ratio = $('#chess-board').height() / $('#chess-board').width();
          function adjustBoard() {
            const scrollbarWidth = window.innerWidth - document.documentElement.clientWidth;
            width = ($(window).height() - 220) / ratio;
            if ($(window).width() >= $(window).height() && $(window).height() < 576) {
            width = ($(window).height() - 50) / ratio;
            }
            width = Math.min(width, $('header > .container').width());
            height = width * ratio;
            $('#chess-board').css({ 'width': width });
            board.resize();
          }
          adjustBoard();
          $(window).on('load resize', adjustBoard);
          $(document).ready(adjustBoard);
          $('#share-board').on('click', function () {
            $(this).attr('href', $(this).attr('href') + '/' + game.fen());
          });
          </script>
        @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
</body>

</html>