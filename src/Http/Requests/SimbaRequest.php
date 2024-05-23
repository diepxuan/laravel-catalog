<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2024-05-22 18:12:07
 */

namespace Diepxuan\Catalog\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class SimbaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<mixed>|\Illuminate\Contracts\Validation\ValidationRule|string>
     */
    public function rules(): array
    {
        return [
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'from' => Carbon::parse($this->get('from', Carbon::now()->firstOfMonth())),
            'to'   => Carbon::parse($this->get('to', Carbon::now()->lastOfMonth())),
        ]);
    }
}
