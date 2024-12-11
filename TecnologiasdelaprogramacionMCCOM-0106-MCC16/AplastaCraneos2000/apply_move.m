function newGameState = apply_move(gameState, move)
    % Aplica un movimiento al estado actual del juego.
    newGameState = gameState;
    newGameState.turn = switch_turn(gameState.turn);

    % Actualiza el historial con el nuevo movimiento.
    if isfield(newGameState, 'history')
        newGameState.history = [newGameState.history; {move}];
    else
        newGameState.history = {move};
    end
end

function nextTurn = switch_turn(currentTurn)
    if strcmp(currentTurn, 'w')
        nextTurn = 'b';
    else
        nextTurn = 'w';
    end
end
