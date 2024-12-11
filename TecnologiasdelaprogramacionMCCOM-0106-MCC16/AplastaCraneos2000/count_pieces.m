function pieceCount = count_pieces(gameState)
    % Asume que gameState tiene un campo 'board' que representa el tablero.
    % Ajusta esta lógica según tu implementación.
    if isfield(gameState, 'board')
        board = gameState.board;
        pieceCount = sum(~cellfun(@isempty, board(:))); % Cuenta piezas no vacías.
    else
        error('El estado del juego no tiene un campo "board".');
    end
end
