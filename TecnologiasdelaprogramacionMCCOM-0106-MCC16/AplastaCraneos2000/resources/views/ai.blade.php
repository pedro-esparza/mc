@extends('layout.gamelayout')
@section('aboveContent')
<div class="text-center py-3">
  <h3 class="fw-bold text-warning">🏆 Jugando contra la IA</h3>
  <h4 class="text-light bg-dark d-inline-block py-1 px-3 rounded">{{ $levelTxt }}</h4>
</div>

<div class="text-center mt-4">
  <div class="bg-dark text-light py-3 px-4 rounded shadow-lg d-inline-block">
    <p class="mb-1 fw-bold text-uppercase">Estado del Juego:</p>
    <span id="game-status" class="d-inline-block rounded-pill px-4 py-2 bg-success text-dark fw-bold">Turno del
      jugador</span>
  </div>
</div>

<div class="text-center py-3">
  <div class="d-flex justify-content-center flex-wrap gap-3">
    <a href="{{ url('/newbie') }}" class="btn btn-outline-success btn-lg px-4 py-2 fw-bold rounded-pill">
      <i class="fas fa-gamepad"></i> Amateur
    </a>
    <a href="{{ url('/easy') }}" class="btn btn-outline-primary btn-lg px-4 py-2 fw-bold rounded-pill">
      <i class="fas fa-gamepad"></i> Genio
    </a>
    <a href="{{ url('/normal') }}" class="btn btn-outline-info btn-lg px-4 py-2 fw-bold rounded-pill">
      <i class="fas fa-gamepad"></i> Crack
    </a>
    <a href="{{ url('/hard') }}" class="btn btn-outline-warning btn-lg px-4 py-2 fw-bold rounded-pill">
      <i class="fas fa-gamepad"></i> Elite
    </a>
    <a href="{{ url('/hardest') }}" class="btn btn-outline-danger btn-lg px-4 py-2 fw-bold rounded-pill">
      <i class="fas fa-skull"></i> Aplasta Cráneo
    </a>
  </div>
</div>

@endsection
@section('belowContent')
<p class="w-100 text-center mt-4">
  <a class="w-25 btn btn-dark btn-lg text-light" id="undo"><i class="fad fa-undo-alt"></i> </a>
  <a class="w-25 btn btn-warning btn-lg text-light" href="{{ url('/play-alone') }}"><i class="fad fa-user"></i> </a>
  <a class="btn btn-info btn-lg px-5 py-3 fw-bold rounded-pill shadow-lg" id="reset"><i class="fad fa-redo-alt"></i>
    Reiniciar</a>
</p>
<script>
  var board = null
  var game = new Chess()

  var promoting = false;
  var piece_theme = $('#piecesUrl').val() + '/img/chesspieces/alpha/{piece}.png';
  var promotion_dialog = $('#promotion-dialog');
  var promote_to = '';
  var whiteSquareGrey = '#a9a9a9'
  var blackSquareGrey = '#696969'

  function removeGreySquares() {
    $('#chess-board .square-55d63').css('background', '')
  }

  function greySquare(square) {
    var $square = $('#chess-board .square-' + square)

    var background = whiteSquareGrey
    if ($square.hasClass('black-3c85d')) {
      background = blackSquareGrey
    }

    $square.css('background', background)
  }

  function onDragStart(source, piece, position, orientation) {
    if (game.in_checkmate() === true || game.in_draw() === true || piece.search(/^b/) !== -1) {
      return false;
    }
  }

  function makeBestMove() {
    var aiWorker = new Worker('/js/aiWorker.js');
    var bestMove;
    aiWorker.postMessage({
      fen: game.fen(),
      depth: {{ $level }}
  });
    aiWorker.onmessage = function (e) {
      bestMove = e.data;
      console.log(bestMove);
      game.ugly_move(bestMove);
      board.position(game.fen());
      nuocCo.play();
      updateStatus();
    }
  }

  function onDrop(source, target) {
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
    var source_rank = source.substring(2, 1);
    var target_rank = target.substring(2, 1);
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
    // make random legal move for black
    makeBestMove();
  }

  function getImgSrc(piece) {
    return piece_theme.replace('{piece}', game.turn() + piece.toLocaleUpperCase());
  }

  var onDialogClose = function () {
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

  // update the board position after the piece snap
  // for castling, en passant, pawn promotion
  function onMouseoverSquare(square, piece) {
    // get list of possible moves for this square
    var moves = game.moves({
      square: square,
      verbose: true
    })

    // exit if there are no moves available for this square
    if (moves.length === 0) return

    // highlight the square they moused over
    greySquare(square)

    // highlight the possible squares for this piece
    for (var i = 0; i < moves.length; i++) {
      greySquare(moves[i].to)
    }
  }

  function onMouseoutSquare(square, piece) {
    removeGreySquares()
  }

  function onSnapEnd() {
    board.position(game.fen())
    nuocCo.play();
    updateStatus();
    if (promoting) return;
    promoting = false;
  }

  function updateBoard(board) {
    board.position(game.fen(), false);
    promoting = false;
  }

  function updateStatus() {
    var status = ''

    var moveColor = 'Blancas'
    if (game.turn() === 'b') {
      moveColor = 'Negras'
    }

    // checkmate?
    if (game.in_checkmate()) {
      status = moveColor + ' recibió jaquemate'
    }

    // draw?
    else if (game.in_draw()) {
      status = 'Posición de Tablas'
    }

    // game still on
    else {
      status = moveColor + " en turno"

      // check?
      if (game.in_check()) {
        status += ', ' + moveColor + ' está en jaque'
      }
    }

    $('#game-status').html(status);
    $('#header-status').html(': ' + status);
    if (game.game_over()) {
      hetTran.play();
      $('#header-status').html(': ' + status + ' - Fin del Juego');
      $('#game-over').removeClass('d-none').addClass('d-inline-block').html('<i class="fad fa-flag-checkered"></i> Fin del Juego');
    }
    if (game.fen().includes('resign')) {
      $('#header-status').html(': ' + status + ' - se Rinde');
      $('#game-over').html('<i class="fad fa-flag-checkered"></i> se Rinde');
      $('#resign, #switch').addClass('disabled').attr('aria-disabled', true);
      config.draggable = false;
    }
  }

  var config = {
    draggable: true,
    position: 'start',
    onDragStart: onDragStart,
    onDrop: onDrop,
    onMouseoutSquare: onMouseoutSquare,
    onMouseoverSquare: onMouseoverSquare,
    onSnapEnd: onSnapEnd,
    showNotation: false
  }

  board = Chessboard('chess-board', config)
  $(window).resize(board.resize);

  updateStatus()
  $(document).ready(function () {
    $('#FEN').val(game.fen());
  });
  $('#resign').on('click', function () {
    game.load(game.fen() + ' resign');
    updateStatus();
  });
  $('#undo').on('click', function () {
    game.undo();
    game.undo();
    board.position(game.fen());
    nuocCo.play();
    updateStatus();
  });
  $('#reset').on('click', function () {
    board.position('rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR');
    game.load('rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1');
    updateStatus();
    $('#game-over').removeClass('d-inline-block').addClass('d-none');
    $('#resign, #switch').removeClass('disabled').attr('aria-disabled', false);
    config.draggable = true;
  });
  $('#switch').on('click', function () {
    board.flip();
    makeBestMove();
  });
  // init promotion piece dialog
  $("#promote-to").selectable({
    stop: function () {
      $(".ui-selected", this).each(function () {
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
      });
    }
  });
</script>
@endsection