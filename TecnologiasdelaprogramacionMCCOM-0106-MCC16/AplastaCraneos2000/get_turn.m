function turn = get_turn(gameState)
    % Asume que gameState tiene un campo 'turn' que indica el turno actual.
    % Si no existe, ajusta esta lógica según tu implementación.
    if isfield(gameState, 'turn')
        if strcmp(gameState.turn, 'w')
            turn = 'Blancas';
        else
            turn = 'Negras';
        end
    else
        error('El estado del juego no tiene información del turno.');
    end
end
