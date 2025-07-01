<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Rules\UniqueAllergiePerPersoon;
use App\Rules\AllergieRisicoValidatie;

class UpdatePersoonAllergieRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $persoonId = $this->route('persoon');
        $allergieId = $this->route('allergie');

        return [
            'nieuwe_allergie_id' => [
                'required',
                'integer',
                'exists:allergies,id,is_actief,1', // Alleen actieve allergieën
                'different:' . $allergieId, // Nieuwe allergie moet anders zijn dan huidige
                new UniqueAllergiePerPersoon($persoonId, $allergieId), // Custom regel voor uniekheid
                new AllergieRisicoValidatie() // Custom regel voor risico validatie
            ],
            'bevestiging' => [
                'accepted' // Must be 'yes', 'on', 1, or true (checkbox checked)
            ]
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nieuwe_allergie_id.required' => 'Selecteer een nieuwe allergie.',
            'nieuwe_allergie_id.integer' => 'Ongeldige allergie geselecteerd.',
            'nieuwe_allergie_id.exists' => 'De geselecteerde allergie bestaat niet of is niet actief.',
            'nieuwe_allergie_id.different' => 'De nieuwe allergie moet anders zijn dan de huidige allergie.',
            'nieuwe_allergie_id.unique' => 'Deze persoon heeft deze allergie al.',
            'bevestiging.accepted' => 'U moet bevestigen dat de wijziging correct is.'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'nieuwe_allergie_id' => 'nieuwe allergie',
            'bevestiging' => 'bevestiging'
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        // Log validation failures for security monitoring
        \Illuminate\Support\Facades\Log::warning('Allergie update validation failed', [
            'errors' => $validator->errors()->toArray(),
            'input' => $this->except(['_token', '_method']),
            'persoon_id' => $this->route('persoon'),
            'allergie_id' => $this->route('allergie'),
            'user_id' => auth()->id(),
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);

        parent::failedValidation($validator);
    }
}
