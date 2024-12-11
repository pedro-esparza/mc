@extends('layout.mainlayout')
@section('aboveContent')
<div class="container-fluid game px-0">
  <div class="container p-3">
    <h2 class="h1-responsivefooter text-center my-4">Rooms</h2>
    <div class="dropdown mx-auto text-center mb-3">
      <button class="btn btn-dark btn-lg dropdown-toggle" type="button" id="hostDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fad fa-play-circle" data-toggle="tooltip" data-placement="bottom" title="Play now"></i> Play online
      </button>
      <div class="dropdown-menu" aria-labelledby="hostDropdown" id="create-room" data-room="{{ md5(time()) }}" href="{{ URL::to('/') }}/room/{{ md5(time()) }}">
        @if (!auth()->check())
        <a data-toggle="tooltip" data-placement="bottom" title="Login to enter tournament" class="dropdown-item thi-dau" style="cursor: pointer !important;" href="{{ URL::to('/login') }}"><i class="fas fa-sign-in text-dark"></i> Login</a>
        @else
        <a data-toggle="tooltip" data-placement="bottom" title="Compete to rank" id="create-match" class="dropdown-item thi-dau" style="cursor: pointer !important;" href="javascript:createRoom();"><i class="fas fa-trophy-alt text-dark"></i> Compete</a>
        @endif
        <a data-toggle="tooltip" data-placement="bottom" title="Play without password" id="create-public-room" class="dropdown-item" style="cursor: pointer !important;"><i class="fas fa-globe-asia text-dark"></i> Public</a>
        <a data-toggle="tooltip" data-placement="bottom" title="Play with password" id="create-private-room" class="dropdown-item" style="cursor: pointer !important;"><i class="fas fa-lock text-dark"></i> Private</a>
        @if ($randomRoom != null)
        <a data-toggle="tooltip" data-placement="bottom" title="Play in random Public room" id="random-room" class="dropdown-item" style="cursor: pointer !important;" href="{{ URL::to('/') }}/room/{{ $randomRoom['code'] }}/random"><i class="fas fa-random text-dark"></i> Random Room</a>
        @endif
      </div>
    </div>
    <div data-step="2" data-intro="List of all matches" class="table-responsive">
      <table id="rooms" class="table table-bordered table-hover table-striped table-sm">
        <thead class="thead-light">
          <tr>
            <th class="text-center" scope="col">Room name</th>
            <th class="text-center" scope="col">Player's turn</th>
            <th class="text-center" scope="col">Result</th>
            <th class="text-center" scope="col">Actions</th>
            <th class="text-center" scope="col">Last played</th>
          </tr>
        </thead>
        <tbody style="background-color: whitesmoke;">
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
@section('belowContent')
<div class="modal fade" id="HoveredBoardModal" tabindex="-1" role="dialog" aria-label="HoveredBoard" aria-hidden="true" data-backdrop="static" data-keyboard="false" href="">
  <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 320px; margin: auto;">
    <div class="modal-content shadow-lg">
      <div class="modal-header">
        <h5 class="modal-title"><i class="far fa-eye"></i> Preview "<span></span>"</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body text-center">
        <div id="HoveredBoardBody"></div>
      </div>
    </div>
  </div>
</div>
<script>
$(document).ready(function () {
  console.log('List URL: ' + '{{ route('rooms.list') }}');
  var table = $('#rooms').DataTable({
    processing: true,
    serverSide: true,
    // ordering: false,
    // searching: false,
    ajax: {
      url: "{{ route('rooms.list') }}"
    },
    columns: [
      {
        data: 'code',
        name: 'code',
        orderable: true,
        searchable: true,
        className: 'text-center room-code'
      },
      {
        data: 'turn',
        name: 'turn',
        orderable: false,
        searchable: false,
        className: 'text-center'
      },
      {
        data: 'result',
        name: 'result',
        orderable: false,
        searchable: false,
        className: 'text-center'
      },
      {
        data: 'join',
        name: 'join',
        orderable: false,
        searchable: false,
        className: 'text-center room-action'
      },
      {
        data: 'time',
        name: 'time',
        orderable: true,
        searchable: true,
        className: 'text-right room-time'
      }
    ],
    'language': {
      'url': '{{ URL::to('/') }}/js/TableEn.json'
    },
    'createdRow': function(row, data, dataIndex) {
      var selectedFen = $(row).find('td.room-code > a').attr('data-fen');
      var selectedName = $(row).find('td.room-code > a').text();
      $(row).attr('data-fen', selectedFen);
      $(row).attr('data-name', selectedName);
    },
    'order': [[ 4, 'desc' ]],
    'drawCallback': function() {
      $('.tooltip').remove();
      $('[data-toggle="tooltip"]').tooltip(function() {
        html : true
      });
      $('#rooms .showPromotion').each(function(){
        $(this).on('click auxclick', function(e){
          e.preventDefault();
          $('#AdSenseModal').attr('data-url', $(this).attr('href')).modal('show');
          $('#adModalCloseBtn').attr('data-original-title', $('#AdSenseModal').attr('data-url'));
          $('#adModalCloseBtn').tooltip();
        });
      });
      $('#rooms > tbody > tr').each(function(index){
        var fenCode = $(this).attr('data-fen');
        var roomName = $(this).attr('data-name');
        $(this).children('td.room-action').find('.previewBtn').on('click', function(){
          $('#HoveredBoardModal').on('shown.bs.modal', function() {
            var container = $('#HoveredBoardBody');
            container.empty();
            var boardId = 'hoveredBoardId_' + index;
            var boardDiv = $('<div class="innerBoard">').attr('id', boardId);
            container.html(boardDiv);
            let boardConfig = {
              position: fenCode              
            };
            if (fenCode.includes(' w ')) {
              boardConfig.orientation = 'white';
            } else if (fenCode.includes(' b ')) {
              boardConfig.orientation = 'black';
            }
            var hoveredBoardDiv = Chessboard(boardId, boardConfig);
            $('#HoveredBoardModal .modal-title > span').text(roomName);
          });
          $('#HoveredBoardModal').modal('show');
        });
      });
      $('.watch-btn').each(function() {
        $(this).on('mouseenter', function() {
          if ($(this).find('i').hasClass('fa-lock')) {
            $(this).find('i').removeClass('fa-lock').addClass('fa-unlock');
          } else if ($(this).find('i').hasClass('fa-unlock')) {
            $(this).find('i').removeClass('fa-unlock').addClass('fa-lock');
          }
        }).on('mouseleave', function() {
          if ($(this).find('i').hasClass('fa-lock')) {
            $(this).find('i').removeClass('fa-lock').addClass('fa-unlock');
          } else if ($(this).find('i').hasClass('fa-unlock')) {
            $(this).find('i').removeClass('fa-unlock').addClass('fa-lock');
          }
        });
      });
    }
  });
  setInterval( function () {
    table.ajax.reload( null, false ); // user paging is not reset on reload
  }, 30000 );
  $('.dataTables_length').addClass('bs-select');
});
@if (auth()->check())
function createRoom() {
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
        title: "Give your Room a name:",
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
                  $('#create-room').trigger('click');
                }
              });
            } else {
              $.ajax({
                type: "POST",
                url: '{{ url('/api') }}/createRoom',
                data: {
                  'room-code': '{{ md5(time()) }}',
                  'ten-room': roomName,
                  'FEN': '{{ env('INITIAL_FEN') }}',
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
}
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
@endif
</script>
<input type="hidden" name="piecesUrl" id="piecesUrl" value="{{ URL::to('/') }}" />
@include('layout.partials.rules')
@endsection