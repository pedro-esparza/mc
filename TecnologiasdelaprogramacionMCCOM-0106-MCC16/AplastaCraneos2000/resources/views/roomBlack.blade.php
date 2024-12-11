@extends('layout.gamelayout')
@section('aboveBoard')
@if (isset($room->host_id))
<h5 id="room-title" class="text-center mb-2"><span id="host-title">{!! app('App\Http\Controllers\UserController')::renderPlayerNameRoom($room->host_id) !!}</span> <span id="guest-title">{!! app('App\Http\Controllers\UserController')::renderPlayerNameRoom($room->guest_id) !!}</span></h5>
@else
<h5 class="text-center mb-2" data-toggle="tooltip" data-placement="top" title="You are Black">You are Black</h5>
<span class="d-block mx-auto text-center" id="room-name">Room's name: {{ $room->name }}</span>
@endif
@endsection
@section('aboveContent')
<p id="room-code" class="w-100 text-center mt-2">
  <span class="alert alert-info d-inline-block" role="alert" data-toggle="tooltip" data-placement="bottom" data-original-title="Remember this room code"><i class="fad fa-trophy-alt"></i> Room code: {{ $roomCode }}</span>
</p>
@endsection
@section('belowContent')
<script>
@if ($room['pass'] != '')
$(document).ready(function() {
  bootbox.prompt({
    title: "Please enter the password for this Room:",
    required: true,
    centerVertical: true,
    buttons: {
      confirm: {
        label: '<i class="fas fa-check"></i> Enter',
        className: 'btn-dark'
      }
    },
    callback: function(password){
      if (password != null) {
        $.ajax({
          type: "GET",
          url: '{{ url('/api') }}/getPass/' + '{{ $roomCode }}',
          dataType: 'text'
        }).done(function(data) {
          if (data != password) {
            bootbox.alert({
              message: "Wrong password! You will be redirected to the Home page",
              size: 'small',
              centerVertical: true,
              buttons: {
                ok: {
                  className: 'btn-dark'
                }
              },
              callback: function () {
                window.location.href = '{{ url('/') }}';
              }
            });
          }
        });
      } else {
        bootbox.alert({
          message: "You clicked Cancel! You will be redirected to the Home page",
          size: 'small',
          centerVertical: true,
          buttons: {
            ok: {
              className: 'btn-dark'
            }
          },
          callback: function () {
            window.location.href = '{{ url('/') }}';
          }
        });
      }
    }
  });
});
@endif
var board = null
var game = new Chess()
var currentFEN = game.fen()

var promoting = false;
var piece_theme = $('#piecesUrl').val() + '/img/chesspieces/alpha/{piece}.png';
var promotion_dialog = $('#promotion-dialog');
var promote_to = '';
var whiteSquareGrey = '#a9a9a9'
var blackSquareGrey = '#696969'

function updateFenCode(roomCode) {
  board.position(game.fen(), true);
  game.load(game.fen());
  $.ajax({
    type: "POST",
    url: '{{ url('/api') }}/updateFEN',
    data: {
      'room-code': roomCode,
      'FEN': game.fen()
    },
    dataType: 'text'
  });
}

function manipulateRoom(roomCode) {
  $.ajax({
    type: "GET",
    url: '{{ url('/api') }}/readFEN/' + roomCode,
    dataType: 'text'
  }).done(function(newFEN) {
    if (newFEN != currentFEN) {
      currentFEN = game.fen();
      if (newFEN == game.fen()) {
        // my move
        board.position(newFEN, true);
        game.load(newFEN);
      } else {
        // opponent's move
        board.position(newFEN, true);
        game.load(newFEN);
        nuocCo.play();
      }
    }
    updateStatus()
  });
}

function updateResult(roomCode, result) {
  @if(auth()->check() && (auth()->id() == $room->host_id || auth()->id() == $room->guest_id))
  $.ajax({
    type: "POST",
    url: '{{ url('/api') }}/updateResult',
    data: {
      'room-code': roomCode,
      'result': result,
      'id': '{{ auth()->id() }}'
    },
    dataType: 'json'
  }).done(function(data) {
    bootbox.alert({
      message: data.success,
      size: 'small',
      centerVertical: true,
      closeButton: false,
      locale: 'en',
      buttons: {
        ok: {
          className: 'btn-dark'
        }
      },
      callback: function () {
        $.ajax({
          type: "POST",
          url: '{{ url('/api') }}/updatePoints',
          data: {
            'id': '{{ auth()->id() }}'
          },
          dataType: 'text'
        }).done(function(){
          const updatePointsTimeout = setTimeout(function(){
            window.location.href = "{{ url('/rooms') }}";
          }, 500);
        });
      }
    });
  });
  @elseif(!isset($room->host_id))
  $.ajax({
    type: "POST",
    url: '{{ url('/api') }}/updateSideResult',
    data: {
      'room-code': roomCode,
      'result': result,
      'lang': 'en',
      'side': 'black'
    },
    dataType: 'json'
  }).done(function(data) {
    bootbox.alert({
      message: data.success,
      size: 'small',
      centerVertical: true,
      closeButton: false,
      locale: 'en',
      buttons: {
        ok: {
          className: 'btn-dark'
        }
      },
      callback: function () {
        window.location.href = "{{ url('/rooms') }}";
      }
    });
  });
  @endif
}

