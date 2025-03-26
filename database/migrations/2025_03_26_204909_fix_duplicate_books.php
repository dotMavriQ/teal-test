<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Book;

return new class extends Migration
{
    /**
     * Run the migrations to fix duplicate books.
     * We'll identify duplicates based on exact title and author matches,
     * and keep only the record with the lowest ID (earliest created).
     */
    public function up(): void
    {
        // Get list of duplicate books (same title and author)
        $duplicates = DB::select("
            SELECT title, author, STRING_AGG(id::text, ',') as ids
            FROM books
            GROUP BY title, author
            HAVING COUNT(*) > 1
        ");

        // Process each set of duplicates
        foreach ($duplicates as $duplicate) {
            // Get IDs in an array
            $ids = explode(',', $duplicate->ids);
            sort($ids, SORT_NUMERIC); // Sort IDs numerically
            
            // Keep the lowest ID (earliest record), remove others
            $keepId = array_shift($ids);
            $deleteIds = $ids; // Remaining IDs to delete
            
            // Output the action for logging
            echo "Keeping book ID: $keepId, deleting duplicate IDs: " . implode(',', $deleteIds) . PHP_EOL;
            
            // Delete the duplicates
            DB::table('books')->whereIn('id', $deleteIds)->delete();
        }
        
        // Ensure no duplicates with same slug
        $duplicateSlugs = DB::select("
            SELECT slug, STRING_AGG(id::text, ',') as ids
            FROM books
            GROUP BY slug
            HAVING COUNT(*) > 1
        ");
        
        foreach ($duplicateSlugs as $duplicate) {
            $ids = explode(',', $duplicate->ids);
            sort($ids, SORT_NUMERIC);
            
            $keepId = array_shift($ids);
            $deleteIds = $ids;
            
            echo "Fixing slug duplicate: keeping book ID: $keepId, deleting IDs: " . implode(',', $deleteIds) . PHP_EOL;
            
            DB::table('books')->whereIn('id', $deleteIds)->delete();
        }
    }

    /**
     * Reverse the migrations.
     * Cannot undo the deletion of duplicates
     */
    public function down(): void
    {
        echo "Warning: This migration cannot be reversed as it deletes duplicate records." . PHP_EOL;
    }
};
