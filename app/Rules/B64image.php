<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class B64image implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        try {
            $binary = base64_decode(explode(',', $value)[1]);
            $data = getimagesizefromstring($binary);
        } catch (\Exception $e) {
          return false;
        }

        $allowed = ['image/jpeg', 'image/png', 'image/gif'];

        if (!$data) {
            return false;
        }

        if (!empty($data[0]) && !empty($data[0]) && !empty($data['mime'])) {
            if (in_array($data['mime'], $allowed)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must be a valid base64 encoded image';
    }
}
