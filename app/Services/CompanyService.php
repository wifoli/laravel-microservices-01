<?php

namespace App\Services;

use App\Models\Company;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class CompanyService
{
    public function __construct(
        protected Company $repository,
    ) {
    }

    public function getCompanies(string $filter = '')
    {
        return $this->repository->getCompanies($filter);
    }

    public function createNewCompany(array $data, UploadedFile $image)
    {
        $path = $this->uploadImage($image);
        $data['image'] = $path;

        return $this->repository->create($data);
    }

    public function getCompanyByUUID(string $uuid = null)
    {
        return $this->repository->whereUuid($uuid)->firstOrFail();
    }

    public function updateCompanyByUUID(string $uuid = null, array $data, UploadedFile $image = null)
    {
        $company = $this->getCompanyByUUID($uuid);

        if ($image) {
            if (Storage::exists($company->image)) {
                Storage::delete($company->image);
            }

            $path = $this->uploadImage($image);
            $data['image'] = $path;
        }

        return $company->update($data);
    }

    public function deleteCompanyByUUID(string $uuid = null)
    {
        $company = $this->getCompanyByUUID($uuid);

        if (Storage::exists($company->image)) {
            Storage::delete($company->image);
        }

        return $company->delete();
    }

    private function uploadImage(UploadedFile $image)
    {
        return $image->store('companies');
    }
}
