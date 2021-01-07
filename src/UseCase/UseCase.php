<?php


namespace Blog\UseCase;


interface UseCase
{
    public function __invoke(object $command);
}