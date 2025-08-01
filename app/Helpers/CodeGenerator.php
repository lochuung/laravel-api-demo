<?php

namespace App\Helpers;

use Exception;
use Illuminate\Support\Facades\DB;

class CodeGenerator
{
    /**
     * Generate unique code with a given prefix
     * @throws Exception
     */
    public static function for(string $prefix): string
    {
        DB::statement("CALL generate_code(?, @code)", [$prefix]);

        $result = DB::select("SELECT @code AS generated_code");

        return $result[0]->generated_code ??
            throw new Exception("Could not generate code for prefix: $prefix");
    }

    public static function getSuggestedPrefixes(): array
    {
        $prefixes = DB::table('code_sequences')
            ->select('prefix')
            ->distinct()
            ->pluck('prefix')
            ->toArray();

        return $prefixes;
    }
}
