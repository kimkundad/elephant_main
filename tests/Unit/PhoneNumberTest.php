<?php

namespace Tests\Unit;

use App\Support\PhoneNumber;
use Tests\TestCase;

class PhoneNumberTest extends TestCase
{
    public function test_normalize_keeps_only_digits_and_a_leading_plus(): void
    {
        $this->assertSame('+66958467417', PhoneNumber::normalize(' +66 95-846 7417 '));
        $this->assertSame('0958467417', PhoneNumber::normalize('095-846-7417'));
        $this->assertNull(PhoneNumber::normalize('   '));
        $this->assertNull(PhoneNumber::normalize('abc'));
    }

    public function test_country_accepts_two_letters_only(): void
    {
        $this->assertSame('TH', PhoneNumber::country('th'));
        $this->assertNull(PhoneNumber::country('THA'));
        $this->assertNull(PhoneNumber::country(null));
    }

    public function test_variants_match_the_local_form_of_a_thai_number(): void
    {
        $this->assertSame(
            ['+66958467417', '0958467417', '66958467417'],
            PhoneNumber::variants('+66958467417', 'TH')
        );

        $this->assertSame(
            ['0958467417', '+0958467417', '+66958467417'],
            PhoneNumber::variants('0958467417')
        );
    }
}
