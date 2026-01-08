<?php
include_once("ppa/database_class.php");

$db = new Database();
$db->connect();

$tables = [
    'clients' => ['name', 'contact', 'address_line_1', 'address_line_2', 'vat_number', 'email', 'phone_number', 'mobile_number'],
    'company' => ['name', 'address_line_1', 'address_line_2', 'vat_number', 'email', 'phone_number', 'mobile_number', 'iban', 'bic'],
    'jobs' => ['client_reference', 'description', 'unit'],
    'invoices' => ['client_reference', 'number']
];

foreach ($tables as $table => $columns) {
    foreach ($columns as $column) {
        $query = "SELECT id, $column FROM $table WHERE $column IS NOT NULL AND $column != ''";
        $result = $db->query($query);
        while ($row = $db->fetchObjectWithoutClass($result)) {
            $original = $row->$column;
            // Fix common mojibake replacements
            $replacements = [
                "Ã©" => "é",
                "Ã¨" => "è",
                "Ã¼" => "ü",
                "Ã¶" => "ö",
                "Ã¤" => "ä",
                "ÃŸ" => "ß",
                "Ã¢" => "â",
                "Ã®" => "î",
                "Ã´" => "ô",
                "Ã»" => "û",
                "Ã«" => "ë",
                "Ã¯" => "ï",
                "Ã¹" => "ù",
                "Ã§" => "ç",
                "Ã€" => "À",
                "Ã?" => "É",
                "Ã‰" => "É",
                "Ãˆ" => "È",
                "Ã‹" => "Ë",
                "ÃŠ" => "Ê",
                "Ã«" => "ë",
                "Ã®" => "î",
                "Ã¯" => "ï",
                "Ã´" => "ô",
                "Ã¶" => "ö",
                "Ã¹" => "ù",
                "Ã»" => "û",
                "Ã¼" => "ü",
                "â€™" => "'",
                "â€" => '"',
                "â€œ" => '"',
                "â€" => '"',
                "â€¢" => "•",
                "â€“" => "–",
                "â€”" => "—",
                "â‚¬" => "€",
                "ÉÆ?ÉÂ©" => "é",  // Specific for this corruption
            ];
            foreach ($replacements as $corrupted => $correct) {
                $original = str_replace($corrupted, $correct, $original);
            }
            if ($original !== $row->$column) {
                $fixed = str_replace("'", "\\'", $original);
                $update_query = "UPDATE $table SET $column = '$fixed' WHERE id = {$row->id}";
                $db->query($update_query);
                echo "Updated $table.$column for id {$row->id}: {$row->$column} -> $original\n";
            }
        }
    }
}

$db->disconnect();
echo "Fix completed.\n";
?>