<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS generate_code');
        $collation = config('database.collation', 'utf8mb4_unicode_ci');
        DB::unprepared("
    CREATE PROCEDURE generate_code(
        IN prefix_input VARCHAR(50),
        OUT result_code VARCHAR(100)
    )
    BEGIN
        DECLARE last_num INT DEFAULT 0;

        START TRANSACTION;

        -- Nếu chưa có prefix, thêm mới
        INSERT INTO code_sequences (prefix, last_number, created_at, updated_at)
        SELECT prefix_input, 0, NOW(), NOW()
        FROM DUAL
        WHERE NOT EXISTS (
            SELECT 1 FROM code_sequences WHERE prefix COLLATE $collation = prefix_input
        );

        -- Lấy số cuối cùng
        SELECT last_number INTO last_num
        FROM code_sequences
        WHERE prefix COLLATE $collation = prefix_input
        FOR UPDATE;

        -- Tăng số
        SET last_num = last_num + 1;

        UPDATE code_sequences
        SET last_number = last_num,
            updated_at = NOW()
        WHERE prefix COLLATE $collation = prefix_input;

        COMMIT;

        -- Ghép mã kết quả: PREFIX + 000X
        SET result_code = CONCAT(prefix_input, LPAD(last_num, 4, '0'));
    END
");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('
        DROP PROCEDURE IF EXISTS generate_code;');
    }
};
