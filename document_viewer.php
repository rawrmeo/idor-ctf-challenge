<?php
session_start();

// Set user session if not exists
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 2023;
    $_SESSION['username'] = 'player';
}

// Documents database
$documents = [
    // Your documents (user 2023)
    2023001 => ["name" => "my_notes.txt", "owner" => 2023, "content" => "Personal notes here..."],
    2023002 => ["name" => "todo_list.txt", "owner" => 2023, "content" => "1. Learn web security\n2. Practice CTF"],
    
    // Other users' documents
    1001001 => ["name" => "john_report.pdf", "owner" => 1001, "content" => "Monthly report for Q1"],
    1001002 => ["name" => "john_todo.txt", "owner" => 1001, "content" => "1. Finish project\n2. Call client"],
    
    // Sarah's documents
    5001001 => ["name" => "sarah_notes.txt", "owner" => 5001, "content" => "Meeting notes with team"],
    5001002 => ["name" => "ideas.txt", "owner" => 5001, "content" => "Project ideas:\n- Mobile app\n- Website redesign"],
    
    // Alex's documents
    6001001 => ["name" => "alex_code.txt", "owner" => 6001, "content" => "function checkAccess() {\n  // TODO: Implement security\n}"],
    
    // Mark's documents - THE FLAG IS HERE
    7001001 => ["name" => "mark_backup.txt", "owner" => 7001, "content" => "Old backup file"],
    7001002 => ["name" => "mark_old.txt", "owner" => 7001, "content" => "This file contains old data"],
    7001003 => ["name" => "mark_secret.txt", "owner" => 7001, "content" => "Important: Don't share this!\nMy password hint: Remember our anniversary date\n\nPS: The secret code is: fukuma{FOUND_MARKS_SECRET}"],
];

// Get document ID from URL
$doc_id = $_GET['doc_id'] ?? 2023001;

// Check if document exists
if (isset($documents[$doc_id])) {
    $doc = $documents[$doc_id];
    
    echo "<!DOCTYPE html><html><head><title>Document Viewer</title>";
    echo "<style>
        body { 
            font-family: 'Segoe UI', sans-serif; 
            margin: 40px; 
            background: #f5f7fa;
        }
        .header { 
            background: #2c3e50; 
            color: white; 
            padding: 25px; 
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
        }
        .card { 
            background: white; 
            padding: 25px; 
            border-radius: 10px; 
            margin: 20px 0; 
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            border-top: 5px solid #3498db;
        }
        .flag { 
            background: linear-gradient(to right, #27ae60, #2ecc71);
            color: white; 
            padding: 20px; 
            border-radius: 8px; 
            margin: 20px 0;
            text-align: center;
            font-size: 1.1em;
            border: 3px solid #1e8449;
        }
        .error { 
            background: #e74c3c; 
            color: white; 
            padding: 20px; 
            border-radius: 8px;
            text-align: center;
        }
        .hint { 
            background: #f8f9fa; 
            padding: 15px; 
            border-radius: 8px; 
            border-left: 4px solid #f39c12;
            margin: 20px 0;
            font-size: 0.95em;
        }
        .nav-links {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin: 20px 0;
        }
        .nav-links a {
            background: #3498db;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
            transition: 0.3s;
        }
        .nav-links a:hover {
            background: #2980b9;
            transform: translateY(-2px);
        }
        pre {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            border: 1px solid #ddd;
            white-space: pre-wrap;
            font-family: 'Courier New', monospace;
        }
        .user-info {
            background: #ecf0f1;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
        }
    </style>";
    echo "</head><body>";
    
    echo "<div class='header'>";
    echo "<h1>📄 Company Document Viewer</h1>";
    echo "<p>Welcome, <strong>" . $_SESSION['username'] . "</strong> (Employee ID: " . $_SESSION['user_id'] . ")</p>";
    echo "</div>";
    
    // Display user info
    echo "<div class='user-info'>";
    echo "<strong>📊 Your Access:</strong> You can view documents from all employees";
    echo "</div>";
    
    echo "<div class='card'>";
    echo "<h3>📖 Document Information</h3>";
    echo "<div style='display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin: 15px 0;'>";
    echo "<div><strong>Document ID:</strong><br><code style='font-size: 1.1em;'>$doc_id</code></div>";
    echo "<div><strong>Filename:</strong><br>" . htmlspecialchars($doc['name']) . "</div>";
    echo "<div><strong>Owner ID:</strong><br><code>" . $doc['owner'] . "</code></div>";
    echo "</div>";
    
    echo "<hr style='margin: 20px 0; border: 1px solid #eee;'>";
    
    echo "<h4>📝 Document Content:</h4>";
    echo "<pre>" . htmlspecialchars($doc['content']) . "</pre>";
    
    // Show flag if found - UPDATED TO MATCH fukuma{}
    if (strpos($doc['content'], 'fukuma{') !== false) {
        echo "<div class='flag'>";
        echo "🎉 <strong>CONGRATULATIONS!</strong><br>";
        echo "You found the flag!<br><br>";
        preg_match('/fukuma\{[^}]+\}/', $doc['content'], $matches);
        echo "<div style='font-size: 1.3em; letter-spacing: 1px; background: rgba(255,255,255,0.2); padding: 10px; border-radius: 5px;'>";
        echo $matches[0];
        echo "</div>";
        echo "</div>";
    }
    
    echo "</div>";
    
} else {
    echo "<div class='error'>";
    echo "❌ <strong>Document Not Found</strong><br><br>";
    echo "Document ID <code>$doc_id</code> does not exist.";
    echo "</div>";
}

// Hint
echo "<div class='hint'>";
echo "💡 <strong>Tip:</strong> The document ID format is <code>[EmployeeID][3-digit-number]</code>";
echo "<br>Example: <code>2023001</code> means Employee <code>2023</code>, Document <code>001</code>";
echo "<br><br>";
echo "🔍 <em>Some employees might have more than one document...</em>";
echo "</div>";

// Navigation links
echo "<div class='nav-links'>";
echo "<strong>Quick Access:</strong><br>";
echo "<a href='?doc_id=2023001'>My Notes</a>";
echo "<a href='?doc_id=1001001'>John's Report</a>";
echo "<a href='?doc_id=5001001'>Sarah's Notes</a>";
echo "<a href='?doc_id=6001001'>Alex's Code</a>";
echo "</div>";

// Footer
echo "<div style='margin-top: 30px; padding: 15px; background: #2c3e50; color: white; border-radius: 8px; text-align: center;'>";
echo "🏢 Company Document System | 👤 Your ID: " . $_SESSION['user_id'] . " | 📁 Total Documents: " . count($documents);
echo "</div>";

echo "</body></html>";
?>
