-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 29, 2025 at 08:58 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tutoring_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `assignments`
--

CREATE TABLE `assignments` (
  `id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `badges`
--

CREATE TABLE `badges` (
  `id` int(11) NOT NULL,
  `badge_name` varchar(50) NOT NULL,
  `threshold` int(11) NOT NULL,
  `image_path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `badges`
--

INSERT INTO `badges` (`id`, `badge_name`, `threshold`, `image_path`) VALUES
(1, 'Beginner Badge', 20, 'beginner_badge.png'),
(2, 'Intermediate Badge', 60, 'intermediate_badge.png'),
(3, 'Pro Badge', 80, 'pro_badge.png');

-- --------------------------------------------------------

--
-- Table structure for table `capstone_projects`
--

CREATE TABLE `capstone_projects` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `project_title` varchar(255) NOT NULL,
  `project_description` text NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `certificates`
--

CREATE TABLE `certificates` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `issued_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chapters`
--

CREATE TABLE `chapters` (
  `id` int(11) NOT NULL,
  `module_id` int(11) NOT NULL,
  `chapter_name` varchar(255) NOT NULL,
  `chapter_description` text DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chapters`
--

INSERT INTO `chapters` (`id`, `module_id`, `chapter_name`, `chapter_description`, `sort_order`) VALUES
(1, 1, 'Python Basics', 'Introduction to Python programming', 1),
(2, 2, 'Conditionals and Loops', 'Making decisions and repeating actions', 1),
(10, 4, 'Core Data Structures', 'Essential collection types in Python', 1),
(21, 9, 'Working with Files', 'File operations and data persistence', 1),
(22, 10, 'Classes and Objects', 'Python OOP fundamentals', 1),
(23, 11, 'Exceptions', 'Handling errors gracefully', 1),
(24, 12, 'Code Organization', 'Creating and using modules', 1),
(25, 13, 'Web APIs', 'Interacting with web services', 1),
(26, 14, 'Project Implementation', 'Apply all learned concepts', 1),
(41, 29, 'C++ Basics', 'Introduction to C++ programming', 1),
(42, 30, 'Conditionals and Loops', 'Decision making and repetition', 1),
(43, 31, 'Functions Basics', 'Defining and using functions', 1),
(44, 32, 'Arrays and Strings', 'Storing and manipulating data collections', 1),
(45, 33, 'Pointers Basics', 'Working with memory addresses', 1),
(46, 34, 'OOP Basics', 'Classes, objects, and encapsulation', 1),
(54, 43, 'Templates', 'Generic programming in C++', 1),
(55, 44, 'File Operations', 'Reading and writing files', 1),
(56, 45, 'Project Implementation', 'Apply all learned concepts', 1),
(77, 57, 'Java Syntax', 'Basic structure of Java programs', 1),
(78, 57, 'Variables and Data Types', 'Storing and working with data', 2),
(79, 58, 'Conditionals', 'Making decisions in code', 1),
(80, 58, 'Loops', 'Repeating actions', 2),
(81, 59, 'Method Basics', 'Defining and calling methods', 1),
(82, 59, 'Parameters and Return', 'Passing data in and out', 2),
(83, 60, 'Arrays', 'Fixed-size collections', 1),
(84, 60, 'ArrayList', 'Dynamic collections', 2),
(85, 61, 'Classes and Objects', 'OOP fundamentals', 1),
(86, 61, 'Inheritance', 'Creating class hierarchies', 2),
(87, 62, 'Try-Catch', 'Handling exceptions', 1),
(88, 62, 'Custom Exceptions', 'Creating your own exception types', 2),
(89, 63, 'File Handling', 'Reading and writing files', 1),
(90, 63, 'Serialization', 'Saving objects to files', 2),
(91, 64, 'Generic Classes', 'Type-safe containers', 1),
(92, 64, 'Generic Methods', 'Type-safe functions', 2),
(93, 65, 'Thread Basics', 'Creating and running threads', 1),
(94, 65, 'Synchronization', 'Managing shared resources', 2),
(95, 66, 'Project Planning', 'Designing your application', 1),
(96, 66, 'Implementation', 'Building all components', 2),
(97, 57, 'Java Syntax', 'Basic structure of Java programs', 1),
(98, 57, 'Variables and Data Types', 'Storing and working with data', 2),
(99, 58, 'Conditionals', 'Making decisions in code', 1),
(100, 58, 'Loops', 'Repeating actions', 2),
(101, 59, 'Method Basics', 'Defining and calling methods', 1),
(102, 59, 'Parameters and Return', 'Passing data in and out', 2),
(103, 60, 'Arrays', 'Fixed-size collections', 1),
(104, 60, 'ArrayList', 'Dynamic collections', 2),
(105, 61, 'Classes and Objects', 'OOP fundamentals', 1),
(106, 61, 'Inheritance', 'Creating class hierarchies', 2),
(107, 62, 'Try-Catch', 'Handling exceptions', 1),
(108, 62, 'Custom Exceptions', 'Creating your own exception types', 2),
(109, 63, 'File Handling', 'Reading and writing files', 1),
(110, 63, 'Serialization', 'Saving objects to files', 2),
(111, 64, 'Generic Classes', 'Type-safe containers', 1),
(112, 64, 'Generic Methods', 'Type-safe functions', 2),
(113, 65, 'Thread Basics', 'Creating and running threads', 1),
(114, 65, 'Synchronization', 'Managing shared resources', 2),
(115, 66, 'Project Planning', 'Designing your application', 1),
(116, 66, 'Implementation', 'Building all components', 2),
(117, 67, 'HTML Structure', 'Basic document structure', 1),
(118, 67, 'HTML Elements', 'Common tags and attributes', 2),
(119, 68, 'CSS Selectors', 'Targeting elements', 1),
(120, 68, 'CSS Box Model', 'Understanding layout', 2),
(121, 69, 'JS Syntax', 'Variables and functions', 1),
(122, 69, 'DOM Manipulation', 'Changing page content', 2),
(123, 70, 'Media Queries', 'Adapting to screen sizes', 1),
(124, 70, 'Flexbox', 'Modern layout techniques', 2),
(125, 71, 'Component Basics', 'Building UI components', 1),
(126, 71, 'State Management', 'Handling application state', 2),
(127, 72, 'Server Setup', 'Creating a Node server', 1),
(128, 72, 'Routing', 'Handling different URLs', 2),
(129, 73, 'SQL Basics', 'Relational databases', 1),
(130, 73, 'NoSQL', 'MongoDB and document stores', 2),
(131, 74, 'REST Principles', 'Designing RESTful APIs', 1),
(132, 74, 'API Consumption', 'Fetching data from APIs', 2),
(133, 75, 'Sessions & Cookies', 'Traditional auth', 1),
(134, 75, 'JWT', 'Token-based auth', 2),
(135, 76, 'Project Planning', 'Designing your app', 1),
(136, 76, 'Full Implementation', 'Building all components', 2);

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `course_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `teacher_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `course_name`, `description`, `teacher_id`) VALUES
(1, 'Complete Python Mastery', 'From absolute beginner to advanced Python programming with hands-on projects', 6),
(2, 'Complete C++ Mastery', 'From basic syntax to advanced C++ programming with hands-on projects', 7),
(3, 'Complete Web Development', 'Master front-end and back-end web technologies', 8),
(4, 'Complete Java Mastery', 'From basic syntax to advanced Java programming with OOP concepts', 9);

-- --------------------------------------------------------

--
-- Table structure for table `lessons`
--

CREATE TABLE `lessons` (
  `id` int(11) NOT NULL,
  `chapter_id` int(11) NOT NULL,
  `lesson_title` varchar(255) NOT NULL,
  `lecture_content` text DEFAULT NULL,
  `video_url` varchar(255) DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lessons`
--

INSERT INTO `lessons` (`id`, `chapter_id`, `lesson_title`, `lecture_content`, `video_url`, `sort_order`) VALUES
(1, 1, 'Getting Started with Python', '## Python Introduction\nPython is a high-level, interpreted programming language known for its readability and versatility.\n\n### Key Concepts Covered:\n- Installing Python and setting up your environment\n- Running Python in interactive mode and scripts\n- Basic syntax rules and indentation\n- Variables and data types (int, float, str, bool)\n- Basic input/output with print() and input()\n- Simple arithmetic operations\n\n### Example Code:\n```python\n# Simple Python program\nname = input(\"What\'s your name? \")\nage = int(input(\"How old are you? \"))\nprint(f\"Hello {name}! Next year you\'ll be {age+1} years old.\")\n```', 'https://example.com/python-intro', 1),
(2, 2, 'Controlling Program Flow', '## Making Decisions in Python\nControl flow statements determine the order in which code executes.\n\n### Key Concepts Covered:\n- if/elif/else statements\n- Comparison operators (==, !=, >, <, >=, <=)\n- Logical operators (and, or, not)\n- while loops for indefinite iteration\n- for loops with range() and sequences\n- break and continue statements\n\n### Example Code:\n```python\n# Control flow examples\nage = 18\nif age < 13:\n    print(\"Child\")\nelif age < 20:\n    print(\"Teenager\")\nelse:\n    print(\"Adult\")\n\n# Loop through numbers 1-5\nfor i in range(1, 6):\n    if i == 3:\n        continue\n    print(i)\n```', 'https://example.com/python-control-flow', 1),
(9, 10, 'Lists, Tuples, Dictionaries and Sets', '## Python Data Structures\nPython provides several built-in data structures for efficient data organization.\n\n### Key Concepts Covered:\n- Lists: ordered, mutable collections\n- Tuples: ordered, immutable collections\n- Dictionaries: key-value pairs\n- Sets: unordered, unique elements\n- Common operations on each structure\n- List comprehensions\n- Dictionary comprehensions\n\n### Example Code:\n```python\n# Data structure examples\nfruits = [\"apple\", \"banana\", \"cherry\"]  # List\ncoordinates = (10.5, 20.3)  # Tuple\nperson = {\"name\": \"Alice\", \"age\": 25}  # Dictionary\nunique_numbers = {1, 2, 3, 3, 4}  # Set (will be {1, 2, 3, 4})\n\n# List comprehension\nsquares = [x**2 for x in range(10)]\n```', 'https://example.com/python-data-structures', 1),
(19, 21, 'File Operations', '## Python File Handling\nPython makes it easy to work with files for data storage and retrieval.\n\n### Key Concepts Covered:\n- Opening and closing files\n- Different file modes (r, w, a, b)\n- Reading file content (read(), readline(), readlines())\n- Writing to files (write(), writelines())\n- Working with file paths (os.path)\n- Context managers (with statement)\n- Working with CSV and JSON files\n\n### Example Code:\n```python\n# File handling examples\nwith open(\"data.txt\", \"w\") as file:\n    file.write(\"Hello, World!\")\n\n# Reading CSV\nimport csv\nwith open(\"data.csv\") as csvfile:\n    reader = csv.DictReader(csvfile)\n    for row in reader:\n        print(row[\"name\"])\n```', 'https://example.com/python-file-handling', 1),
(20, 22, 'OOP in Python', '## Object-Oriented Python\nPython supports object-oriented programming with classes and objects.\n\n### Key Concepts Covered:\n- Creating classes and objects\n- The __init__ method (constructor)\n- Instance methods and attributes\n- Class attributes and methods\n- Inheritance and polymorphism\n- Magic methods (__str__, __repr__)\n- Property decorators\n\n### Example Code:\n```python\n# OOP example\nclass Animal:\n    def __init__(self, name):\n        self.name = name\n\n    def speak(self):\n        return \"Sound\"\n\nclass Dog(Animal):\n    def speak(self):\n        return \"Bark!\"\n\nbuddy = Dog(\"Buddy\")\nprint(buddy.speak())  # Output: Bark!\n```', 'https://example.com/python-oop', 1),
(21, 23, 'Try/Except Blocks', '## Error Handling in Python\nPython uses exceptions to handle errors gracefully during program execution.\n\n### Key Concepts Covered:\n- Common exception types (ValueError, TypeError, etc.)\n- try/except blocks\n- Handling multiple exceptions\n- The else and finally clauses\n- Raising exceptions\n- Creating custom exceptions\n- Debugging with pdb\n\n### Example Code:\n```python\n# Exception handling example\ntry:\n    num = int(input(\"Enter number: \"))\n    result = 10 / num\nexcept ValueError:\n    print(\"Not a valid number!\")\nexcept ZeroDivisionError:\n    print(\"Can\'t divide by zero!\")\nelse:\n    print(f\"Result is {result}\")\nfinally:\n    print(\"Execution complete\")\n```', 'https://example.com/python-error-handling', 1),
(22, 24, 'Python Modules', '## Python Modules and Packages\nPython modules help organize code into reusable components.\n\n### Key Concepts Covered:\n- Creating and importing modules\n- The import statement variations\n- Package structure and __init__.py\n- The Python module search path\n- Installing third-party packages with pip\n- Virtual environments\n- Common standard library modules\n\n### Example Code:\n```python\n# Module example\n# mymodule.py\ndef greet(name):\n    return f\"Hello, {name}!\"\n\n# main.py\nimport mymodule\nprint(mymodule.greet(\"Alice\"))\n\n# Alternative import\nfrom mymodule import greet\nprint(greet(\"Bob\"))\n```', 'https://example.com/python-modules', 1),
(23, 25, 'HTTP Requests', '## Python and Web APIs\nPython can interact with web services through HTTP requests.\n\n### Key Concepts Covered:\n- The requests library\n- HTTP methods (GET, POST, PUT, DELETE)\n- Working with JSON data\n- API authentication\n- Handling response codes\n- Rate limiting\n- Building a simple REST client\n\n### Example Code:\n```python\n# API example with requests\nimport requests\n\nresponse = requests.get(\"https://api.github.com/users/octocat\")\nif response.status_code == 200:\n    data = response.json()\n    print(f\"User: {data[\'login\']}\")\n    print(f\"Bio: {data[\'bio\']}\")\nelse:\n    print(\"Error:\", response.status_code)\n```', 'https://example.com/python-apis', 1),
(24, 26, 'Building a Complete Application', '## Python Final Project\nApply all the concepts you\'ve learned to build a complete application.\n\n### Project Description:\nBuild a weather application that:\n- Takes city name as input\n- Fetches weather data from a public API\n- Displays current conditions and forecast\n- Stores search history in a file\n- Has error handling for invalid inputs\n- Uses proper code organization\n\n### Implementation Steps:\n1. Set up project structure\n2. Create configuration for API keys\n3. Implement API client\n4. Create user interface\n5. Add file persistence\n6. Implement error handling\n7. Write documentation\n\n### Example Starter Code:\n```python\nimport requests\nimport json\nfrom datetime import datetime\n\nclass WeatherApp:\n    def __init__(self, api_key):\n        self.api_key = api_key\n        self.history = []\n\n    def get_weather(self, city):\n        try:\n            url = f\"http://api.openweathermap.org/data/2.5/weather?q={city}&appid={self.api_key}\"\n            response = requests.get(url)\n            response.raise_for_status()\n            data = response.json()\n            self.history.append({\n                \"city\": city,\n                \"time\": datetime.now().strftime(\"%Y-%m-%d %H:%M:%S\")\n            })\n            return data\n        except requests.exceptions.RequestException as e:\n            print(f\"Error fetching weather: {e}\")\n            return None\n\n# More implementation would go here...\n```', 'https://example.com/python-final-project', 1),
(37, 41, 'Getting Started with C++', '## C++ Introduction\nC++ is a powerful general-purpose programming language with object-oriented features.\n\n### Key Concepts Covered:\n- Setting up a C++ development environment\n- Structure of a C++ program\n#include directives\n- main() function\n- Basic I/O with cin and cout\n- Data types (int, float, double, char, bool)\n- Variables and constants\n- Basic operators\n\n### Example Code:\n```cpp\n#include <iostream>\nusing namespace std;\n\nint main() {\n    int age;\n    cout << \"Enter your age: \";\n    cin >> age;\n    cout << \"Next year you will be \" << age + 1 << endl;\n    return 0;\n}\n```', 'https://example.com/cpp-intro', 1),
(38, 42, 'Control Structures', '## Control Flow in C++\nC++ provides several control structures to manage program flow.\n\n### Key Concepts Covered:\n- if/else statements\n- switch statements\n- Ternary operator\n- while and do-while loops\n- for loops\n- break and continue\n- Logical operators (&&, ||, !)\n- Comparison operators\n\n### Example Code:\n```cpp\n#include <iostream>\nusing namespace std;\n\nint main() {\n    int score;\n    cout << \"Enter test score: \";\n    cin >> score;\n\n    if (score >= 90) {\n        cout << \"Grade: A\" << endl;\n    } else if (score >= 80) {\n        cout << \"Grade: B\" << endl;\n    } else {\n        cout << \"Grade: C\" << endl;\n    }\n\n    for (int i = 1; i <= 5; i++) {\n        if (i == 3) continue;\n        cout << i << endl;\n    }\n    return 0;\n}\n```', 'https://example.com/cpp-control-flow', 1),
(39, 43, 'Working with Functions', '## Functions in C++\nFunctions allow you to organize code into reusable blocks.\n\n### Key Concepts Covered:\n- Function declaration and definition\n- Return types and void\n- Parameters and arguments\n- Function prototypes\n- Pass by value vs pass by reference\n- Function overloading\n- Default arguments\n- Recursion\n\n### Example Code:\n```cpp\n#include <iostream>\nusing namespace std;\n\n// Function prototype\nint add(int a, int b);\n\nint main() {\n    int result = add(5, 3);\n    cout << \"Sum: \" << result << endl;\n    return 0;\n}\n\n// Function definition\nint add(int a, int b) {\n    return a + b;\n}\n```', 'https://example.com/cpp-functions', 1),
(40, 44, 'Arrays and Strings', '## Arrays and Strings in C++\nC++ provides several ways to work with collections of data.\n\n### Key Concepts Covered:\n- Array declaration and initialization\n- Multi-dimensional arrays\n- C-style strings\n- string class from STL\n- Common string operations\n- Array bounds and safety\n- Character arrays vs strings\n- Array algorithms\n\n### Example Code:\n```cpp\n#include <iostream>\n#include <string>\nusing namespace std;\n\nint main() {\n    int numbers[5] = {1, 2, 3, 4, 5};\n    char name[] = \"John\";  // C-style string\n    string greeting = \"Hello\";  // C++ string\n\n    cout << \"Third number: \" << numbers[2] << endl;\n    cout << \"Name length: \" << greeting.length() << endl;\n    return 0;\n}\n```', 'https://example.com/cpp-arrays', 1),
(41, 45, 'Understanding Pointers', '## Pointers in C++\nPointers are variables that store memory addresses.\n\n### Key Concepts Covered:\n- Pointer declaration and initialization\n- Address-of operator (&)\n- Dereference operator (*)\n- Pointer arithmetic\n- Pointers and arrays\n- Pointers to pointers\n- nullptr\n- Dynamic memory allocation\n\n### Example Code:\n```cpp\n#include <iostream>\nusing namespace std;\n\nint main() {\n    int var = 5;\n    int* ptr = &var;  // Pointer to var\n\n    cout << \"Value: \" << *ptr << endl;  // Dereference\n    cout << \"Address: \" << ptr << endl;\n\n    // Dynamic allocation\n    int* arr = new int[5];\n    delete[] arr;  // Don\'t forget to free!\n    return 0;\n}\n```', 'https://example.com/cpp-pointers', 1),
(42, 46, 'Classes and Objects', '## OOP in C++\nC++ supports object-oriented programming with classes and objects.\n\n### Key Concepts Covered:\n- Class definition\n- Objects and instantiation\n- Member variables and functions\n- Constructors and destructors\n- Access specifiers (public, private, protected)\n- Encapsulation\n- this pointer\n- Static members\n\n### Example Code:\n```cpp\n#include <iostream>\nusing namespace std;\n\nclass Rectangle {\nprivate:\n    int width, height;\npublic:\n    Rectangle(int w, int h) : width(w), height(h) {}\n    int area() { return width * height; }\n};\n\nint main() {\n    Rectangle rect(3, 4);\n    cout << \"Area: \" << rect.area() << endl;\n    return 0;\n}\n```', 'https://example.com/cpp-oop', 1),
(50, 54, 'Function and Class Templates', '## Templates in C++\nTemplates enable generic programming in C++.\n\n### Key Concepts Covered:\n- Function templates\n- Class templates\n- Template parameters\n- Template specialization\n- STL containers\n- Generic algorithms\n- Type deduction\n- Variadic templates\n\n### Example Code:\n```cpp\n#include <iostream>\nusing namespace std;\n\ntemplate <typename T>\nT max(T a, T b) {\n    return (a > b) ? a : b;\n}\n\nint main() {\n    cout << max(3, 7) << endl;  // int\n    cout << max(3.14, 2.71) << endl;  // double\n    return 0;\n}\n```', 'https://example.com/cpp-templates', 1),
(51, 55, 'File Handling', '## File I/O in C++\nC++ provides several ways to work with files.\n\n### Key Concepts Covered:\n- fstream, ifstream, ofstream\n- File opening modes\n- Reading and writing text\n- Binary files\n- File position pointers\n- Error handling\n- Serialization\n- File system operations\n\n### Example Code:\n```cpp\n#include <iostream>\n#include <fstream>\nusing namespace std;\n\nint main() {\n    ofstream outfile(\"data.txt\");\n    outfile << \"Hello, File!\" << endl;\n    outfile.close();\n\n    ifstream infile(\"data.txt\");\n    string line;\n    getline(infile, line);\n    cout << line << endl;\n    return 0;\n}\n```', 'https://example.com/cpp-files', 1),
(52, 56, 'Building a Complete Application', '## C++ Final Project\nApply all the concepts you\'ve learned to build a complete application.\n\n### Project Description:\nBuild a banking system that:\n- Manages customer accounts\n- Supports deposits and withdrawals\n- Maintains transaction history\n- Saves data to files\n- Uses proper OOP design\n- Implements error handling\n- Has a console interface\n\n### Implementation Steps:\n1. Design class hierarchy\n2. Implement Account base class\n3. Create derived account types\n4. Implement transaction processing\n5. Add file persistence\n6. Create user interface\n7. Test thoroughly\n\n### Example Starter Code:\n```cpp\n#include <iostream>\n#include <vector>\n#include <fstream>\nusing namespace std;\n\nclass Account {\nprotected:\n    string accountNumber;\n    double balance;\npublic:\n    Account(string num) : accountNumber(num), balance(0) {}\n    virtual void deposit(double amount) {\n        balance += amount;\n    }\n    virtual void withdraw(double amount) {\n        if (amount <= balance) {\n            balance -= amount;\n        }\n    }\n    double getBalance() const { return balance; }\n};\n\n// More implementation would go here...\n```', 'https://example.com/cpp-final-project', 1),
(95, 77, 'Hello World in Java', 'Writing your first Java program', 'https://example.com/java-hello-world', 1),
(96, 77, 'Java Program Structure', 'Understanding classes, methods and main', 'https://example.com/java-structure', 2),
(97, 78, 'Primitive Data Types', 'int, double, boolean and more', 'https://example.com/java-primitives', 1),
(98, 78, 'Reference Types', 'Objects and Strings', 'https://example.com/java-references', 2),
(99, 79, 'If-Else Statements', 'Making decisions in code', 'https://example.com/java-if-else', 1),
(100, 79, 'Switch Statements', 'Multi-way branching', 'https://example.com/java-switch', 2),
(101, 80, 'For Loops', 'Count-controlled repetition', 'https://example.com/java-for', 1),
(102, 80, 'While Loops', 'Condition-controlled repetition', 'https://example.com/java-while', 2),
(103, 81, 'Defining Methods', 'Creating reusable code blocks', 'https://example.com/java-methods', 1),
(104, 81, 'Method Calls', 'Executing methods', 'https://example.com/java-method-calls', 2),
(105, 82, 'Parameters', 'Passing data to methods', 'https://example.com/java-parameters', 1),
(106, 82, 'Return Values', 'Getting data back from methods', 'https://example.com/java-return', 2),
(107, 83, 'Array Basics', 'Creating and using arrays', 'https://example.com/java-arrays', 1),
(108, 83, 'Array Operations', 'Sorting, searching and more', 'https://example.com/java-array-ops', 2),
(109, 84, 'ArrayList Introduction', 'Dynamic arrays', 'https://example.com/java-arraylist', 1),
(110, 84, 'Common Operations', 'Adding, removing and finding elements', 'https://example.com/java-list-ops', 2),
(111, 85, 'Class Definition', 'Creating your own types', 'https://example.com/java-classes', 1),
(112, 85, 'Object Creation', 'Instantiating classes', 'https://example.com/java-objects', 2),
(113, 86, 'Inheritance Basics', 'Extending classes', 'https://example.com/java-inheritance', 1),
(114, 86, 'Method Overriding', 'Changing inherited behavior', 'https://example.com/java-overriding', 2),
(115, 87, 'Try-Catch Blocks', 'Handling exceptions', 'https://example.com/java-try-catch', 1),
(116, 87, 'Finally Block', 'Cleanup code', 'https://example.com/java-finally', 2),
(117, 88, 'Custom Exception Classes', 'Defining your own exceptions', 'https://example.com/java-custom-exceptions', 1),
(118, 88, 'Exception Best Practices', 'When and how to use exceptions', 'https://example.com/java-exception-practices', 2),
(119, 89, 'Reading Files', 'FileReader and Scanner', 'https://example.com/java-file-reading', 1),
(120, 89, 'Writing Files', 'FileWriter and PrintWriter', 'https://example.com/java-file-writing', 2),
(121, 90, 'Object Serialization', 'Saving objects to files', 'https://example.com/java-serialization', 1),
(122, 90, 'Deserialization', 'Loading objects from files', 'https://example.com/java-deserialization', 2),
(123, 91, 'Generic Classes', 'Type-safe containers', 'https://example.com/java-generic-classes', 1),
(124, 91, 'Type Parameters', 'Working with generic types', 'https://example.com/java-type-params', 2),
(125, 92, 'Generic Methods', 'Type-safe functions', 'https://example.com/java-generic-methods', 1),
(126, 92, 'Bounded Types', 'Restricting generic parameters', 'https://example.com/java-bounded-types', 2),
(127, 93, 'Thread Creation', 'Extending Thread and implementing Runnable', 'https://example.com/java-thread-creation', 1),
(128, 93, 'Thread Lifecycle', 'States of a thread', 'https://example.com/java-thread-states', 2),
(129, 94, 'Synchronized Methods', 'Managing concurrent access', 'https://example.com/java-synchronized', 1),
(130, 94, 'Deadlocks', 'Avoiding thread contention', 'https://example.com/java-deadlocks', 2),
(131, 95, 'Requirements Analysis', 'Defining project scope', 'https://example.com/java-project-analysis', 1),
(132, 95, 'Class Design', 'Planning your object model', 'https://example.com/java-class-design', 2),
(133, 96, 'Implementation Strategy', 'Building your application', 'https://example.com/java-implementation', 1),
(134, 96, 'Testing and Debugging', 'Ensuring quality', 'https://example.com/java-testing', 2),
(135, 77, 'Hello World in Java', 'Writing your first Java program', 'https://example.com/java-hello-world', 1),
(136, 77, 'Java Program Structure', 'Understanding classes, methods and main', 'https://example.com/java-structure', 2),
(137, 78, 'Primitive Data Types', 'int, double, boolean and more', 'https://example.com/java-primitives', 1),
(138, 78, 'Reference Types', 'Objects and Strings', 'https://example.com/java-references', 2),
(139, 79, 'If-Else Statements', 'Making decisions in code', 'https://example.com/java-if-else', 1),
(140, 79, 'Switch Statements', 'Multi-way branching', 'https://example.com/java-switch', 2),
(141, 80, 'For Loops', 'Count-controlled repetition', 'https://example.com/java-for', 1),
(142, 80, 'While Loops', 'Condition-controlled repetition', 'https://example.com/java-while', 2),
(143, 81, 'Defining Methods', 'Creating reusable code blocks', 'https://example.com/java-methods', 1),
(144, 81, 'Method Calls', 'Executing methods', 'https://example.com/java-method-calls', 2),
(145, 82, 'Parameters', 'Passing data to methods', 'https://example.com/java-parameters', 1),
(146, 82, 'Return Values', 'Getting data back from methods', 'https://example.com/java-return', 2),
(147, 83, 'Array Basics', 'Creating and using arrays', 'https://example.com/java-arrays', 1),
(148, 83, 'Array Operations', 'Sorting, searching and more', 'https://example.com/java-array-ops', 2),
(149, 84, 'ArrayList Introduction', 'Dynamic arrays', 'https://example.com/java-arraylist', 1),
(150, 84, 'Common Operations', 'Adding, removing and finding elements', 'https://example.com/java-list-ops', 2),
(151, 85, 'Class Definition', 'Creating your own types', 'https://example.com/java-classes', 1),
(152, 85, 'Object Creation', 'Instantiating classes', 'https://example.com/java-objects', 2),
(153, 86, 'Inheritance Basics', 'Extending classes', 'https://example.com/java-inheritance', 1),
(154, 86, 'Method Overriding', 'Changing inherited behavior', 'https://example.com/java-overriding', 2),
(155, 87, 'Try-Catch Blocks', 'Handling exceptions', 'https://example.com/java-try-catch', 1),
(156, 87, 'Finally Block', 'Cleanup code', 'https://example.com/java-finally', 2),
(157, 88, 'Custom Exception Classes', 'Defining your own exceptions', 'https://example.com/java-custom-exceptions', 1),
(158, 88, 'Exception Best Practices', 'When and how to use exceptions', 'https://example.com/java-exception-practices', 2),
(159, 89, 'Reading Files', 'FileReader and Scanner', 'https://example.com/java-file-reading', 1),
(160, 89, 'Writing Files', 'FileWriter and PrintWriter', 'https://example.com/java-file-writing', 2),
(161, 90, 'Object Serialization', 'Saving objects to files', 'https://example.com/java-serialization', 1),
(162, 90, 'Deserialization', 'Loading objects from files', 'https://example.com/java-deserialization', 2),
(163, 91, 'Generic Classes', 'Type-safe containers', 'https://example.com/java-generic-classes', 1),
(164, 91, 'Type Parameters', 'Working with generic types', 'https://example.com/java-type-params', 2),
(165, 92, 'Generic Methods', 'Type-safe functions', 'https://example.com/java-generic-methods', 1),
(166, 92, 'Bounded Types', 'Restricting generic parameters', 'https://example.com/java-bounded-types', 2),
(167, 93, 'Thread Creation', 'Extending Thread and implementing Runnable', 'https://example.com/java-thread-creation', 1),
(168, 93, 'Thread Lifecycle', 'States of a thread', 'https://example.com/java-thread-states', 2),
(169, 94, 'Synchronized Methods', 'Managing concurrent access', 'https://example.com/java-synchronized', 1),
(170, 94, 'Deadlocks', 'Avoiding thread contention', 'https://example.com/java-deadlocks', 2),
(171, 95, 'Requirements Analysis', 'Defining project scope', 'https://example.com/java-project-analysis', 1),
(172, 95, 'Class Design', 'Planning your object model', 'https://example.com/java-class-design', 2),
(173, 96, 'Implementation Strategy', 'Building your application', 'https://example.com/java-implementation', 1),
(174, 96, 'Testing and Debugging', 'Ensuring quality', 'https://example.com/java-testing', 2),
(175, 97, 'Basic HTML Document', 'Creating your first page', 'https://example.com/html-basics', 1),
(176, 97, 'HTML5 Semantic Elements', 'header, footer, article, etc.', 'https://example.com/html-semantic', 2),
(177, 98, 'Common HTML Tags', 'div, span, p, a, img', 'https://example.com/html-tags', 1),
(178, 98, 'Forms and Inputs', 'Creating user inputs', 'https://example.com/html-forms', 2),
(179, 99, 'Basic Selectors', 'Element, class, ID selectors', 'https://example.com/css-selectors', 1),
(180, 99, 'Pseudo-classes', ':hover, :focus, etc.', 'https://example.com/css-pseudo', 2),
(181, 100, 'Margin, Border, Padding', 'Understanding spacing', 'https://example.com/css-box', 1),
(182, 100, 'Positioning', 'Relative, absolute, fixed', 'https://example.com/css-position', 2),
(183, 101, 'Variables and Data Types', 'let, const, strings, numbers', 'https://example.com/js-variables', 1),
(184, 101, 'Functions and Scope', 'Declaring and calling functions', 'https://example.com/js-functions', 2),
(185, 102, 'Selecting Elements', 'getElementById, querySelector', 'https://example.com/js-dom-select', 1),
(186, 102, 'Event Listeners', 'Handling user interactions', 'https://example.com/js-events', 2),
(187, 103, 'Breakpoints', 'Designing for different screens', 'https://example.com/responsive-breakpoints', 1),
(188, 103, 'Viewport Meta Tag', 'Mobile rendering control', 'https://example.com/responsive-viewport', 2),
(189, 104, 'Flex Container', 'display: flex properties', 'https://example.com/flex-container', 1),
(190, 104, 'Flex Items', 'Controlling child elements', 'https://example.com/flex-items', 2),
(191, 105, 'Creating Components', 'Building reusable UI', 'https://example.com/react-components', 1),
(192, 105, 'Props', 'Passing data between components', 'https://example.com/react-props', 2),
(193, 106, 'Local State', 'Managing component state', 'https://example.com/react-state', 1),
(194, 106, 'Context API', 'Global state management', 'https://example.com/react-context', 2),
(195, 107, 'Creating a Server', 'Express.js basics', 'https://example.com/node-server', 1),
(196, 107, 'Middleware', 'Processing requests', 'https://example.com/node-middleware', 2),
(197, 108, 'Basic Routes', 'Handling GET/POST requests', 'https://example.com/node-routes', 1),
(198, 108, 'Route Parameters', 'Dynamic URL segments', 'https://example.com/node-params', 2),
(199, 109, 'Database Design', 'Tables and relationships', 'https://example.com/sql-design', 1),
(200, 109, 'CRUD Operations', 'Create, Read, Update, Delete', 'https://example.com/sql-crud', 2),
(201, 110, 'MongoDB Basics', 'Collections and documents', 'https://example.com/mongodb-basics', 1),
(202, 110, 'Mongoose ODM', 'Working with MongoDB in Node', 'https://example.com/mongoose', 2),
(203, 111, 'HTTP Methods', 'GET, POST, PUT, DELETE', 'https://example.com/rest-methods', 1),
(204, 111, 'Resource Design', 'Naming and structuring endpoints', 'https://example.com/rest-design', 2),
(205, 112, 'Fetch API', 'Making HTTP requests', 'https://example.com/api-fetch', 1),
(206, 112, 'Axios', 'Promise-based HTTP client', 'https://example.com/api-axios', 2),
(207, 113, 'Express Sessions', 'Managing user sessions', 'https://example.com/auth-sessions', 1),
(208, 113, 'Password Hashing', 'Storing credentials safely', 'https://example.com/auth-hashing', 2),
(209, 114, 'Token Basics', 'How JWT works', 'https://example.com/jwt-basics', 1),
(210, 114, 'Implementing JWT', 'Adding to your application', 'https://example.com/jwt-implement', 2),
(211, 115, 'Requirements Gathering', 'Defining features', 'https://example.com/project-planning', 1),
(212, 115, 'Database Schema', 'Designing data structure', 'https://example.com/project-schema', 2),
(213, 116, 'Frontend Development', 'Building the UI', 'https://example.com/project-frontend', 1),
(214, 116, 'Backend Development', 'Implementing API endpoints', 'https://example.com/project-backend', 2);

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `modules`
--

CREATE TABLE `modules` (
  `id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `module_name` varchar(255) NOT NULL,
  `module_description` text DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `is_capstone` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `modules`
--

INSERT INTO `modules` (`id`, `course_id`, `module_name`, `module_description`, `sort_order`, `is_capstone`) VALUES
(1, 1, 'Python Fundamentals', 'Core syntax, variables, and basic operations', 1, 0),
(2, 1, 'Control Flow', 'Conditionals and loops to control program execution', 2, 0),
(3, 1, 'Functions', 'Creating reusable code blocks', 3, 0),
(4, 1, 'Data Structures', 'Working with lists, tuples, dictionaries, and sets', 4, 0),
(9, 1, 'File Handling', 'Reading and writing files in Python', 5, 0),
(10, 1, 'Object-Oriented Programming', 'Classes, objects, and inheritance', 6, 0),
(11, 1, 'Error Handling', 'Exceptions and debugging', 7, 0),
(12, 1, 'Modules and Packages', 'Organizing and reusing code', 8, 0),
(13, 1, 'Working with APIs', 'HTTP requests and web APIs', 9, 0),
(14, 1, 'Final Project', 'Building a complete Python application', 10, 0),
(29, 2, 'C++ Fundamentals', 'Basic syntax, variables, and I/O operations', 1, 0),
(30, 2, 'Control Flow', 'Conditionals and loops in C++', 2, 0),
(31, 2, 'Functions', 'Creating reusable code blocks', 3, 0),
(32, 2, 'Arrays and Strings', 'Working with collections of data', 4, 0),
(33, 2, 'Pointers', 'Memory addresses and references', 5, 0),
(34, 2, 'Object-Oriented Programming', 'Classes and objects', 6, 0),
(35, 2, 'Inheritance and Polymorphism', 'Advanced OOP concepts', 7, 0),
(43, 2, 'Templates', 'Generic programming', 8, 0),
(44, 2, 'File I/O', 'Working with files', 9, 0),
(45, 2, 'Final Project', 'Building a complete C++ application', 10, 0),
(57, 4, 'Java Basics', 'Introduction to Java programming', 1, 0),
(58, 4, 'Control Flow', 'Conditionals and loops in Java', 2, 0),
(59, 4, 'Methods', 'Creating reusable code blocks', 3, 0),
(60, 4, 'Arrays and Collections', 'Working with data structures', 4, 0),
(61, 4, 'Object-Oriented Programming', 'Classes, objects and inheritance', 5, 0),
(62, 4, 'Exception Handling', 'Dealing with errors', 6, 0),
(63, 4, 'File I/O', 'Reading and writing files', 7, 0),
(64, 4, 'Generics', 'Type-safe collections', 8, 0),
(65, 4, 'Multithreading', 'Concurrent programming', 9, 0),
(66, 4, 'Final Project', 'Build a complete Java application', 10, 1),
(67, 4, 'Java Basics', 'Introduction to Java programming', 1, 0),
(68, 4, 'Control Flow', 'Conditionals and loops in Java', 2, 0),
(69, 4, 'Methods', 'Creating reusable code blocks', 3, 0),
(70, 4, 'Arrays and Collections', 'Working with data structures', 4, 0),
(71, 4, 'Object-Oriented Programming', 'Classes, objects and inheritance', 5, 0),
(72, 4, 'Exception Handling', 'Dealing with errors', 6, 0),
(73, 4, 'File I/O', 'Reading and writing files', 7, 0),
(74, 4, 'Generics', 'Type-safe collections', 8, 0),
(75, 4, 'Multithreading', 'Concurrent programming', 9, 0),
(76, 4, 'Final Project', 'Build a complete Java application', 10, 1),
(77, 3, 'HTML Fundamentals', 'Structure of web pages', 1, 0),
(78, 3, 'CSS Styling', 'Making websites beautiful', 2, 0),
(79, 3, 'JavaScript Basics', 'Adding interactivity', 3, 0),
(80, 3, 'Responsive Design', 'Mobile-friendly websites', 4, 0),
(81, 3, 'Frontend Frameworks', 'React or Vue introduction', 5, 0),
(82, 3, 'Node.js Backend', 'Server-side JavaScript', 6, 0),
(83, 3, 'Databases', 'Storing application data', 7, 0),
(84, 3, 'APIs', 'Building and consuming APIs', 8, 0),
(85, 3, 'Authentication', 'User login systems', 9, 0),
(86, 3, 'Final Project', 'Full-stack web application', 10, 1);

-- --------------------------------------------------------

--
-- Table structure for table `peer_posts`
--

CREATE TABLE `peer_posts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `course_id` int(11) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `peer_posts`
--

INSERT INTO `peer_posts` (`id`, `user_id`, `content`, `created_at`, `course_id`, `file_path`) VALUES
(16, 27, 'hi', '2025-04-29 02:02:48', 4, NULL),
(17, 22, 'hi', '2025-04-29 03:16:10', 1, NULL),
(18, 22, 'hello', '2025-04-29 03:19:24', 1, NULL),
(19, 22, 'kaise ho', '2025-04-29 03:19:49', 1, NULL),
(20, 22, 'hi', '2025-04-29 05:27:32', 2, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `peer_reviews`
--

CREATE TABLE `peer_reviews` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `reviewer_id` int(11) NOT NULL,
  `review_text` text NOT NULL,
  `rating` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quizzes`
--

CREATE TABLE `quizzes` (
  `id` int(11) NOT NULL,
  `chapter_id` int(11) NOT NULL,
  `quiz_title` varchar(255) NOT NULL,
  `quiz_content` text DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quizzes`
--

INSERT INTO `quizzes` (`id`, `chapter_id`, `quiz_title`, `quiz_content`, `sort_order`) VALUES
(1, 1, 'Python Basics Quiz', 'Test your understanding of fundamental Python concepts', 1),
(2, 2, 'Control Flow Quiz', 'Test your understanding of conditionals and loops', 1),
(9, 10, 'Data Structures Quiz', 'Test your understanding of Python data structures', 1),
(19, 21, 'File Handling Quiz', 'Test your understanding of file operations', 1),
(20, 22, 'OOP Quiz', 'Test your understanding of object-oriented programming', 1),
(21, 23, 'Error Handling Quiz', 'Test your understanding of exceptions', 1),
(22, 24, 'Modules Quiz', 'Test your understanding of modules and packages', 1),
(23, 25, 'APIs Quiz', 'Test your understanding of web APIs', 1),
(24, 26, 'Project Quiz', 'Test your readiness for the final project', 1),
(37, 41, 'C++ Basics Quiz', 'Test your understanding of fundamental C++ concepts', 1),
(38, 42, 'Control Flow Quiz', 'Test your understanding of C++ control structures', 1),
(39, 43, 'Functions Quiz', 'Test your understanding of C++ functions', 1),
(40, 44, 'Arrays Quiz', 'Test your understanding of arrays and strings', 1),
(41, 45, 'Pointers Quiz', 'Test your understanding of pointers', 1),
(42, 46, 'OOP Quiz', 'Test your understanding of OOP in C++', 1),
(50, 54, 'Templates Quiz', 'Test your understanding of templates', 1),
(51, 55, 'File I/O Quiz', 'Test your understanding of file operations', 1),
(52, 56, 'Project Quiz', 'Test your readiness for the final project', 1),
(73, 77, 'Java Basics Quiz', 'Test your understanding of Java fundamentals', 1),
(74, 78, 'Variables Quiz', 'Test your knowledge of Java data types', 1),
(75, 79, 'Conditionals Quiz', 'Test your decision-making skills in Java', 1),
(76, 80, 'Loops Quiz', 'Test your ability to create loops', 1),
(77, 81, 'Methods Quiz', 'Test your method creation skills', 1),
(78, 82, 'Parameters Quiz', 'Test your understanding of method parameters', 1),
(79, 83, 'Arrays Quiz', 'Test your array knowledge', 1),
(80, 84, 'Collections Quiz', 'Test your ArrayList skills', 1),
(81, 85, 'OOP Quiz', 'Test your object-oriented programming knowledge', 1),
(82, 86, 'Inheritance Quiz', 'Test your understanding of inheritance', 1),
(83, 87, 'Exception Handling Quiz', 'Test your error handling skills', 1),
(84, 88, 'Custom Exceptions Quiz', 'Test your ability to create exceptions', 1),
(85, 89, 'File I/O Quiz', 'Test your file handling knowledge', 1),
(86, 90, 'Serialization Quiz', 'Test your object serialization skills', 1),
(87, 91, 'Generics Quiz', 'Test your generic programming knowledge', 1),
(88, 92, 'Generic Methods Quiz', 'Test your type-safe function skills', 1),
(89, 93, 'Threads Quiz', 'Test your multithreading basics', 1),
(90, 94, 'Synchronization Quiz', 'Test your concurrent programming skills', 1),
(91, 95, 'Project Planning Quiz', 'Test your requirements analysis skills', 1),
(92, 96, 'Implementation Quiz', 'Test your full application development knowledge', 1),
(93, 77, 'Java Basics Quiz', 'Test your understanding of Java fundamentals', 1),
(94, 78, 'Variables Quiz', 'Test your knowledge of Java data types', 1),
(95, 79, 'Conditionals Quiz', 'Test your decision-making skills in Java', 1),
(96, 80, 'Loops Quiz', 'Test your ability to create loops', 1),
(97, 81, 'Methods Quiz', 'Test your method creation skills', 1),
(98, 82, 'Parameters Quiz', 'Test your understanding of method parameters', 1),
(99, 83, 'Arrays Quiz', 'Test your array knowledge', 1),
(100, 84, 'Collections Quiz', 'Test your ArrayList skills', 1),
(101, 85, 'OOP Quiz', 'Test your object-oriented programming knowledge', 1),
(102, 86, 'Inheritance Quiz', 'Test your understanding of inheritance', 1),
(103, 87, 'Exception Handling Quiz', 'Test your error handling skills', 1),
(104, 88, 'Custom Exceptions Quiz', 'Test your ability to create exceptions', 1),
(105, 89, 'File I/O Quiz', 'Test your file handling knowledge', 1),
(106, 90, 'Serialization Quiz', 'Test your object serialization skills', 1),
(107, 91, 'Generics Quiz', 'Test your generic programming knowledge', 1),
(108, 92, 'Generic Methods Quiz', 'Test your type-safe function skills', 1),
(109, 93, 'Threads Quiz', 'Test your multithreading basics', 1),
(110, 94, 'Synchronization Quiz', 'Test your concurrent programming skills', 1),
(111, 95, 'Project Planning Quiz', 'Test your requirements analysis skills', 1),
(112, 96, 'Implementation Quiz', 'Test your full application development knowledge', 1),
(113, 97, 'HTML Basics Quiz', 'Test your understanding of HTML fundamentals', 1),
(114, 98, 'HTML Elements Quiz', 'Test your knowledge of HTML tags and attributes', 1),
(115, 99, 'CSS Selectors Quiz', 'Test your ability to target elements', 1),
(116, 100, 'CSS Box Model Quiz', 'Test your layout understanding', 1),
(117, 101, 'JavaScript Basics Quiz', 'Test your JS fundamentals', 1),
(118, 102, 'DOM Manipulation Quiz', 'Test your ability to work with the DOM', 1),
(119, 103, 'Responsive Design Quiz', 'Test your mobile-first knowledge', 1),
(120, 104, 'Flexbox Quiz', 'Test your layout skills', 1),
(121, 105, 'React Components Quiz', 'Test your component knowledge', 1),
(122, 106, 'State Management Quiz', 'Test your state handling skills', 1),
(123, 107, 'Node Server Quiz', 'Test your backend basics', 1),
(124, 108, 'Routing Quiz', 'Test your route handling knowledge', 1),
(125, 109, 'SQL Quiz', 'Test your database skills', 1),
(126, 110, 'NoSQL Quiz', 'Test your document store knowledge', 1),
(127, 111, 'REST API Quiz', 'Test your API design understanding', 1),
(128, 112, 'API Consumption Quiz', 'Test your client-side API skills', 1),
(129, 113, 'Sessions Quiz', 'Test your auth knowledge', 1),
(130, 114, 'JWT Quiz', 'Test your token-based auth skills', 1),
(131, 115, 'Project Planning Quiz', 'Test your requirements analysis', 1),
(132, 116, 'Implementation Quiz', 'Test your full-stack skills', 1);

-- --------------------------------------------------------

--
-- Table structure for table `quiz_questions`
--

CREATE TABLE `quiz_questions` (
  `id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `question_text` text NOT NULL,
  `option_a` varchar(255) DEFAULT NULL,
  `option_b` varchar(255) DEFAULT NULL,
  `option_c` varchar(255) DEFAULT NULL,
  `option_d` varchar(255) DEFAULT NULL,
  `correct_option` char(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quiz_questions`
--

INSERT INTO `quiz_questions` (`id`, `quiz_id`, `question_text`, `option_a`, `option_b`, `option_c`, `option_d`, `correct_option`) VALUES
(1, 1, 'Which of these is NOT a Python data type?', 'int', 'float', 'char', 'string', 'C'),
(2, 1, 'What is the output of print(3 * \"a\")?', '3a', 'aaa', 'a a a', 'Error', 'B'),
(3, 1, 'How do you start a comment in Python?', '//', '#', '/*', '--', 'B'),
(4, 1, 'Which function converts user input to an integer?', 'str()', 'int()', 'input()', 'float()', 'B'),
(5, 1, 'What does the % operator do?', 'Percentage', 'Division', 'Modulus', 'Multiplication', 'C'),
(6, 1, 'Which is valid variable name?', '1var', 'var-name', 'var_name', 'var name', 'C'),
(7, 1, 'What is the result of bool(\"False\")?', 'False', 'True', 'Error', 'None', 'B'),
(8, 1, 'Which prints \"Hello\" without newline?', 'print(\"Hello\")', 'print(\"Hello\", end=\"\")', 'print(\"Hello\", end=\"\n\")', 'print(\"Hello\", newline=False)', 'B'),
(9, 1, 'What does type(3.14) return?', 'int', 'float', 'decimal', 'number', 'B'),
(10, 1, 'Which is NOT a Python keyword?', 'while', 'for', 'loop', 'def', 'C'),
(11, 2, 'Which is the correct if statement syntax?', 'if x == 5 then', 'if (x == 5)', 'if x == 5:', 'if x = 5:', 'C'),
(12, 2, 'What does range(5) generate?', '0,1,2,3,4', '1,2,3,4,5', '0,1,2,3,4,5', '1,2,3,4', 'A'),
(13, 2, 'What does break do in a loop?', 'Restarts loop', 'Skips iteration', 'Exits loop', 'Pauses loop', 'C'),
(14, 2, 'Which operator means \"not equal\"?', '!=', '<>', '~=', '!==', 'A'),
(15, 2, 'What is the output of \"a\" in \"banana\"?', 'True', 'False', 'Error', 'None', 'A'),
(16, 2, 'What does continue do?', 'Ends loop', 'Skips to next iteration', 'Restarts loop', 'Pauses execution', 'B'),
(17, 2, 'Which loop is guaranteed to run at least once?', 'for', 'while', 'do-while', 'None in Python', 'D'),
(18, 2, 'What is the result of not (True or False)?', 'True', 'False', 'None', 'Error', 'B'),
(19, 2, 'How many times does \"for i in range(3):\" run?', '1', '2', '3', '4', 'C'),
(20, 2, 'What is the output of bool(0)?', 'True', 'False', '0', 'Error', 'B'),
(81, 9, 'Which is mutable?', 'list', 'tuple', 'string', 'all of the above', 'A'),
(82, 9, 'How to access dictionary value by key?', 'dict.key', 'dict[key]', 'dict.get(key)', 'both B and C', 'D'),
(83, 9, 'What does [1, 2, 3][1:] return?', '1', '[1]', '[2, 3]', '[1, 2, 3]', 'C'),
(84, 9, 'Which creates a set?', '{}', 'set()', '[]', '()', 'B'),
(85, 9, 'What is the result of (1, 2) + (3, 4)?', '(4, 6)', '(1, 2, 3, 4)', 'Error', 'None', 'B'),
(86, 9, 'How to add to a list?', 'list.insert()', 'list.append()', 'list.add()', 'both A and B', 'D'),
(87, 9, 'Which checks if key exists?', 'key in dict', 'dict.has_key()', 'dict.exists(key)', 'dict.contains(key)', 'A'),
(88, 9, 'What is the output of {1, 2, 2, 3}?', '{1, 2, 2, 3}', '{1, 2, 3}', '[1, 2, 3]', 'Error', 'B'),
(89, 9, 'Which is ordered?', 'set', 'dict', 'list', 'both B and C', 'D'),
(90, 9, 'What does [x for x in range(3)] create?', '[0, 1, 2]', '[1, 2, 3]', 'generator', 'Error', 'A'),
(181, 19, 'Which mode appends to file?', 'r', 'w', 'a', 'x', 'C'),
(182, 19, 'What does with statement do?', 'Opens file', 'Automatically closes file', 'Handles errors', 'All of the above', 'B'),
(183, 19, 'How to read all lines into list?', 'read()', 'readline()', 'readlines()', 'getlines()', 'C'),
(184, 19, 'Which module handles CSV?', 'json', 'csv', 'pandas', 'file', 'B'),
(185, 19, 'What is the default mode for open()?', 'r', 'w', 'a', 'x', 'A'),
(186, 19, 'How to write multiple lines?', 'write()', 'writeline()', 'writelines()', 'writeall()', 'C'),
(187, 19, 'Which is NOT a file method?', 'seek()', 'tell()', 'read()', 'get()', 'D'),
(188, 19, 'What does \"b\" in mode do?', 'Backup', 'Binary', 'Both', 'None', 'B'),
(189, 19, 'How to check if file exists?', 'os.path.exists()', 'file.exists()', 'path.exists()', 'exists()', 'A'),
(190, 19, 'Which is best for large files?', 'read()', 'readline()', 'readlines()', 'None', 'B'),
(191, 20, 'What is __init__?', 'Destructor', 'Constructor', 'Initializer', 'Both B and C', 'D'),
(192, 20, 'How to call parent method?', 'parent.method()', 'super().method()', 'self.parent.method()', 'Parent.method()', 'B'),
(193, 20, 'What is self?', 'Keyword', 'Instance reference', 'Class reference', 'Module reference', 'B'),
(194, 20, 'Which is class method decorator?', '@staticmethod', '@classmethod', '@property', '@method', 'B'),
(195, 20, 'What does __str__ do?', 'String representation', 'Debugging', 'Both', 'None', 'A'),
(196, 20, 'How to make private attribute?', '__attr', '_attr', 'private attr', 'None of the above', 'B'),
(197, 20, 'What is polymorphism?', 'Many forms', 'Inheritance', 'Encapsulation', 'Abstraction', 'A'),
(198, 20, 'Which is NOT a magic method?', '__init__', '__len__', '__add__', '__function__', 'D'),
(199, 20, 'What does @property do?', 'Creates property', 'Makes read-only', 'Both', 'None', 'C'),
(200, 20, 'How to check instance type?', 'type()', 'isinstance()', 'class()', 'Both A and B', 'D'),
(201, 21, 'What is base exception?', 'Exception', 'BaseException', 'Error', 'Object', 'B'),
(202, 21, 'When does finally execute?', 'Always', 'On error', 'No error', 'Never', 'A'),
(203, 21, 'How to raise exception?', 'throw', 'raise', 'except', 'error', 'B'),
(204, 21, 'Which catches all exceptions?', 'except:', 'except Exception:', 'except BaseException:', 'All of the above', 'B'),
(205, 21, 'What is AssertionError?', 'assert failed', 'type error', 'value error', 'syntax error', 'A'),
(206, 21, 'How to create custom exception?', 'class MyError:', 'class MyError(Exception):', 'def MyError():', 'exception MyError:', 'B'),
(207, 21, 'What does else do in try?', 'Handles error', 'Runs if no error', 'Runs always', 'None', 'B'),
(208, 21, 'Which is NOT built-in exception?', 'ValueError', 'TypeError', 'CodeError', 'KeyError', 'C'),
(209, 21, 'How to get error message?', 'e.msg', 'str(e)', 'e.message', 'e.args', 'B'),
(210, 21, 'What is KeyboardInterrupt?', 'Ctrl+C', 'Program error', 'User input', 'System exit', 'A'),
(211, 22, 'What runs when module imported?', 'Nothing', 'All code', 'Only functions', 'Only classes', 'B'),
(212, 22, 'Which is NOT an import?', 'import mod', 'from mod import *', 'require mod', 'from mod import func', 'C'),
(213, 22, 'What is __name__ when run directly?', '__main__', 'module', 'script', 'None', 'A'),
(214, 22, 'How to install package?', 'python get', 'pip install', 'import', 'download', 'B'),
(215, 22, 'What is in __init__.py?', 'Package init', 'Module docs', 'Nothing required', 'All of the above', 'D'),
(216, 22, 'Which finds module path?', 'sys.path', 'os.path', 'module.path', 'path.path', 'A'),
(217, 22, 'What does pip do?', 'Installs packages', 'Creates venvs', 'Manages dependencies', 'All of the above', 'D'),
(218, 22, 'How to create venv?', 'python -m venv', 'pip venv', 'virtualenv', 'Both A and C', 'D'),
(219, 22, 'Which is standard library?', 'os', 'sys', 'math', 'All of the above', 'D'),
(220, 22, 'What is PYTHONPATH?', 'Module search path', 'Python location', 'Package index', 'Environment', 'A'),
(221, 23, 'Which library for HTTP?', 'http', 'requests', 'urllib', 'All of the above', 'D'),
(222, 23, 'What is GET?', 'Read data', 'Create data', 'Update data', 'Delete data', 'A'),
(223, 23, 'How to get JSON from response?', 'response.json', 'response.json()', 'response.text', 'response.data', 'B'),
(224, 23, 'What is 404 status?', 'Success', 'Not found', 'Error', 'Unauthorized', 'B'),
(225, 23, 'How to add headers?', 'headers param', 'set_headers()', 'add_header()', 'header()', 'A'),
(226, 23, 'What is REST?', 'Protocol', 'Architecture', 'Library', 'Language', 'B'),
(227, 23, 'How to send POST data?', 'data param', 'json param', 'body param', 'Both A and B', 'D'),
(228, 23, 'What is API key for?', 'Authentication', 'Identification', 'Both', 'None', 'C'),
(229, 23, 'What does timeout do?', 'Sets delay', 'Limits request time', 'Waits longer', 'Retries', 'B'),
(230, 23, 'How to handle rate limits?', 'Retry-After', 'Sleep', 'Queue', 'All of the above', 'D'),
(231, 24, 'What is first step in project?', 'Write code', 'Plan structure', 'Find API', 'Test', 'B'),
(232, 24, 'Where to store API key?', 'In code', 'Config file', 'Environment variable', 'Both B and C', 'D'),
(233, 24, 'How to handle API changes?', 'Try/except', 'Validation', 'Documentation', 'All of the above', 'D'),
(234, 24, 'What to include in docs?', 'Setup', 'Usage', 'Examples', 'All of the above', 'D'),
(235, 24, 'How to organize code?', 'Modules', 'Classes', 'Functions', 'All of the above', 'D'),
(236, 24, 'What to test for?', 'Valid input', 'Invalid input', 'Edge cases', 'All of the above', 'D'),
(237, 24, 'How to improve UX?', 'Clear prompts', 'Error messages', 'Help text', 'All of the above', 'D'),
(238, 24, 'What to version control?', 'Code', 'Docs', 'Tests', 'All of the above', 'D'),
(239, 24, 'How to share project?', 'GitHub', 'PyPI', 'Blog', 'All of the above', 'D'),
(240, 24, 'What next after project?', 'Refactor', 'Document', 'Share', 'All of the above', 'D'),
(361, 37, 'Which is NOT a C++ data type?', 'int', 'float', 'string', 'bool', 'C'),
(362, 37, 'What is the output operator?', '<<', '>>', '->', '::', 'A'),
(363, 37, 'How to start a single-line comment?', '//', '#', '/*', '--', 'A'),
(364, 37, 'What is the entry point function?', 'start()', 'begin()', 'main()', 'init()', 'C'),
(365, 37, 'Which header is for I/O?', '<input>', '<iostream>', '<io>', '<console>', 'B'),
(366, 37, 'What does namespace prevent?', 'Errors', 'Name conflicts', 'Memory leaks', 'Slow code', 'B'),
(367, 37, 'How to declare a constant?', 'constant', 'const', 'final', 'readonly', 'B'),
(368, 37, 'Which is NOT a valid variable name?', 'myVar', '_var', '2var', 'var2', 'C'),
(369, 37, 'What is the size of int typically?', '2 bytes', '4 bytes', '8 bytes', 'Depends on system', 'D'),
(370, 37, 'What does endl do?', 'Ends program', 'New line + flush', 'Space', 'End line', 'B'),
(371, 38, 'Which is correct if syntax?', 'if x == 5 then', 'if (x == 5)', 'if x == 5:', 'if (x == 5) {', 'D'),
(372, 38, 'What does break do?', 'Exits loop', 'Skips iteration', 'Restarts loop', 'Pauses loop', 'A'),
(373, 38, 'Which is NOT a loop?', 'for', 'while', 'do-while', 'repeat-until', 'D'),
(374, 38, 'What is the result of 5 > 3 && 2 < 4?', 'true', 'false', '1', '0', 'C'),
(375, 38, 'How to write \"not equal\"?', '!=', '<>', '~=', '!==', 'A'),
(376, 38, 'What does continue do?', 'Exits loop', 'Skips iteration', 'Restarts loop', 'Pauses loop', 'B'),
(377, 38, 'Which loop always runs once?', 'for', 'while', 'do-while', 'foreach', 'C'),
(378, 38, 'What is the ternary operator?', '?:', '??', '->', '::', 'A'),
(379, 38, 'How many parts in for loop?', '1', '2', '3', '4', 'C'),
(380, 38, 'What is short-circuit evaluation?', 'Fast execution', 'Stops when result known', 'Skips code', 'Optimization', 'B'),
(381, 39, 'What is a function prototype?', 'Definition', 'Declaration', 'Call', 'Body', 'B'),
(382, 39, 'How to pass by reference?', '& param', '* param', 'ref param', '-> param', 'A'),
(383, 39, 'What is function overloading?', 'Same name, different params', 'Different names', 'Same params', 'No return', 'A'),
(384, 39, 'What does void return?', '0', '1', 'Nothing', 'Null', 'C'),
(385, 39, 'Which is NOT a storage class?', 'auto', 'static', 'dynamic', 'register', 'C'),
(386, 39, 'What is recursion?', 'Function calls itself', 'Loop', 'Multiple functions', 'Parallel execution', 'A'),
(387, 39, 'How to set default argument?', 'param = value', 'default param', 'param := value', 'param -> value', 'A'),
(388, 39, 'What is scope?', 'Variable visibility', 'Function range', 'Code block', 'Program size', 'A'),
(389, 39, 'What is a lambda?', 'Anonymous function', 'Named function', 'Macro', 'Pointer', 'A'),
(390, 39, 'What is the call stack?', 'Function calls', 'Memory allocation', 'Data structure', 'Hardware', 'A'),
(391, 40, 'How to declare array?', 'int array[]', 'array int[]', 'int[] array', 'array[] int', 'A'),
(392, 40, 'What is index of first element?', '0', '1', '-1', 'first', 'A'),
(393, 40, 'Which is C++ string?', 'char[]', 'string class', 'char*', 'All of the above', 'B'),
(394, 40, 'How to get string length?', 'len()', 'length()', 'size()', 'Both B and C', 'D'),
(395, 40, 'What is out-of-bounds access?', 'Valid', 'Undefined behavior', 'Error', 'Returns 0', 'B'),
(396, 40, 'How to concatenate strings?', '+', 'concat()', 'append()', 'Both A and C', 'D'),
(397, 40, 'What is a null terminator?', '\0', 'NULL', 'nullptr', 'None', 'A'),
(398, 40, 'Which is NOT a string method?', 'substr()', 'find()', 'split()', 'replace()', 'C'),
(399, 40, 'What is a 2D array?', 'Array of arrays', 'Matrix', 'Table', 'All of the above', 'D'),
(400, 40, 'How to initialize all to 0?', 'int arr[5]', 'int arr[5] = {0}', 'int arr[5] = {}', 'All of the above', 'D'),
(401, 41, 'What is a pointer?', 'Variable', 'Memory address', 'Both', 'None', 'C'),
(402, 41, 'Which is address-of operator?', '*', '&', '->', '::', 'B'),
(403, 41, 'What is dereferencing?', 'Getting address', 'Getting value', 'Both', 'None', 'B'),
(404, 41, 'What is nullptr?', '0', 'NULL', 'Modern null pointer', 'All of the above', 'C'),
(405, 41, 'How to allocate memory?', 'malloc', 'new', 'alloc', 'create', 'B'),
(406, 41, 'What is pointer arithmetic?', 'Math on addresses', 'Math on values', 'Both', 'None', 'A'),
(407, 41, 'What is a memory leak?', 'Forgotten allocation', 'Deleted memory', 'Both', 'None', 'A'),
(408, 41, 'How to free memory?', 'free', 'delete', 'release', 'remove', 'B'),
(409, 41, 'What is a dangling pointer?', 'Null pointer', 'Pointer to freed memory', 'Uninitialized', 'Constant', 'B'),
(410, 41, 'What is the -> operator?', 'Dereference', 'Member access', 'Both', 'None', 'C'),
(411, 42, 'What is a class?', 'Blueprint', 'Object', 'Both', 'None', 'A'),
(412, 42, 'What is instantiation?', 'Creating object', 'Defining class', 'Both', 'None', 'A'),
(413, 42, 'Which is NOT an access specifier?', 'public', 'private', 'protected', 'internal', 'D'),
(414, 42, 'What is encapsulation?', 'Data hiding', 'Bundling data/functions', 'Both', 'None', 'C'),
(415, 42, 'What is constructor?', 'Initializes object', 'Destroys object', 'Both', 'None', 'A'),
(416, 42, 'What is the :: operator?', 'Scope resolution', 'Member access', 'Both', 'None', 'A'),
(417, 42, 'What is this?', 'Current object', 'Pointer to current', 'Both', 'None', 'B'),
(418, 42, 'What is a static member?', 'Class-level', 'Object-level', 'Both', 'None', 'A'),
(419, 42, 'What is a destructor?', '~ClassName', 'Cleanup', 'Both', 'None', 'C'),
(420, 42, 'What is a member function?', 'Class function', 'Method', 'Both', 'None', 'C'),
(491, 50, 'What are templates for?', 'Generic code', 'Specific types', 'Both', 'None', 'A'),
(492, 50, 'What keyword for templates?', 'template', 'generic', 'type', 'class', 'A'),
(493, 50, 'What is typename?', 'Template parameter', 'Type alias', 'Both', 'None', 'A'),
(494, 50, 'What is STL?', 'Standard Template Library', 'System Template', 'Standard Type', 'System Type', 'A'),
(495, 50, 'What is template specialization?', 'Specific version', 'Generic version', 'Both', 'None', 'A'),
(496, 50, 'What is auto?', 'Type deduction', 'Automatic variable', 'Both', 'None', 'C'),
(497, 50, 'What is a variadic template?', 'Variable arguments', 'Fixed arguments', 'Both', 'None', 'A'),
(498, 50, 'Which is NOT an STL container?', 'vector', 'array', 'list', 'tree', 'D'),
(499, 50, 'What is template instantiation?', 'Creating concrete', 'Defining template', 'Both', 'None', 'A'),
(500, 50, 'What is decltype?', 'Type deduction', 'Expression type', 'Both', 'None', 'C'),
(501, 51, 'Which header for files?', '<file>', '<fstream>', '<filestream>', '<io>', 'B'),
(502, 51, 'What is ofstream?', 'Output file', 'Input file', 'Both', 'None', 'A'),
(503, 51, 'How to open for append?', 'ios::app', 'ios::ate', 'ios::out', 'ios::in', 'A'),
(504, 51, 'What is binary mode?', 'ios::binary', 'ios::bin', 'binary', 'bin', 'A'),
(505, 51, 'How to check if open?', 'is_open()', 'good()', 'Both', 'None', 'C'),
(506, 51, 'What does tellg() do?', 'Get position', 'Set position', 'Both', 'None', 'A'),
(507, 51, 'What is serialization?', 'Object to bytes', 'Bytes to object', 'Both', 'None', 'C'),
(508, 51, 'How to read binary?', 'read()', 'get()', 'Both', 'None', 'A'),
(509, 51, 'What is seekp()?', 'Set write position', 'Set read position', 'Both', 'None', 'A'),
(510, 51, 'What is a file stream?', 'File interface', 'Stream to file', 'Both', 'None', 'C'),
(511, 52, 'What is first step?', 'Write code', 'Design classes', 'Test', 'Document', 'B'),
(512, 52, 'How to persist data?', 'Files', 'Database', 'Both', 'None', 'C'),
(513, 52, 'What to use for UI?', 'Console', 'GUI', 'Both', 'None', 'A'),
(514, 52, 'How to handle errors?', 'Exceptions', 'Error codes', 'Both', 'None', 'C'),
(515, 52, 'What OOP concepts to use?', 'Inheritance', 'Polymorphism', 'Both', 'None', 'C'),
(516, 52, 'How to organize code?', 'Headers', 'Source files', 'Both', 'None', 'C'),
(517, 52, 'What to include in docs?', 'Design', 'Usage', 'Both', 'None', 'C'),
(518, 52, 'How to test?', 'Unit tests', 'Manual', 'Both', 'None', 'C'),
(519, 52, 'What to version control?', 'Source', 'Docs', 'Both', 'None', 'C'),
(520, 52, 'What next after project?', 'Refactor', 'Extend', 'Both', 'None', 'C'),
(541, 73, 'What is the entry point of a Java program?', 'main() method', 'init() method', 'start() method', 'run() method', 'A'),
(542, 73, 'Which keyword defines a class?', 'class', 'struct', 'object', 'type', 'A'),
(543, 73, 'What is the correct file extension for Java source code?', '.java', '.class', '.jar', '.jvm', 'A'),
(544, 73, 'Which command compiles Java code?', 'java', 'javac', 'compile', 'jvm', 'B'),
(545, 73, 'What does JVM stand for?', 'Java Virtual Machine', 'Java Variable Manager', 'Java Verified Method', 'Java Visual Module', 'A'),
(546, 74, 'Which is NOT a primitive type?', 'int', 'String', 'boolean', 'double', 'B'),
(547, 74, 'What is the default value of an int?', '0', '1', 'null', 'undefined', 'A'),
(548, 74, 'Which is the correct way to declare a double?', 'double price = 19.99;', 'Double price = 19.99;', 'decimal price = 19.99;', 'float price = 19.99;', 'A'),
(549, 74, 'What is the size of a boolean?', '1 bit', '1 byte', '2 bytes', 'JVM dependent', 'A'),
(550, 74, 'Which is a valid variable name?', '2things', '_what', 'price$', 'Both B and C', 'D'),
(551, 75, 'Which is the correct if syntax?', 'if (x == 5) then', 'if x == 5:', 'if (x == 5) {', 'if x == 5 then', 'C'),
(552, 75, 'What does the switch statement evaluate?', 'boolean', 'int or String', 'any object', 'only integers', 'B'),
(553, 75, 'Which is NOT a logical operator?', '&&', '||', '!', '&', 'D'),
(554, 75, 'What is short-circuit evaluation?', 'Fast execution', 'Stops when result is known', 'Optimized bytecode', 'Parallel processing', 'B'),
(555, 75, 'Which operator has highest precedence?', '*', '&&', '==', '=', 'A'),
(556, 76, 'Which loop is guaranteed to run at least once?', 'for', 'while', 'do-while', 'foreach', 'C'),
(557, 76, 'What does break do?', 'Exits loop', 'Skips iteration', 'Restarts loop', 'Pauses loop', 'A'),
(558, 76, 'What does continue do?', 'Exits loop', 'Skips iteration', 'Restarts loop', 'Pauses loop', 'B'),
(559, 76, 'Which is the enhanced for loop?', 'for (int i : numbers)', 'for (i = 0; i < n; i++)', 'for i in numbers', 'foreach (int i in numbers)', 'A'),
(560, 76, 'How to create an infinite loop?', 'while (1)', 'for (;;)', 'while (true)', 'All of the above', 'D'),
(561, 73, 'What is the entry point of a Java program?', 'main() method', 'init() method', 'start() method', 'run() method', 'A'),
(562, 73, 'Which keyword defines a class?', 'class', 'struct', 'object', 'type', 'A'),
(563, 73, 'What is the correct file extension for Java source code?', '.java', '.class', '.jar', '.jvm', 'A'),
(564, 73, 'Which command compiles Java code?', 'java', 'javac', 'compile', 'jvm', 'B'),
(565, 73, 'What does JVM stand for?', 'Java Virtual Machine', 'Java Variable Manager', 'Java Verified Method', 'Java Visual Module', 'A'),
(566, 74, 'Which is NOT a primitive type?', 'int', 'String', 'boolean', 'double', 'B'),
(567, 74, 'What is the default value of an int?', '0', '1', 'null', 'undefined', 'A'),
(568, 74, 'Which is the correct way to declare a double?', 'double price = 19.99;', 'Double price = 19.99;', 'decimal price = 19.99;', 'float price = 19.99;', 'A'),
(569, 74, 'What is the size of a boolean?', '1 bit', '1 byte', '2 bytes', 'JVM dependent', 'A'),
(570, 74, 'Which is a valid variable name?', '2things', '_what', 'price$', 'Both B and C', 'D'),
(571, 75, 'Which is the correct if syntax?', 'if (x == 5) then', 'if x == 5:', 'if (x == 5) {', 'if x == 5 then', 'C'),
(572, 75, 'What does the switch statement evaluate?', 'boolean', 'int or String', 'any object', 'only integers', 'B'),
(573, 75, 'Which is NOT a logical operator?', '&&', '||', '!', '&', 'D'),
(574, 75, 'What is short-circuit evaluation?', 'Fast execution', 'Stops when result is known', 'Optimized bytecode', 'Parallel processing', 'B'),
(575, 75, 'Which operator has highest precedence?', '*', '&&', '==', '=', 'A'),
(576, 76, 'Which loop is guaranteed to run at least once?', 'for', 'while', 'do-while', 'foreach', 'C'),
(577, 76, 'What does break do?', 'Exits loop', 'Skips iteration', 'Restarts loop', 'Pauses loop', 'A'),
(578, 76, 'What does continue do?', 'Exits loop', 'Skips iteration', 'Restarts loop', 'Pauses loop', 'B'),
(579, 76, 'Which is the enhanced for loop?', 'for (int i : numbers)', 'for (i = 0; i < n; i++)', 'for i in numbers', 'foreach (int i in numbers)', 'A'),
(580, 76, 'How to create an infinite loop?', 'while (1)', 'for (;;)', 'while (true)', 'All of the above', 'D'),
(581, 97, 'Which tag defines the root of an HTML document?', '<html>', '<head>', '<body>', '<root>', 'A'),
(582, 97, 'Which element contains meta information?', '<meta>', '<head>', '<body>', 'Both A and B', 'D'),
(583, 97, 'Which tag creates a hyperlink?', '<a>', '<link>', '<href>', '<hyperlink>', 'A'),
(584, 97, 'Which is NOT a valid HTML5 element?', '<header>', '<footer>', '<sidebar>', '<article>', 'C'),
(585, 97, 'What does HTML stand for?', 'Hyperlinks and Text Markup Language', 'Home Tool Markup Language', 'Hyper Text Markup Language', 'Hyper Text Making Language', 'C'),
(586, 99, 'Which selector targets an element with ID \"main\"?', '#main', '.main', 'main', '*main', 'A'),
(587, 99, 'Which selector targets all <p> elements?', 'p', '#p', '.p', 'all.p', 'A'),
(588, 99, 'Which selects elements with class \"highlight\"?', 'highlight', '.highlight', '#highlight', '*highlight', 'B'),
(589, 99, 'Which selects a <div> with class \"container\"?', 'div.container', 'div#container', 'container div', 'div container', 'A'),
(590, 99, 'Which selects the first child of an element?', ':first-child', ':first', ':child(1)', ':first-of-type', 'A'),
(591, 101, 'How do you declare a variable in ES6?', 'var x', 'let x', 'const x', 'Both B and C', 'D'),
(592, 101, 'Which is NOT a JavaScript data type?', 'string', 'boolean', 'integer', 'symbol', 'C'),
(593, 101, 'What does === compare?', 'Value only', 'Value and type', 'Type only', 'Neither', 'B'),
(594, 101, 'Which loops through an array?', 'for', 'while', 'forEach', 'All of the above', 'D'),
(595, 101, 'What is the result of 2 + \"2\"?', '4', '22', 'NaN', 'Error', 'B'),
(596, 103, 'What does responsive design ensure?', 'Fast loading', 'Works on mobile', 'Looks good on all screens', 'Both B and C', 'D'),
(597, 103, 'What is a media query?', 'CSS for specific conditions', 'JavaScript listener', 'HTML attribute', 'Server-side check', 'A'),
(598, 103, 'What is mobile-first design?', 'Design for mobile then enhance', 'Mobile-only design', 'Responsive images', 'Touch-friendly UI', 'A'),
(599, 103, 'Which meta tag is crucial for mobile?', 'viewport', 'charset', 'description', 'author', 'A'),
(600, 103, 'What unit is best for responsive fonts?', 'px', 'em', 'rem', 'Both B and C', 'D'),
(601, 107, 'What is Node.js?', 'JavaScript runtime', 'Frontend framework', 'Database', 'Markup language', 'A'),
(602, 107, 'Which is NOT a core Node module?', 'fs', 'http', 'express', 'path', 'C'),
(603, 107, 'What does npm stand for?', 'Node Package Manager', 'Node Project Manager', 'Node Program Manager', 'Node Process Manager', 'A'),
(604, 107, 'How do you import a module in Node?', 'import', 'require', 'include', 'using', 'B'),
(605, 107, 'Which creates a basic server?', 'http.createServer()', 'express()', 'new Server()', 'server.listen()', 'A');

-- --------------------------------------------------------

--
-- Table structure for table `quiz_results`
--

CREATE TABLE `quiz_results` (
  `id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `score` decimal(5,2) NOT NULL,
  `taken_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quiz_scores`
--

CREATE TABLE `quiz_scores` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `score` decimal(5,2) NOT NULL,
  `taken_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quiz_scores`
--

INSERT INTO `quiz_scores` (`id`, `student_id`, `quiz_id`, `score`, `taken_at`) VALUES
(1, 27, 1, 40.00, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `student_progress`
--

CREATE TABLE `student_progress` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `completed` int(11) DEFAULT 0,
  `last_accessed` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_progress`
--

INSERT INTO `student_progress` (`id`, `student_id`, `course_id`, `completed`, `last_accessed`) VALUES
(23, 27, 1, 0, '2025-04-29 02:15:30'),
(24, 27, 4, 0, '2025-04-29 02:15:48'),
(25, 27, 3, 0, '2025-04-29 02:28:40'),
(26, 27, 2, 20, '2025-04-29 02:31:49'),
(29, 22, 4, 0, '2025-04-29 03:23:51'),
(30, 22, 3, 0, '2025-04-29 03:24:11'),
(31, 22, 1, 0, '2025-04-29 05:38:39'),
(32, 22, 2, 0, '2025-04-29 17:33:54'),
(33, 24, 1, 0, '2025-04-29 18:04:02'),
(34, 29, 1, 0, '2025-04-29 18:04:38'),
(35, 24, 2, 0, '2025-04-29 18:24:32'),
(36, 24, 3, 0, '2025-04-29 18:50:53'),
(37, 24, 4, 0, '2025-04-29 18:51:05');

-- --------------------------------------------------------

--
-- Table structure for table `submissions`
--

CREATE TABLE `submissions` (
  `id` int(11) NOT NULL,
  `assignment_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `submission` text DEFAULT NULL,
  `grade` decimal(5,2) DEFAULT NULL,
  `graded_at` timestamp NULL DEFAULT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `teacher_applications`
--

CREATE TABLE `teacher_applications` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `resume_path` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `applied_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teacher_applications`
--

INSERT INTO `teacher_applications` (`id`, `full_name`, `email`, `phone`, `resume_path`, `status`, `applied_at`) VALUES
(1, 'Suyosha Acharya', 'suyoshaacharya123@gmail.com', '9407584125', 'uploads/resumes/1744507326_CV IDYLLO.pdf', 'pending', '2025-04-13 01:22:06'),
(2, 'Suyosha Acharya', 'suyoshaacharya123@gmail.com', '9840330816', 'uploads/resumes/1744507710_2025Resume.pdf', 'pending', '2025-04-13 01:28:30'),
(3, 'Suyosha Acharya', 'suyoshaacharya123@gmail.com', '9840330816', 'uploads/resumes/1744605748_HW5(Data Mining).pdf', 'pending', '2025-04-14 04:42:28'),
(4, 'Suyosha Acharya', 'suyoshaacharya123@gmail.com', '9840330816', 'uploads/resumes/1744648695_test.py', 'pending', '2025-04-14 16:38:15'),
(5, 'trick', 'trick@treat.com', '9409772999', 'uploads/resumes/1745876394_welcome.php', 'pending', '2025-04-28 21:39:55');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('student','teacher','admin') DEFAULT 'student',
  `teacher_code` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `teacher_code`) VALUES
(21, '', 'fallon@codemania.com', '$2y$10$b6QDJ2wzYfwDrH0aJE8vLORHF.SOzyAgmkPlCB50OitBbw1TV329O', 'teacher', 'teacher8'),
(22, '', 'suyu@code.com', '$2y$10$/Cxq9OfhMLJ78UXgSpVxrOOt69UebRF5QXRXqf0tlJPFcv3wacs8i', 'student', NULL),
(23, '', 'liam@codemania.com', '$2y$10$dagWdXzgcEsGb2bRtwNg6OrCuBr.jix9lq.ApS49kTiBm/RC75OaW', 'teacher', 'teacher9'),
(24, '', 'suyosha@codemania.com', '$2y$10$1W4lk8bAFEQJIwgrbyUBk.v9xXVFxY3hFLUlnpjLfQWVKfpPwBHju', 'teacher', 'teacher6'),
(27, '', 'david@codemania.com', '$2y$10$J8izhShajx3Bk5En3sRd5OakE8pwjZer3mAigLrVFnm4jcpECADv2', 'teacher', 'teacher7'),
(28, '', 'trick@treat.com', '$2y$10$xv8w2ESqmCbZLtlpWtBG7uijt5tCN9JA9PywdCodPRwxTOeWoBzrC', 'student', NULL),
(29, '', 'tim@tom.com', '$2y$10$dnlb8BLVFuVm1k.t.yPBNOywSBef5utpz91fUDpLsT6bLhvzuAIDm', 'student', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assignments`
--
ALTER TABLE `assignments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_course` (`course_id`);

--
-- Indexes for table `badges`
--
ALTER TABLE `badges`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `capstone_projects`
--
ALTER TABLE `capstone_projects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_student` (`student_id`);

--
-- Indexes for table `certificates`
--
ALTER TABLE `certificates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_cert_student` (`student_id`),
  ADD KEY `fk_cert_course` (`course_id`);

--
-- Indexes for table `chapters`
--
ALTER TABLE `chapters`
  ADD PRIMARY KEY (`id`),
  ADD KEY `module_id` (`module_id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `course_name` (`course_name`);

--
-- Indexes for table `lessons`
--
ALTER TABLE `lessons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `chapter_id` (`chapter_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `modules`
--
ALTER TABLE `modules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `peer_posts`
--
ALTER TABLE `peer_posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `peer_reviews`
--
ALTER TABLE `peer_reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `reviewer_id` (`reviewer_id`);

--
-- Indexes for table `quizzes`
--
ALTER TABLE `quizzes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `chapter_id` (`chapter_id`);

--
-- Indexes for table `quiz_questions`
--
ALTER TABLE `quiz_questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quiz_id` (`quiz_id`);

--
-- Indexes for table `quiz_results`
--
ALTER TABLE `quiz_results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quiz_id` (`quiz_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `quiz_scores`
--
ALTER TABLE `quiz_scores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_id` (`student_id`,`quiz_id`),
  ADD KEY `quiz_id` (`quiz_id`);

--
-- Indexes for table `student_progress`
--
ALTER TABLE `student_progress`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `submissions`
--
ALTER TABLE `submissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `assignment_id` (`assignment_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `teacher_applications`
--
ALTER TABLE `teacher_applications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assignments`
--
ALTER TABLE `assignments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `badges`
--
ALTER TABLE `badges`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `capstone_projects`
--
ALTER TABLE `capstone_projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `certificates`
--
ALTER TABLE `certificates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `chapters`
--
ALTER TABLE `chapters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=137;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `lessons`
--
ALTER TABLE `lessons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=215;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `modules`
--
ALTER TABLE `modules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;

--
-- AUTO_INCREMENT for table `peer_posts`
--
ALTER TABLE `peer_posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `peer_reviews`
--
ALTER TABLE `peer_reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `quizzes`
--
ALTER TABLE `quizzes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=133;

--
-- AUTO_INCREMENT for table `quiz_questions`
--
ALTER TABLE `quiz_questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=606;

--
-- AUTO_INCREMENT for table `quiz_results`
--
ALTER TABLE `quiz_results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quiz_scores`
--
ALTER TABLE `quiz_scores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `student_progress`
--
ALTER TABLE `student_progress`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `submissions`
--
ALTER TABLE `submissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `teacher_applications`
--
ALTER TABLE `teacher_applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `assignments`
--
ALTER TABLE `assignments`
  ADD CONSTRAINT `assignments_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_course` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `capstone_projects`
--
ALTER TABLE `capstone_projects`
  ADD CONSTRAINT `capstone_projects_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_student` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `certificates`
--
ALTER TABLE `certificates`
  ADD CONSTRAINT `certificates_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `certificates_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_cert_course` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_cert_student` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `chapters`
--
ALTER TABLE `chapters`
  ADD CONSTRAINT `chapters_ibfk_1` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `lessons`
--
ALTER TABLE `lessons`
  ADD CONSTRAINT `lessons_ibfk_1` FOREIGN KEY (`chapter_id`) REFERENCES `chapters` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `modules`
--
ALTER TABLE `modules`
  ADD CONSTRAINT `modules_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `peer_posts`
--
ALTER TABLE `peer_posts`
  ADD CONSTRAINT `peer_posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `peer_reviews`
--
ALTER TABLE `peer_reviews`
  ADD CONSTRAINT `peer_reviews_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `peer_reviews_ibfk_2` FOREIGN KEY (`reviewer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `quizzes`
--
ALTER TABLE `quizzes`
  ADD CONSTRAINT `quizzes_ibfk_1` FOREIGN KEY (`chapter_id`) REFERENCES `chapters` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `quiz_questions`
--
ALTER TABLE `quiz_questions`
  ADD CONSTRAINT `quiz_questions_ibfk_1` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `quiz_results`
--
ALTER TABLE `quiz_results`
  ADD CONSTRAINT `quiz_results_ibfk_1` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `quiz_results_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `quiz_scores`
--
ALTER TABLE `quiz_scores`
  ADD CONSTRAINT `quiz_scores_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `quiz_scores_ibfk_2` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`);

--
-- Constraints for table `student_progress`
--
ALTER TABLE `student_progress`
  ADD CONSTRAINT `student_progress_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_progress_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `submissions`
--
ALTER TABLE `submissions`
  ADD CONSTRAINT `submissions_ibfk_1` FOREIGN KEY (`assignment_id`) REFERENCES `assignments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `submissions_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
