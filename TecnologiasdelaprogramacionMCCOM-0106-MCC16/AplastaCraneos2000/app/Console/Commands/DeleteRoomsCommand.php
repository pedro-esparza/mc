<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Room;
use Illuminate\Console\Command;

class DeleteRoomsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:room';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete old rooms';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $days = 9; // Number of days after which rooms should be deleted
        $date = Carbon::now()->subDays($days);

        Room::where('modified_at', '<', $date)->where('fen', '=', env('INITIAL_FEN'))->orWhere('fen', '=', 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1 resign')->delete(); // Rooms not started yet
        Room::where('modified_at', '<', $date)->where('host_id', '=', null)->where('result', '=', null)->delete(); // Rooms not in competition and not finish
        Room::where('modified_at', '<', $date)->where('host_id', '!=', null)->where('result', '!=', null)->where('fen', 'LIKE', '% - - 0 1%')->delete(); // Rooms have wrong FEN code

        $this->info('Rooms older than ' . $days . ' days have been deleted.');
    }
}
