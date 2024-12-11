function score = evaluateBoard(gameState)
    % Define los valores de las piezas.
    pieceValues = struct(...
        'P', 1, 'p', -1, ...
        'R', 5, 'r', -5, ...
        'N', 3, 'n', -3, ...
        'B', 3, 'b', -3, ...
        'Q', 9, 'q', -9, ...
        'K', 100, 'k', -100 ...
    );

    score = 0;
    board = gameState.board;

    % Recorre el tablero y suma los valores de las piezas.
    for i = 1:size(board, 1)
        for j = 1:size(board, 2)
            piece = board{i, j};
            if ~isempty(piece)
                if isfield(pieceValues, piece)
                    score = score + pieceValues.(piece);
                else
                    error(['Clave no válida para pieza: ', piece]);
                end
            end
        end
    end
end