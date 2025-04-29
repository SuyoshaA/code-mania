<?php
 class code_format {
    public static function cpp($cppCode)
    {
    $cppCode = trim($cppCode);
    
    $cppCode = preg_replace('/[ ]{2,}/', ' ', $cppCode); // Reduce multiple spaces
    $cppCode = preg_replace('/\t/', '    ', $cppCode); // Replace tabs with spaces

    // Format new lines properly
    $cppCode = preg_replace('/\s*{\s*/', " {\n", $cppCode); // Space before '{'
    $cppCode = preg_replace('/;\s*/', ";\n    ", $cppCode); // Newline after ';'
    $cppCode = preg_replace('/}\s*/', "}\n", $cppCode); // Newline after '}'

    $lines = explode("\n", $cppCode);
    $beautifiedCode = "";
    $indentation = 0;
    $indentSize = "    "; // 4 spaces

    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === "}") {
            $indentation--; // Reduce indentation before closing bracket
        }
        
        $beautifiedCode .= str_repeat($indentSize, $indentation) . $line . "\n";

        if (strpos($line, "{") !== false) {
            $indentation++; // Increase indentation after opening bracket
        }
    }

    return $beautifiedCode;
    }

    function beautifyPythonCode($pythonCode) {
        // Trim unnecessary spaces and ensure consistent indentation
        $pythonCode = trim($pythonCode);
    
        // Normalize spaces (remove multiple spaces but keep indentation)
        $pythonCode = preg_replace('/[ ]{2,}/', ' ', $pythonCode); // Reduce multiple spaces
        $pythonCode = preg_replace('/\t/', '    ', $pythonCode); // Replace tabs with spaces
    
        // Format new lines properly
        $pythonCode = preg_replace('/:\s*/', ":\n    ", $pythonCode); // Newline after ':'
        $pythonCode = preg_replace('/\s*#/', "  #", $pythonCode); // Ensure spacing before comments
    
        // Handle indentation based on colons (simple logic for structuring blocks)
        $lines = explode("\n", $pythonCode);
        $beautifiedCode = "";
        $indentation = 0;
        $indentSize = "    "; // 4 spaces
    
        foreach ($lines as $line) {
            $line = trim($line);
    
            // Dedent for return, break, continue, or pass
            if (preg_match('/^(return|break|continue|pass)/', $line)) {
                $indentation--;
            }
    
            $beautifiedCode .= str_repeat($indentSize, max(0, $indentation)) . $line . "\n";
    
            // Increase indentation after control flow statements
            if (preg_match('/(def |class |if |elif |else|for |while |try |except |with )/', $line) && strpos($line, ":") !== false) {
                $indentation++;
            }
        }
    
        return $beautifiedCode;
    }

    function beautifyPhpCode($phpCode) {
        // Trim unnecessary spaces
        $phpCode = trim($phpCode);
    
        // Normalize spaces (remove multiple spaces but keep indentation)
        $phpCode = preg_replace('/[ ]{2,}/', ' ', $phpCode); // Reduce multiple spaces
        $phpCode = preg_replace('/\t/', '    ', $phpCode); // Replace tabs with spaces
    
        // Format new lines properly
        $phpCode = preg_replace('/;\s*/', ";\n    ", $phpCode); // Newline after ';'
        $phpCode = preg_replace('/\s*{\s*/', " {\n    ", $phpCode); // Space before '{'
        $phpCode = preg_replace('/}\s*/', "}\n", $phpCode); // Newline after '}'
        
        // Handle indentation
        $lines = explode("\n", $phpCode);
        $beautifiedCode = "";
        $indentation = 0;
        $indentSize = "    "; // 4 spaces
    
        foreach ($lines as $line) {
            $line = trim($line);
    
            if ($line === "}") {
                $indentation--; // Reduce indentation before closing bracket
            }
    
            $beautifiedCode .= str_repeat($indentSize, max(0, $indentation)) . $line . "\n";
    
            if (strpos($line, "{") !== false) {
                $indentation++; // Increase indentation after opening bracket
            }
        }
    
        return $beautifiedCode;
    }
    
};

?>