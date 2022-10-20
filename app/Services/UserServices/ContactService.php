<?php

namespace App\Services\UserServices;

use App\Repositories\UserRepositories\ContactRepository;
use Illuminate\Http\Request;

class ContactService
{
    protected $contactRepository;

    public function __construct(ContactRepository $contactRepository)
    {
        $this->contactRepository = $contactRepository;
    }

    public function contacts()
    {
        return $this->contactRepository->contacts();
    }

    public function store($request)
    {
        return $this->contactRepository->store($request);
    }

    //PRIVACY SET TO ALL CONTACTS
    public function storeContactPrivacy($request)
    {
        return $this->contactRepository->storeContactPrivacy($request);
    }

    public function storeMultipleContacts($request)
    {
        // $createdContacts = collect();
        // foreach ($request->contacts as $contactRequest) {
        //     $contactRequest = new Request($contactRequest);
        //     $contacts = $this->contactRepository->store($contactRequest, );
        //     $createdContacts->push($contacts);
        // }
        // return $createdContacts;
        return $this->contactRepository->store($request);
    }

    public function edit($contactId)
    {
        return $this->contactRepository->show($contactId);
    }

    public function update($request, $id)
    {
        $this->contactRepository->update($request, $id);
    }

}
