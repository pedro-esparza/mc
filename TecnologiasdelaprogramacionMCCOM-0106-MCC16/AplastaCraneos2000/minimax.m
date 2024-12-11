function [bestValue, bestMove, centralLog] = minimax(depth, gameState, alpha, beta, isMaximisingPlayer, centralLog)
    if depth == 0 || is_terminal(gameState)
        bestValue = evaluateBoard(gameState); % Evalúa la posición actual.
        bestMove = NaN; % No hay movimiento en un nodo hoja.
        centralLog = [centralLog; {depth, bestValue, alpha, beta, false, 'N/A', gameState}];
        return;
    end

    possibleMoves = get_moves(gameState); % Obtiene los movimientos posibles.
    bestMove = '';
    pruned = false;

    if isMaximisingPlayer
        bestValue = -inf;
        for i = 1:length(possibleMoves)
            nextGameState = apply_move(gameState, possibleMoves{i});
            [value, ~, centralLog] = minimax(depth - 1, nextGameState, alpha, beta, false, centralLog);
            if value > bestValue
                bestValue = value;
                bestMove = possibleMoves{i};
            end
            alpha = max(alpha, bestValue);
            if beta <= alpha
                pruned = true;
                break;
            end
        end
    else
        bestValue = inf;
        for i = 1:length(possibleMoves)
            nextGameState = apply_move(gameState, possibleMoves{i});
            [value, ~, centralLog] = minimax(depth - 1, nextGameState, alpha, beta, true, centralLog);
            if value < bestValue
                bestValue = value;
                bestMove = possibleMoves{i};
            end
            beta = min(beta, bestValue);
            if beta <= alpha
                pruned = true;
                break;
            end
        end
    end

    gameStateSummary = sprintf('Turno: %s, Piezas: %d', get_turn(gameState), count_pieces(gameState));

    centralLog = [centralLog; {depth, bestValue, alpha, beta, pruned, bestMove, gameStateSummary}];

end