<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAnnonceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()?->estVendeur() === true;
    }

    public function rules(): array
    {
        return [
            'titre' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'prix' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'devise' => ['required', Rule::in(['USD', 'CDF'])],
            'categorie_id' => ['required', 'exists:categories,id'],
            'etat' => ['required', Rule::in(['neuf', 'tres_bon_etat', 'bon_etat', 'usage', 'endommage'])],
            'photos' => ['nullable', 'array', 'max:5'],
            'photos.*' => ['image', 'mimes:jpeg,png,webp', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        return [
            'titre.required' => 'Le titre est obligatoire.',
            'prix.required' => 'Le prix est obligatoire.',
            'categorie_id.required' => 'La catégorie est obligatoire.',
            'etat.required' => "L'état est obligatoire.",
            'photos.max' => 'Maximum 5 photos.',
            'photos.*.max' => 'Chaque photo ne doit pas dépasser 5 Mo.',
        ];
    }
}
