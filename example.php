<?php

require_once 'db.php';

$db = new Database();

//get item by id example (column name needs to be id)
$select = $db->getByID('*','posts',2);
echo $select->img;

//get first value from select query only
$first = $db->first('*','posts',
    [
        'where'=> [
        'column' => 'category',
        'operator' => '=',
        'value' => 'Politics'
    ],
]);
echo  $first->title;

//Create function example
$create = $db->Create(
    'posts',
    [
        'category' => 'Technology',
        'title' => 'DB-Test',
        'body' => 'DB-Test',
        'author' => 'Mostafa',
        'keywords' => 'kjkszpj',
        'img' => 'http://img.img/123.jpg',
    ]
);
//getAll function example + looping through the result
$posts = $db->getAll('*','posts',[
    'order' =>
        [
            'key' => 'id',
            'type' => 'DESC'
        ],
    'limit' => 15
]);
foreach ($posts as $post)
{
    echo $post->id;
    echo '<br>';
}

//Delete Function Usage example
$post_delete = $db->Delete('posts',
    [
        'column' => 'id',
        'operator' => '=',
        'value' => '3'
    ]);

//Update Function usage example
$update = $db->Update(
    'posts',
    [
        'category' => 'Sports'
    ],
    [
        'key' => 'id',
        'operator' => '=',
        'value' => '6'
    ]);
echo $update;
