<?php

$categories = array(
    array(
        "id" => 1,
        "title" => "Обувь",
        'children' => array(
            array(
                'id' => 2,
                'title' => 'Ботинки',
                'children' => array(
                    array('id' => 3, 'title' => 'Кожа'),
                    array('id' => 4, 'title' => 'Текстиль'),
                    array('id' => 10, 'title' => 'Железо'),
                ),
            ),
            array('id' => 5, 'title' => 'Кроссовки',),
        )
    ),
    array(
        "id" => 6,
        "title" => "Спорт",
        'children' => array(
            array(
                'id' => 7,
                'title' => 'Мячи'
            )
        )
    ),
);

function searchCategory($categories, $id)
{
    foreach ($categories as $key => $value) {
        if($categories[$key] === $id) {
            print_r($categories['title']);
        }
        else if(is_array($value)){
            searchCategory($value,$id);
        }
    }
}
searchCategory($categories, 10);












