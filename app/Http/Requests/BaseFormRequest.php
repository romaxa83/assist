<?php

namespace App\Http\Requests;

use App\Core\Contracts\Model\Sortable;
use App\Enums\DateFormat;
use App\Enums\OrderType;
use App\Models\Users\User;
use ArondeParon\RequestSanitizer\Traits\SanitizesInputs;
use Illuminate\Foundation\Http\FormRequest;

 class BaseFormRequest extends FormRequest
{
     use SanitizesInputs;

     public function authorize(): bool
     {
         return true;
     }

    public function paginationRule(): array
    {
        return [
            'page' => ['nullable', 'int'],
            'per_page' => ['nullable', 'int'],
        ];
    }

     public function searchRule(): array
     {
         return [
             'search' => ['nullable', 'string', 'min:2'],
         ];
     }

     public function idRule(): array
     {
         return [
             'id' => ['nullable', 'numeric'],
         ];
     }

     public function orderRule(array $allowed): array
     {
         return [
             'order_by' => ['nullable', 'string', $this->orderByIn($allowed)],
             'order_type' => ['nullable', 'string', $this->orderTypeIn()],
         ];
     }

     public function sortRule(string $modelClass): array
     {
         /** @var $model Sortable */
         $model = resolve($modelClass);

         return [
             'sort' => ['nullable', 'array'],
             'sort.*' => ['required', 'string', 'in:' . $model::getSortEnumsAsString(',')],
         ];
     }

     public function dateRangeRule(): array
     {
         return [
             'start_date' => ['nullable', 'string', 'date_format:'.DateFormat::FRONT_FILTER()],
             'end_date' => ['nullable', 'string', 'date_format:'.DateFormat::FRONT_FILTER()],
         ];
     }

     public function passwordRule(): array
     {
         return [
             'password' => ['required', 'min:'.User::MIN_LENGTH_PASSWORD, 'max:32'],
             'password_confirmation' => ['required','same:password','min:'.User::MIN_LENGTH_PASSWORD, 'max:32']
         ];
     }

     public function fileRule(): array
     {
         return [
             'attachment' => ['required', 'file', 'mimes:pdf,png,jpg,jpeg,jpe,doc,docx,txt,xls,xlsx'],
         ];
     }

     public function attachmentMimes(): string
     {
         return 'mimes:pdf,png,jpg,jpeg,jpe,doc,docx,txt,xls,xlsx';
     }

     public function imageRule(): array
     {
         return ['required', 'image', "max:" . byte_to_kb(config('media-library.max_file_size'))];
     }

     protected function orderByIn(array $allowed): string
     {
         return 'in:' . implode(',', $allowed);
     }

     protected function orderTypeIn(): string
     {
         return 'in:' . implode(',', [OrderType::ASC(), OrderType::DESC()]);
     }
}
