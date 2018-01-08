<?php

namespace Tests;

trait SpecMatchers
{
    public function getMatchers(): array
    {
        return [
            //custom dd() --> shouldHaveDd('bla')
            'haveDd' => function ($subject, $value) {
                dd($value, $subject);
            },
        ];
    }
}
