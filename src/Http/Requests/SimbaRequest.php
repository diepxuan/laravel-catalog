<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2024-05-24 14:39:10
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
        $from = Carbon::parse($this->get('from', Carbon::now()->firstOfMonth()));
        $to   = Carbon::parse($this->get('to', Carbon::now()->lastOfMonth()));
        if ($from->gt($to)) {
            [$from, $to] = [$to, $from];
        }
        if ($from->eq($to)) {
            $to = $to->addDay();
        }
        $this->merge([
            'from' => $from,
            'to'   => $to,
        ]);
    }
}