function removeGreySquares () {
  $('#chess-board .square-55d63').css('background', '')
}

function greySquare (square) {
  var $square = $('#chess-board .square-' + square)

  var background = whiteSquareGrey
  if ($square.hasClass('black-3c85d')) {
    background = blackSquareGrey
  }

  $square.css('background', background)
}

function onDragStart (source, piece, position, orientation) {
  // do not pick up pieces if the game is over
  if (game.game_over()) return false

  // only pick up pieces for the side to move
  if ((game.turn() === 'w' && piece.search(/^b/) !== -1) ||
      (game.turn() === 'b' && piece.search(/^w/) !== -1)) {
    return false
  }
  
  if ((board.orientation() == 'white' && game.turn() === 'b') || (board.orientation() == 'black' && game.turn() === 'w')) {
    return false;
  }
}

function onDrop (source, target) {
  removeGreySquares();

  move_cfg = {
    from: source,
    to: target,
    promotion: 'q'
  };
  var move = game.move(move_cfg);
  // illegal move
  if (move === null) {
    return 'snapback';
  } else {
    game.undo(); //move is ok, now we can go ahead and check for promotion
  }
 
  // is it a promotion?
  var source_rank = source.substring(2,1);
  var target_rank = target.substring(2,1);
  var piece = game.get(source).type;

  if (piece === 'p' &&
      ((source_rank === '7' && target_rank === '8') || (source_rank === '2' && target_rank === '1'))) {
        promoting = true;

    // get piece images
    $('.promotion-piece-q').attr('src', getImgSrc('q'));
    $('.promotion-piece-r').attr('src', getImgSrc('r'));
    $('.promotion-piece-n').attr('src', getImgSrc('n'));
    $('.promotion-piece-b').attr('src', getImgSrc('b'));

    //show the select piece to promote to dialog
    promotion_dialog.dialog({
      modal: true,
      height: 46,
      width: 184,
      resizable: true,
      draggable: false,
      close: onDialogClose,
      closeOnEscape: false,
      dialogClass: 'noTitleStuff'
    }).dialog('widget').position({
      of: $('#chess-board'),
      my: 'middle middle',
      at: 'middle middle',
    });
    //the actual move is made after the piece to promote to
    //has been selected, in the stop event of the promotion piece selectable
    return;
  }

  // no promotion, go ahead and move
  makeMove(game, move_cfg);
  updateStatus()
}

function getImgSrc(piece) {
  return piece_theme.replace('{piece}', game.turn() + piece.toLocaleUpperCase());
}

var onDialogClose = function() {
  // console.log(promote_to);
  move_cfg.promotion = promote_to;
  makeMove(game, move_cfg);
}

function makeMove(game, cfg) {
  // see if the move is legal
  var move = game.move(cfg);
  // illegal move
  if (move === null) return 'snapback';
}

function onMouseoverSquare (square, piece) {
  // get list of possible moves for this square
  let moves = game.moves({
    square: square,
    verbose: true
  });

  // exit if there are no moves available for this square
  if (moves.length === 0) return;

  // highlight the square they moused over
  greySquare(square);

  // highlight the possible squares for this piece
  for (let i = 0; i < moves.length; i++) {
    greySquare(moves[i].to);
  }
}

function onMouseoutSquare (square, piece) {
  removeGreySquares();
}

function onSnapEnd () {
  nuocCo.play();
  updateFenCode('{{ $roomCode }}');
  if (promoting) return;
  promoting = false;
}

function updateBoard(board) {
  board.position(game.fen(), false);
  promoting = false;
}
function updatePlayersTitle() {
  @if (auth()->id() == $room->host_id || auth()->id() == $room->guest_id)
  $.ajax({
    type: "POST",
    url: '{{ url('/api') }}/updatePlayersStatus',
    data: {
      'room-code': '{{ $roomCode }}'
    },
    dataType: 'text'
  }).done(function(){
    $.ajax({
      type: "POST",
      url: '{{ url('/api') }}/renderPlayersTitle',
      data: {
        'room-code': '{{ $roomCode }}'
      },
      dataType: 'text'
    }).done(function(data){
      $('h5#room-title').html(data);
    });
  });
  @else
  $.ajax({
    type: "POST",
    url: '{{ url('/api') }}/renderPlayersTitle',
    data: {
      'room-code': '{{ $roomCode }}'
    },
    dataType: 'text'
  }).done(function(data){
    $('h5#room-title').html(data);
  });
  @endif
}

