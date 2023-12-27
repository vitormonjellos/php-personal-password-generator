<?php

function print_array($array_string) {
    foreach($array_string as $a) {
        echo "$a";
    }
}

function command_line_arguments($args) {
    $length = null;
    foreach ($args as $arg) {
        if (preg_match('/^--length=(\d+)$/', $arg, $matches)) {
            $length = (int)$matches[1];
            break;
        }
    }
    return $length;
}

function check_password_lenght($password) {
    $passwordLength = strlen($password) - 1;
    $complexityMessage = check_password_complexity($password);

    switch (true) {
        case ($passwordLength <= 6):
            echo "Your password has 6 or less characters. This means that it is really weak.\n";
            $answer = readline('Do you want to keep it? [Y/N] ');
            if (strtoupper($answer) == 'N') {
                echo "We will generate a new password. We advise a password with more than 12 characters!\n";
                $new_length = (int)readline("How many characters would you like? ");
                if ($new_length > 0) {
                    $chars = characters_generator();
                    $final_password = password_generator($chars, $new_length) . "\n";
                    echo "Your new password is: $final_password";
                    exit;
                } else {
                    echo "Invalid length specified.\n";
                    exit;
                }
            } elseif ($answer != 'Y') {
                echo "Invalid input. Please respond with 'Y' for Yes or 'N' for No.\n";
                exit;
            }
            break;
            break;
    
        case ($passwordLength <= 8):
            echo "Your password has 8 or less characters. This means that it is probably weak.\n";
            $answer = readline('Do you want to keep it? [Y/N] ');
            if (strtoupper($answer) == 'N') {
                echo "We will generate a new password. We advise a password with more than 12 characters!\n";
                $new_length = (int)readline("How many characters would you like? ");
                if ($new_length > 0) {
                    $chars = characters_generator();
                    $final_password = password_generator($chars, $new_length) . "\n";
                    echo "Your new password is: $final_password";
                    exit;
                } else {
                    echo "Invalid length specified.\n";
                    exit;
                }
            } elseif ($answer != 'Y') {
                echo "Invalid input. Please respond with 'Y' for Yes or 'N' for No.\n";
                exit;
            }
            break;

        case ($passwordLength <= 12):
            return "Your password is good";

        case ($passwordLength <= 16):
            return "Your password is strong";

        default:
            return 'Your password is really strong.';
    }
}

function check_password_complexity($password) {
    $patterns = [
        '/^[0-9]+$/' => "numbers only",
        '/^[a-z]+$/' => "lowercase letters only",
        '/^[A-Z]+$/' => "uppercase letters only",
        '/^[a-zA-Z]+$/' => "upper and lowercase letters",
        '/^[0-9a-zA-Z]+$/' => "numbers and letters",
        '/^[0-9a-zA-Z!#$%&()*+,\-.\/:;<=>?@\[\]^_{|}]+$/' => "alphanumeric and special characters",
    ];
    
    foreach ($patterns as $pattern => $complexity) {
        if (preg_match($pattern, $password)) {
            return $complexity;
        }
    }
    
    return "includes invalid characters";
} 

function password_generator($chars, $passwordlength) {
    
    $main_password = '';
    $passwordsize = strlen(implode($chars));
    for($i = 0; $i < $passwordlength; ++$i) {
        $randCharIndex = random_int(0, $passwordsize - 1);
        $main_password .= $chars[$randCharIndex];
    }
    return $main_password;
}


function characters_generator() {
    $upperCase = range('A', 'Z');
    $lowerCase = range('a', 'z');
    $numbers = range(0, 9);
    $specialChars = str_split('!#$%&()*+,-./:;<=>?@[]^_{|}');
    $chars =  array_merge($upperCase,$lowerCase,$numbers,$specialChars);

    return $chars;
}

$pass_lenght = command_line_arguments($argv);

if ($pass_lenght === null) {
    echo "You must specify a password length with the --length option.\n";
    exit;
}

$chars = characters_generator();
$final_password = password_generator($chars, $pass_lenght) . "\n";

$lenght_check = check_password_lenght($final_password);
echo "$lenght_check" . "\n";
echo "$final_password";

?>