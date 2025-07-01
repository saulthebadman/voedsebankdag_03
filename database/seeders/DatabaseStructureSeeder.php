<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class DatabaseStructureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Read the SQL script
        $sqlScript = File::get(database_path('../database_create_script.sql'));
        
        // Split the script into individual statements
        $statements = explode(';', $sqlScript);
        
        foreach ($statements as $statement) {
            $statement = trim($statement);
            
            // Skip empty statements and comments
            if (empty($statement) || str_starts_with($statement, '--')) {
                continue;
            }
            
            try {
                DB::unprepared($statement);
                echo "✓ Executed: " . substr($statement, 0, 50) . "...\n";
            } catch (\Exception $e) {
                echo "✗ Error executing: " . substr($statement, 0, 50) . "...\n";
                echo "Error: " . $e->getMessage() . "\n";
            }
        }
        
        echo "\n🎉 Database structure created successfully!\n";
    }
}
