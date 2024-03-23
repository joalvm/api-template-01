<?php

namespace App\Exceptions;

class CannotDeleteDocumentTypeWithPersonsException extends CannotDeleteResourceWithChildrenException
{
    public function __construct(int $count)
    {
        parent::__construct(
            singular_name('document_type', true),
            resource_name('person', $count),
            $count
        );
    }
}
