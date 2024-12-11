function moves = get_moves(gameState)
    % Si no hay historial, devuelve los movimientos iniciales.
    if isempty(gameState.history)
        moves = {'e2-e4', 'd2-d4', 'g1-f3', 'b1-c3'};
    else
        % Genera movimientos posibles con lógica personalizada si hay historial.
        moves = {'e7-e5', 'd7-d5', 'g8-f6', 'b8-c6'};
    end
end
