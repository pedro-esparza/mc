<?php

namespace App\Console\Commands;

use App\Models\Room;
use Illuminate\Console\Command;

class DeleteBadWordsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:badWords';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete bad words';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $badWords = ['Cặc', 'Lồn', ' lồn ', 'con cac', 'con cặc', 'con cu', 'cu', 'cặc', 'cac', 'ccc', 'cc', 'vcl', 'vú', 'địt', 'dit', 'đụ', 'ngu', 'stupid', 'shit', 'piss', 'fuck', 'cunt', 'cocksucker', 'motherfucker', 'tits', 'sex', 'sexy', 'nude', 'naked', 'porn'];

        Room::whereIn('name', $badWords)->delete();

        foreach ($badWords as $word) {
            Room::where('name', 'LIKE', "{$word}%")->delete();
            Room::where('name', 'LIKE', "%{$word}")->delete();
            Room::where('name', 'LIKE', "%{$word}%")->delete();
        }

        $this->info('Rooms and Puzzles with bad words in name have been deleted.');
    }
}
