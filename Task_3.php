<?php
$arrWithCorrectHTML1 = array(
    0 => '&lta&gt',//<a>
    1 => '&ltdiv&gt',//<div>
    2 => '&ltdiv&gt',//<div>
    3 => '&lt&#47div&gt',//</div>
    4 => '&lt&#47div&gt',//</div>
    5 => '&lt&#47a&gt',//</a>
    6 => '&ltspan&gt',//<span>
    7 => '&lt&#47span&gt'//</span>
);
$arrWithCorrectHTML2 = array(
    0 => '&ltdiv&gt',//<div>
    1 => '&ltdiv&gt',//<div>
    2 => '&lt&#47div&gt',//</div>
    3 => '&lta&gt',//<a>
    4 => '&lt&#47a&gt',//</a>
    5 => '&lt&#47div&gt'//</div>
);

$arrWithIncorrectHTML1 = array(
    0 => '&lta&gt',//<a>
    1 => '&ltdiv&gt',//<div>
    2 => '&lt&#47a&gt',//</a>
);
$arrWithIncorrectHTML2 = array(
    0 => '&lta&gt',//<a>
    1 => '&ltdiv&gt',//<div>
    2 => '&lt&#47a&gt',//</a>
    3 => '&ltdiv&gt',//<div>
    4 => '&lt&#47a&gt',//</a>
    5 => '&lt&#47a&gt',//</a>
);


//Закрывающий тег не должен идти сразу после открывающего ДРУГОГО тега
function isHtmlCorrect($mas)
{
    //если первый тег закрывающий, то html - некорректен
    if (strpos($mas[0], '&#47')) {
        return false;
    }
    //если последний тег открывающий, то html - некорректен
    if (!strpos($mas[count($mas) - 1], '&#47')) {
        return false;
    }

    $lastTag = $mas[0];

    for ($i = 1; $i < count($mas); $i++) {
        $currentTag = $mas[$i];
        //Текущий тег закрывающий и прошлый тег открывающий?
        if (strpos($currentTag, "&#47") && !strpos($lastTag, "&#47")) {
            //Текущий тег не закрывает прошлый?
            if (str_replace('&#47', '', $currentTag) !== $lastTag) {
                return false;
            }
        }
        $lastTag = $currentTag;
    }
    return true;
}

print_r($arrWithCorrectHTML1);
if (isHtmlCorrect($arrWithCorrectHTML1)) {
    echo "<p>HTML is correct!</p>";
} else {
    echo "<p>HTML is incorrect!</p>";
}

print_r($arrWithCorrectHTML2);
if (isHtmlCorrect($arrWithCorrectHTML2)) {
    echo "<p>HTML is correct!</p>";
} else {
    echo "<p>HTML is incorrect!</p>";
}

print_r($arrWithIncorrectHTML1);
if (isHtmlCorrect($arrWithIncorrectHTML1)) {
    echo "<p>HTML is correct!</p>";
} else {
    echo "<p>HTML is incorrect!</p>";
}

print_r($arrWithIncorrectHTML2);
if (isHtmlCorrect($arrWithIncorrectHTML2)) {
    echo "<p>HTML is correct!</p>";
} else {
    echo "<p>HTML is incorrect!</p>";
}
