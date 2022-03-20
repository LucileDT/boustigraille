<?php

namespace App\Migrations\Interfaces;

use App\Service\Migrations\ContentAuthorService;

interface ContentAuthorable
{
    public function setAuthorService(ContentAuthorService $authorService);
}
