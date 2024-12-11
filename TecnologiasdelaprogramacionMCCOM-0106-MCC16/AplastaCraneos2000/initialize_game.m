function gameState = initialize_game()
    % Inicializa el estado del juego.
    gameState.turn = 'w'; % Blancas inician.
    gameState.board = create_initial_board(); % Llama a una función para configurar el tablero.
    gameState.history = {}; % Historial de movimientos vacío.
end

function board = create_initial_board()
    % Crea un tablero de ajedrez inicial.
    % Cada celda contiene una pieza representada como un string, o está vacía.
    board = {
        'r', 'n', 'b', 'q', 'k', 'b', 'n', 'r';
        'p', 'p', 'p', 'p', 'p', 'p', 'p', 'p';
        '',  '',  '',  '',  '',  '',  '',  '';
        '',  '',  '',  '',  '',  '',  '',  '';
        '',  '',  '',  '',  '',  '',  '',  '';
        '',  '',  '',  '',  '',  '',  '',  '';
        'P', 'P', 'P', 'P', 'P', 'P', 'P', 'P';
        'R', 'N', 'B', 'Q', 'K', 'B', 'N', 'R';
    };
end
