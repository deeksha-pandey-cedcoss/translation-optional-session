<?php
echo "<h1>Hello User Welcome</h1>";
echo PHP_EOL;
echo $this->tag->linkTo(
    [
        'signup',
        'Signup Here!', 'class' => 'm-3 btn btn-primary'
    ]
);

echo $this->tag->linkTo(
    [
        'product',
        'Add products Here!', 'class' => 'm-3 btn btn-primary'
    ]
);

echo $this->tag->linkTo(
    [
        'order',
        'Place Order Here!', 'class' => 'm-3 btn btn-primary'
    ]
);

echo $this->tag->linkTo(
    [
        'setting',
        'Apply Settings Here!', 'class' => 'm-3 btn btn-primary'
    ]
);

echo $this->tag->linkTo(
    [
        'role',
        'Add role Here!', 'class' => 'm-3 btn btn-primary'
    ]
);

echo $this->tag->linkTo(
    [
        'addaction',
        'Add action Here!', 'class' => 'm-3 btn btn-primary'
    ]
);
