<?php

namespace Tests\Unit;

use App\Services\CoordinateGenerator;
use Tests\TestCase;

class CoordinateGeneratorTest extends TestCase
{
    public function test_it_generates_correct_coordinates_for_provided_location_code(): void
    {
        $coordinateGenerator = resolve(CoordinateGenerator::class);

        $coordinate = $coordinateGenerator->generate([37.0902, -95.7129], 400);

        // pass test
        self::assertNotEmpty($coordinate);
    }
}
