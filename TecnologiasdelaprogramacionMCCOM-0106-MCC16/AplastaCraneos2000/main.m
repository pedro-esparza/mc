% Archivo: main.m
depth = 2;
alpha = -inf;
beta = inf;
isMaximisingPlayer = true;
centralLog = {};

% Estado inicial del juego.
gameState = initialize_game();

% Ejecuta Minimax.
[bestValue, bestMove, centralLog] = minimax(depth, gameState, alpha, beta, isMaximisingPlayer, centralLog);

% Muestra la mejor jugada y la tabla de evaluación.
disp(['Mejor movimiento: ', bestMove, ', Valor: ', num2str(bestValue)]);

T = cell2table(centralLog, 'VariableNames', {'Depth', 'Value', 'Alpha', 'Beta', 'Pruned', 'Move', 'GameState'});
disp(T);
