<?php
namespace SomeVendor\SomePkg;

use PDO;

class QueryBuilder
{
    public function build(): string
    {
        return 'SELECT * FROM test';
    }
}
