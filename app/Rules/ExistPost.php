<?php

namespace App\Rules;

use App\Models\Post;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ExistPost implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!Post::where('id', (int)$value)->exists()) {
            $fail('post does not exist');
        }
    }
}