const updateChessBoard = setInterval(function() {
  manipulateRoom('{{ $roomCode }}');
}, 1000);

@if (isset($room->host_id))
const updatePlayers = setInterval(function() {
  updatePlayersTitle();
}, 5000);
@endif

function updateStatus () {
  var status = ''

  var moveColor = 'Blancas'
  if (game.turn() === 'b') {
    moveColor = 'Negras'
  }

  // checkmate?
  if (game.in_checkmate()) {
    status = moveColor + ' recibió jaquemate'
    if (game.turn() === 'b') {
      updateResult('{{ $roomCode }}', '1');
    } else if (game.turn() === 'r') {
      updateResult('{{ $roomCode }}', '-1');
    }
  }

  // draw?
  else if (game.in_draw()) {
    status = 'Posición de Tablas'
    updateResult('{{ $roomCode }}', '0');
  }

  // game still on
  else {
    status = moveColor + " en turno"
    if (game.game_over() && !game.in_draw()) {
      if (game.turn() === 'b') {
        updateResult('{{ $roomCode }}', '1');
      } else if (game.turn() === 'r') {
        updateResult('{{ $roomCode }}', '-1');
      }
    }

    // check?
    if (game.in_check()) {
      status += ', ' + moveColor + ' está en jaque'
    }
  }

  $('#game-status').html(status);
  $('#header-status').html(': '+status);
  if (game.game_over()) {
    hetTran.play();
    $('#game-over').removeClass('d-none').addClass('d-inline-block').html('<i class="fad fa-flag-checkered"></i> Fin del Juego');;
    $('#header-status').html(': '+status+' - Fin del Juego');
    // evtSource.close();
    clearInterval(updateChessBoard);
    @if (isset($room->host_id))
    clearInterval(updatePlayers);
    @endif
  }
  if (game.fen().includes('resign')) {
    $('#header-status').html(': '+status+' - se Rinde');
    $('#game-over').html('<i class="fad fa-flag-checkered"></i> se Rinde');
    $('#resign').addClass('disabled').attr('aria-disabled', true);
    updateResult('{{ $roomCode }}', '0');
  }
}
function undo () {
  if (history.length > 1) {
    history.pop();
    board.position(history[history.length - 1]);
  }
  console.log(history);
}
var config = {
  @if ((isset($room->guest_id) && auth()->id() == $room->guest_id) || !isset($room->host_id))
  draggable: true,
  @else
  draggable: false,
  @endif
  position: 'start',
  onDragStart: onDragStart,
  onDrop: onDrop,
  onMouseoutSquare: onMouseoutSquare,
  onMouseoverSquare: onMouseoverSquare,
  onSnapEnd: onSnapEnd,
  showNotation: false,
  orientation: "black"
  //pieceTheme: '/static/img/xiangqipieces/traditional/{piece}.svg'

};
board = Chessboard('chess-board', config)
$(window).resize(board.resize);
updateStatus()
$('#undo').on('click', undo);

// let evtSource = new EventSource("{{ url('/api') }}/getFEN/{{ $roomCode }}");

// evtSource.onmessage = function (e) {
//   let newFEN = e.data;
//     console.log(newFEN);
//     if (newFEN != currentFEN) {
//     currentFEN = game.fen();
//     $.ajax({
//       type: "POST",
//       url: '{{ url('/api') }}/updateFEN',
//       data: {
//         'room-code': '{{ $roomCode }}',
//         'FEN': newFEN
//       },
//       dataType: 'text'
//     });
//     if (newFEN == game.fen()) {
//       // my move
//       board.position(newFEN, true);
//       game.load(newFEN);
//     } else {
//       // opponent's move
//       board.position(newFEN, true);
//       game.load(newFEN);
//       if (!game.fen().includes('resign')) {
//         nuocCo.play();
//       }
//     }
//   }
//   updateStatus();
// };
$('#resign').on('click', function() {
  game.load(game.fen() + ' resign');
  updateFenCode('{{ $roomCode }}');
  updateStatus();
});
// init promotion piece dialog
$("#promote-to").selectable({
stop: function() {
  $( ".ui-selected", this ).each(function() {
    var selectable = $('#promote-to li');
    var index = selectable.index(this);
    if (index > -1) {
      var promote_to_html = selectable[index].innerHTML;
      var span = $('<div>' + promote_to_html + '</div>').find('span');
      promote_to = span[0].innerHTML;
    }
    promotion_dialog.dialog('close');
    $('.ui-selectee').removeClass('ui-selected');
    updateBoard(board);
    updateStatus();
    updateFenCode('{{ $roomCode }}');
  });
}
});
</script>
@include('layout.partials.comments')
@endsection