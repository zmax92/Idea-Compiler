<?php 

set_error_handler(
    function ($severity, $message, $file, $line) {
        throw new ErrorException($message, $severity, $severity, $file, $line);
    }
);

function inital () {
    $files = [
        'comma' => ', ',
        'pipe' => ' | ',
        'space' => ' '
    ];

    try {
        file_put_contents('test', '');
        unlink('test');
    }
    catch (\Throwable $th) {
        print $th->getMessage()."\n";
        die('Exiting script...'."\n");
    }
    
    $overall_info = [];
    foreach ($files as $name => $seperator) {
        try {
            $file = fopen($name.'.txt', 'r');
        }
        catch (\Throwable $th) {
            print $th->getMessage()."\n";
            continue;
        }

        if(!empty($file)) {
            print 'Reading file: '.$name.'.txt ...'."\n";
            while ($line = fgets($file)) {
                $line = explode($seperator, $line);
                
                $item = [
                    'last' => $line[0],
                    'first' => $line[1],
                ];
    
                switch ($name) {
                    case 'pipe':
                        $item['gender'] = ($line[3][0] == 'F' ? 'Female' : 'Male');
                        $item['birth'] = strtotime($line[5]);
                        $item['color'] = trim($line[4]);
                        break;
                    case 'space':
                        $item['gender'] = ($line[3][0] == 'F' ? 'Female' : 'Male');
                        $item['birth'] = strtotime($line[4]);
                        $item['color'] = trim($line[5]);
                        break;
                    case 'comma':
                        $item['gender'] = ($line[2][0] == 'F' ? 'Female' : 'Male');
                        $item['birth'] = strtotime($line[4]);
                        $item['color'] = trim($line[3]);
                        break;
                    }
                $overall_info[] = $item;
            }
        } 
    }
    
    if (!empty($overall_info)) {
        $file_text = '';
        print 'Sorting by gender then last name ...'."\n";
        $female = $male = [];
        foreach ($overall_info as $value) {
            if($value['gender'] == 'Female') {
                $female[] = $value;
            }
            else {
                $male[] = $value;
            }
        }
        $female = sorting_algorithm($female, 'last');
        $male = sorting_algorithm($male, 'last');
        $output = array_merge($female, $male); 
        $file_text .= format_output_text($output, 'gender');

        $file_text .= "\n";
        print 'Sorting by date of birth ...'."\n";
        $output = sorting_algorithm($overall_info, 'birth');
        $file_text .= format_output_text($output, 'birth');
 
        $file_text .= "\n";
        print 'Sorting by last name ...'."\n";
        $output = sorting_algorithm($overall_info, 'last', 'DESC');
        $file_text .= format_output_text($output, 'last');

        print 'Writing file: output.txt ...'."\n";
        file_put_contents('output.txt', $file_text);
        die('Done, exiting ...'."\n");
    }
}
inital();

/**
 * Function takes array, sorts it by array item in specified direction, returns new sorted array
 *
 * @param array $init_array
 *   Input array
 *
 * @param string $sort_item
 *  Item in array that is used for sorting (last, birth)
 * 
 * @param string $direction
 *  Sorting direction (ASC, DESC)
 * 
 * @return array
 *  New sorted array
 *   
 */
function sorting_algorithm(array $init_array, string $sort_item = 'last', string $direction = 'ASC') {
    $output_array = $init_array;

    if($sort_item == 'last') {
        $letters = range('A', 'Z');
    }

    $size = sizeof($output_array);
    for ($outer = 1; $outer < $size; $outer++) {
        for ($inner = $size - 1; $inner >= $outer; $inner--) {
            switch ($sort_item) {
                case 'birth':
                    $previous = $output_array[$inner-1][$sort_item];
                    $current = $output_array[$inner][$sort_item];
                    break;
                case 'last':
                    $index = 0;
                    $previous = array_keys($letters, $output_array[$inner-1][$sort_item][$index] )[0];
                    $current = array_keys($letters, $output_array[$inner][$sort_item][$index] )[0];

                    if($previous == $current) {
                        while ($previous == $current) {
                            $index++;

                            $previous = array_keys($letters, strtoupper($output_array[$inner-1][$sort_item][$index]) )[0];
                            $current = array_keys($letters, strtoupper($output_array[$inner][$sort_item][$index]) )[0];
                        }
                    }
                    break;
            }

            switch ($direction) {
                case 'ASC':
                    if($previous > $current) {
                        $tmp = $output_array[$inner - 1];
                        $output_array[$inner - 1] = $output_array[$inner];
                        $output_array[$inner] = $tmp;
                    }
                    break;
                case 'DESC':
                    if($previous < $current) {
                        $tmp = $output_array[$inner - 1];
                        $output_array[$inner - 1] = $output_array[$inner];
                        $output_array[$inner] = $tmp;
                    }
                    break;
            }
                
        }
    }
     
    return $output_array;
}

/**
 * Function creates output string from structured array
 * 
 * @param array $input_array
 *   Structured input array
 *
 * @param string $header
 *  Type of header (last, birth, gender)
 * 
 * @return string
 *  String with header and main text
 */
function format_output_text(array $input_array, string $header) {
    $text = '';

    if(!empty($input_array)) {
        foreach ($input_array as &$value) {
            $value['last'] = str_pad($value['last'], 15);
            $value['first'] = str_pad($value['first'], 15);
            $value['gender'] = str_pad($value['gender'], 15);
            $value['birth'] = str_pad(date('d/m/Y', $value['birth']), 15);
            $value['color'] = str_pad($value['color'], 16);
    
            $value = implode(' ', $value);
        }
    
        $text = implode("\n", $input_array);
        $text .= "\n";
    }

    switch ($header) {
        case 'gender':
            $text = 'gender then lastname ascending'."\n".
            '--------------------------'."\n".
            $text.
            '--------------------------'."\n";
            break;
        case 'birth':
            $text = 'dateofbirth ascending'."\n".
            '--------------------------'."\n".
            $text.
            '--------------------------'."\n";
            break;
        case 'last':
            $text = 'lastname descending'."\n".
            '--------------------------'."\n".
            $text.
            '--------------------------'."\n";
            break;
    }

    return $text;
}

?>