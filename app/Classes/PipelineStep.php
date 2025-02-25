<?php


namespace App\Classes;


interface PipelineStep
{
    public function execute(array $articles);
}
