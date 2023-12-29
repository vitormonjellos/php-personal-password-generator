<?php

function print_array($arrayString) {
    foreach($arrayString as $a) {
        echo "$a";
    }
}

function command_line_arguments($args) {
    $lenght = null;
    foreach ($args as $arg) {
        if (preg_match('/^--lenght=(\d+)$/', $arg, $matches)) {
            $lenght = (int)$matches[1];
            break;
        }
    }
    return $lenght;
}

function weak_password_reset($passwordLen) {

    echo "Your password has ", $passwordLen," or less characters. This means that it is probably weak.\n";
    $answer = readline('Do you want to keep it? [Y/N] ');
    if (strtoupper($answer) == 'N') {
        echo "We will generate a new password. We advise a password with more than 12 characters!\n";
        $new_lenght = (int)readline("How many characters would you like? ");
        if ($new_lenght > $passwordLen) {
            $chars = characters_generator();
            $finalPassword = password_generator($chars, $new_lenght) . "\n";
            echo "Your new password is: $finalPassword";
            exit;
        } else {
            echo "Invalid lenght specified. It must have more then $passwordLen characters\n";
            exit;
        }
    } elseif ($answer != 'Y') {
        echo "Invalid input. Please respond with 'Y' for Yes or 'N' for No.\n";
        exit;
    }

}

function check_password_lenght($password) {
    $passwordLenght = strlen($password) - 1;
    $complexityMessage = check_password_complexity($password);

    switch (true) {
        case ($passwordLenght > 16):
            return 'Your password is really strong.';

        case ($passwordLenght > 12):
            return "Your password is strong";

        case ($passwordLenght > 8):
            return "Your password is acceptable";

        default:
            weak_password_reset($passwordLenght);
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

function password_generator($chars, $passwordLenght) {
    
    $mainPassword = '';
    $passwordSize = strlen(implode($chars));
    for($i = 0; $i < $passwordLenght; ++$i) {
        $randCharIndex = random_int(0, $passwordSize - 1);
        $mainPassword .= $chars[$randCharIndex];
    }
    return $mainPassword;
}


function characters_generator() {
    $upperCase = range('A', 'Z');
    $lowerCase = range('a', 'z');
    $numbers = range(0, 9);
    $specialChars = str_split('!#$%&()*+,-./:;<=>?@[]^_{|}');
    $chars =  array_merge($upperCase,$lowerCase,$numbers,$specialChars);

    return $chars;
}

$passLenght = command_line_arguments($argv);

if ($passLenght === null) {
    echo "You must specify a password lenght with the --lenght option.\n";
    exit;
}

$chars = characters_generator();
$finalPassword = password_generator($chars, $passLenght) . "\n";

$lenghtCheck = check_password_lenght($finalPassword);
echo "$lenghtCheck" . "\n";
echo "$finalPassword";

?>