@php
    echo '<?php';
@endphp


namespace App\Exceptions;

use Exception;

class BusinessException extends Exception
{
    /**
     * @var string
     */
    private $userMessage;

    public function __construct(string $userMessage)
    {
        $this->userMessage = $userMessage;
        parent::__construct("Business exception");
    }

    public function getUserMessage(): string
    {
        return $this->userMessage;
    }
}