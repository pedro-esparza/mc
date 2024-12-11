@extends('layout.gamelayout')
@section('aboveContent')
<h3 class="text-center my-2">Setting up the puzzle</h3>
@if ($board != '')
<p class="w-100 text-center mt-2">
  <i class="fad fa-external-link-alt"></i> Inviting friend to play by sending the link.
</p>
<div id="copy-url" class="input-group mb-2 w-50 mx-auto" data-toggle="tooltip" data-placement="bottom" data-original-title="Click to copy">
  <div class="input-group-prepend">
    <span class="input-group-text" id="url-addon"><i class="fal fa-copy"></i></span>
  </div>
  <input type="text" class="form-control" id="url" value="{{ url('/') }}/puzzle/{{ $board }}">
</div>
<script>
$('#copy-url').on('click', function() {
  copyToClipboard('#url');
  selectText('#url')
});
</script>
@endif
@endsection
@section('belowContent')
<p class="w-100 text-center mt-4">
  <a id="solve-puzzle" class="btn btn-dark btn-lg" href="{{ url('/solve-puzzle') }}"><i class="fad fa-abacus"></i> Solve puzzle</a>
</p>
<p class="w-100 text-center mt-4">
  <a id="new-board" class="w-25 btn btn-dark btn-lg"><i class="fad fa-save"></i> Save board</a>
  <a id="undo" class="w-25 btn btn-light btn-lg"><i class="fad fa-redo-alt"></i> </a>
</p>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.5.0-beta4/html2canvas.min.js" integrity="sha512-OqcrADJLG261FZjar4Z6c4CfLqd861A3yPNMb+vRQ2JwzFT49WT4lozrh3bcKxHxtDTgNiqgYbEUStzvZQRfgQ==" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/file-saver@2.0.2/dist/FileSaver.min.js" integrity="sha256-u/J1Urdrk3nCYFefpoeTMgI5viU1ujCDu2fXXoSJjhg=" crossorigin="anonymous"></script>
<script>
@if ($board != '')
let history = ['{{ $board }}'];
@else
let history = ['8/8/8/8/8/8/8/8'];
@endif
function onSnapEnd () {
  if (board.fen() != history[history.length - 1]){
    history.push(board.fen());
  }
  console.log(history);
  nuocCo.play();
}
function undo () {
  if (history.length > 1) {
    history.pop();
    board.position(history[history.length - 1]);
  }
  console.log(history);
}
let game = new Chess();
const board = Chessboard('chess-board', {
  draggable: true,
  dropOffBoard: 'trash',
  sparePieces: true,
  @if ($board != '')
  position: '{{ $board }}',
  @endif
  showNotation: true,
  onSnapEnd: onSnapEnd
});
const ratio = $('#chess-board').height() / $('#chess-board').width();
function adjustBoard() {
  const scrollbarWidth = window.innerWidth - document.documentElement.clientWidth;
  width = ($(window).height() - 195) / ratio;
  width = Math.min(width, $('header > .container').width());
  height = width * ratio;
  $('#chess-board').css({'width': width});
  board.resize();
}
adjustBoard();
$(window).on('load resize', adjustBoard);
$(document).ready(adjustBoard);
$(window).resize(board.resize);
$('#switch').on('click', board.flip);
$('#new-board').on('click', function(){
  if (!game.validate_fen(board.fen() + ' w - - 0 1').valid) {
    bootbox.alert({
      message: "Invalid puzzle",
      size: 'small',
      centerVertical: true,
      closeButton: false,
      buttons: {
        ok: {
          className: 'btn-dark'
        }
      }
    });
  } else {
    window.location.href = "{{ URL::to('/puzzle/') }}/" + board.fen();
  }
});
$('#undo').on('click', undo);
$("#capture").on('click', function() {
  if (!game.validate_fen(board.fen() + ' w - - 0 1').valid) {
    bootbox.alert({
      message: "Invalid puzzle",
      size: 'small',
      centerVertical: true,
      closeButton: false,
      buttons: {
        ok: {
          className: 'btn-dark'
        }
      }
    });
  } else {
    html2canvas($(".board-b72b1"), {
      windowWidth: $(".board-b72b1").width(),
      windowHeight: $(".board-b72b1").height(),
      onrendered: function(canvas) {
        var context = canvas.getContext('2d');

        // Draw the Watermark
        context.font = '42px monospace';
        context.globalCompositeOperation = 'lighter';
        context.fillStyle = '#424242';
        context.textAlign = 'center';
        context.textBaseline = 'middle';
        context.fillText('CHESSROOM.TOP', canvas.width / 2, canvas.height / 2);

        canvas.toBlob(function(blob) {
          saveAs(blob, "board-{{ date('Y-m-d g-i a') }}.png"); 
        });
      }
    });
  }
});
$('#solve-puzzle').on('click auxclick', function(e){
  e.preventDefault();
  if (!game.validate_fen(board.fen() + ' w - - 0 1').valid) {
    bootbox.alert({
      message: "Invalid puzzle",
      size: 'small',
      centerVertical: true,
      closeButton: false,
      buttons: {
        ok: {
          className: 'btn-dark'
        }
      }
    });
  } else {
    window.location.href = "{{ URL::to('/solve-puzzle/') }}/" + board.fen() + " w - - 0 1";
  }
});
</script>
@endsection
