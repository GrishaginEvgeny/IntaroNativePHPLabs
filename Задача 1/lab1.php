<?php

function getCalculatedBet($input_file_path) {
    $balance = 0; 
    $games = [];
    $bets = [];
    $input_data_stream = fopen($input_file_path, 'r');
    $count_of_bets = fgets($input_data_stream);
    # 0 - ID игры
    # 1 - Ставка
    # 2 - Победитель по ставке (L, R, D)
    for ($i = 0; $i < $count_of_bets; $i++) { 
        $bets_info_as_string = fgets($input_data_stream);
        $bets_info_as_array = explode(" ", $bets_info_as_string);
        array_push($bets, $bets_info_as_array);
    }
    $count_of_games = fgets($input_data_stream);
    # 0 - ID игры
    # 1 - Вещественное число, коэффициент на победу левой команды
    # 2 - Вещественное число, коэффициент на победу правой команды
    # 3 - Вещественное число, коэффициент на ничью
    # 4 - Победитель (L, R, D)

    for ($i = 0; $i < $count_of_games; $i++) { 
        $games_info = fgets($input_data_stream);
        $tmp_games_list = explode(" ", $games_info);
        array_push($games, $tmp_games_list);
    }

    for ($i = 0; $i < $count_of_bets; $i++) { 
        for ($j = 0; $j < $count_of_games; $j++) {
            if($games[$j][0] === $bets[$i][0]) {
                if($games[$j][4] === $bets[$i][2]){
                    switch($bets[$i][2]) {
                        case "L\n" : 
                            $balance += $bets[$i][1] * $games[$j][1] - $bets[$i][1];
                            break;
                        case "R\n":
                            $balance += $bets[$i][1] * $games[$j][2] - $bets[$i][1];
                            break;
                        case "D\n":
                            $balance += $bets[$i][1] * $games[$j][3] - $bets[$i][1];
                            break;    
                    }
                }
                else {
                    $balance -= $bets[$i][1];
                }
                break;
            }
        }
    }

    return $balance;
}

function getAnswer($output_file_path) {
    $output_data_stream = fopen($output_file_path, 'r');
    return trim(fgets($output_data_stream));
}


$answer_data_files = glob('A/*.ans');
$input_data_files = glob('A/*.dat');
$count_of_tests = 8;

for ($i = 0; $i < $count_of_tests; $i++) {
    $number_of_test = $i + 1;
    print_r("Тест {$number_of_test} -- ");
    if(getCalculatedBet($input_data_files[$i]) == getAnswer($answer_data_files[$i])) 
        print_r("OK.\n");
    else 
        print_r("Wrong answer.");
}
