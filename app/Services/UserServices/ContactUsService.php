<?php

namespace App\Services\UserServices;

use App\Repositories\UserRepositories\ContactUsRepository;
use Illuminate\Http\Request;

class ContactUsService
{
    protected $contactUsRepository;

    public function __construct(ContactUsRepository $contactUsRepository)
    {
        $this->contactUsRepository = $contactUsRepository;
    }

    public function store($request)
    {
        return $this->contactUsRepository->contactsUs($request);
    }

}
