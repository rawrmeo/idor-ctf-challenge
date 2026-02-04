                                                                                                                                          
<?php
session_start();

// Set user session if not exists
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 2023; // Your user ID
    $_SESSION['username'] = 'player';
}

// Documents database
$documents = [
    // Your documents (user 2023)
    2023001 => ["name" => "my_notes.txt", "owner" => 2023, "content" => "Personal notes here..."],
    2023002 => ["name" => "todo_list.txt", "owner" => 2023, "content" => "1. Learn IDOR\n2. Find flags"],
    
    // Other user's document (user 1001)
    1001001 => ["name" => "john_report.pdf", "owner" => 1001, "content" => "Monthly report data..."],
    
    // ADMIN DOCUMENTS - FLAG 1 HERE (user 9999)
    9999001 => ["name" => "admin_config.txt", "owner" => 9999, "content" => "Server config... SECRET: CTF{IDOR_ADMIN_ACCESS}"],
    
    // HIDDEN FLAG - FLAG 2 HERE (user 7777)
    7777001 => ["name" => "deleted_secret.txt", "owner" => 7777, "content" => "This file was deleted but still accessible! FLAG: CTF{HIDDEN_DOCUMENT_FOUND}"],
    
    // Decoy documents
    3001001 => ["name" => "invoice.txt", "owner" => 3001, "content" => "Invoice #3001"],
    4001001 => ["name" => "budget.xlsx", "owner" => 4001, "content" => "Budget spreadsheet"],
];

// Get document ID from URL
$doc_id = $_GET['doc_id'] ?? 2023001;

// Check if document exists
if (isset($documents[$doc_id])) {
    $doc = $documents[$doc_id];
    
    echo "<!DOCTYPE html><html><head><title>Secure Doc Viewer</title>";
    echo "<style>
        body { font-family: Arial; margin: 40px; }
        .header { background: #2c3e50; color: white; padding: 20px; border-radius: 10px; }
        .card { background: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 5px solid #3498db; }
        .flag { background: #27ae60; color: white; padding: 15px; border-radius: 5px; margin: 15px 0; }
        .error { background: #e74c3c; color: white; padding: 15px; border-radius: 5px; }
        .hint { background: #f39c12; color: white; padding: 10px; border-radius: 5px; font-size: 0.9em; }
    </style>";
    echo "</head><body>";
    
    echo "<div class='header'>";
    echo "<h1>🔒 Secure Document Viewer</h1>";
    echo "<p>Welcome, " . $_SESSION['username'] . " (ID: " . $_SESSION['user_id'] . ")</p>";
    echo "</div>";
    
    echo "<div class='card'>";
    echo "<h3>📄 Viewing Document</h3>";
    echo "<p><strong>Document ID:</strong> $doc_id</p>";
    echo "<p><strong>Filename:</strong> " . htmlspecialchars($doc['name']) . "</p>";
    echo "<p><strong>Owner ID:</strong> " . $doc['owner'] . "</p>";
    echo "<hr>";
    echo "<h4>Content:</h4>";
    echo "<pre>" . htmlspecialchars($doc['content']) . "</pre>";
    
    // Show flag if found
    if (strpos($doc['content'], 'CTF{') !== false) {
        echo "<div class='flag'>";
        echo "🏴 <strong>FLAG CAPTURED!</strong><br>";
        // Extract flag from content
        preg_match('/CTF\{[^}]+\}/', $doc['content'], $matches);
        echo $matches[0];
        echo "</div>";
    }
    
    echo "</div>";
    
} else {
    echo "<div class='error'>";
    echo "❌ Document not found. ID: $doc_id";
    echo "</div>";
}

// Hint system
echo "<div class='hint'>";
echo "💡 <strong>Hint:</strong> Document IDs follow pattern: ";
echo "<code>[owner_id][3-digit-number]</code>";
echo "<br>Example: 2023001 = owner 2023, document 001";
echo "</div>";

// Quick links for testing
echo "<div style='margin-top: 20px;'>";
echo "<strong>Test links:</strong> ";
echo "<a href='?doc_id=2023001'>My Document</a> | ";
echo "<a href='?doc_id=1001001'>John's Document</a> | ";
echo "<a href='?doc_id=3001001'>Invoice</a>";
echo "</div>";

echo "</body></html>";
?>


