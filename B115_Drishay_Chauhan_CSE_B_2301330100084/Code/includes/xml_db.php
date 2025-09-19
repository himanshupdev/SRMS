<?php
class XMLDatabase {
    private $studentsFile;
    private $usersFile;
    
    public function __construct() {
        $this->studentsFile = __DIR__ . '/../data/students.xml';
        $this->usersFile = __DIR__ . '/../data/users.xml';
        $this->initializeDatabase();
    }
    
    private function initializeDatabase() {
        // Create data directory if it doesn't exist
        $dataDir = dirname($this->studentsFile);
        if (!is_dir($dataDir)) {
            mkdir($dataDir, 0755, true);
        }
        
        // Initialize students.xml if it doesn't exist
        if (!file_exists($this->studentsFile)) {
            $xml = new DOMDocument('1.0', 'UTF-8');
            $xml->formatOutput = true;
            $root = $xml->createElement('students');
            $xml->appendChild($root);
            $xml->save($this->studentsFile);
        }
        
        // Initialize users.xml if it doesn't exist
        if (!file_exists($this->usersFile)) {
            $xml = new DOMDocument('1.0', 'UTF-8');
            $xml->formatOutput = true;
            $root = $xml->createElement('users');
            
            // Create default admin user
            $admin = $xml->createElement('user');
            $admin->appendChild($xml->createElement('id', '1'));
            $admin->appendChild($xml->createElement('username', 'admin'));
            $admin->appendChild($xml->createElement('password', password_hash('admin123', PASSWORD_DEFAULT)));
            $admin->appendChild($xml->createElement('role', 'admin'));
            $root->appendChild($admin);
            
            $xml->appendChild($root);
            $xml->save($this->usersFile);
        }
    }
    
    // User Authentication Methods
    public function authenticateUser($username, $password) {
        $xml = new DOMDocument();
        $xml->load($this->usersFile);
        $users = $xml->getElementsByTagName('user');
        
        foreach ($users as $user) {
            $userUsername = $user->getElementsByTagName('username')->item(0)->nodeValue;
            $userPassword = $user->getElementsByTagName('password')->item(0)->nodeValue;
            
            if ($userUsername === $username && password_verify($password, $userPassword)) {
                return [
                    'id' => $user->getElementsByTagName('id')->item(0)->nodeValue,
                    'username' => $userUsername,
                    'role' => $user->getElementsByTagName('role')->item(0)->nodeValue
                ];
            }
        }
        return false;
    }
    
    public function createUser($username, $password, $role = 'student') {
        $xml = new DOMDocument();
        $xml->load($this->usersFile);
        
        // Check if username already exists
        $users = $xml->getElementsByTagName('user');
        foreach ($users as $user) {
            if ($user->getElementsByTagName('username')->item(0)->nodeValue === $username) {
                return false;
            }
        }
        
        // Generate new ID
        $maxId = 0;
        foreach ($users as $user) {
            $id = (int)$user->getElementsByTagName('id')->item(0)->nodeValue;
            if ($id > $maxId) $maxId = $id;
        }
        
        // Create new user element
        $newUser = $xml->createElement('user');
        $newUser->appendChild($xml->createElement('id', $maxId + 1));
        $newUser->appendChild($xml->createElement('username', htmlspecialchars($username)));
        $newUser->appendChild($xml->createElement('password', password_hash($password, PASSWORD_DEFAULT)));
        $newUser->appendChild($xml->createElement('role', $role));
        
        $xml->documentElement->appendChild($newUser);
        return $xml->save($this->usersFile);
    }
    
    // Student Management Methods
    public function getAllStudents() {
        $xml = new DOMDocument();
        $xml->load($this->studentsFile);
        $students = [];
        
        $studentNodes = $xml->getElementsByTagName('student');
        foreach ($studentNodes as $student) {
            $students[] = $this->nodeToArray($student);
        }
        
        return $students;
    }
    
    public function getStudentById($id) {
        $xml = new DOMDocument();
        $xml->load($this->studentsFile);
        
        $students = $xml->getElementsByTagName('student');
        foreach ($students as $student) {
            if ($student->getElementsByTagName('id')->item(0)->nodeValue == $id) {
                return $this->nodeToArray($student);
            }
        }
        return null;
    }
    
    public function getStudentByRollNo($roll_no) {
        $xml = new DOMDocument();
        $xml->load($this->studentsFile);
        
        $students = $xml->getElementsByTagName('student');
        foreach ($students as $student) {
            if ($student->getElementsByTagName('roll_no')->item(0)->nodeValue == $roll_no) {
                return $this->nodeToArray($student);
            }
        }
        return null;
    }
    
    public function addStudent($data) {
        $xml = new DOMDocument();
        $xml->load($this->studentsFile);
        
        // Generate new ID
        $maxId = 0;
        $students = $xml->getElementsByTagName('student');
        foreach ($students as $student) {
            $id = (int)$student->getElementsByTagName('id')->item(0)->nodeValue;
            if ($id > $maxId) $maxId = $id;
        }
        
        // Create new student element
        $newStudent = $xml->createElement('student');
        $newStudent->appendChild($xml->createElement('id', $maxId + 1));
        $newStudent->appendChild($xml->createElement('name', htmlspecialchars($data['name'])));
        $newStudent->appendChild($xml->createElement('roll_no', htmlspecialchars($data['roll_no'])));
        $newStudent->appendChild($xml->createElement('ml', $data['ml']));
        $newStudent->appendChild($xml->createElement('sed', $data['sed']));
        $newStudent->appendChild($xml->createElement('dt2', $data['dt2']));
        $newStudent->appendChild($xml->createElement('wt', $data['wt']));
        $newStudent->appendChild($xml->createElement('elective1', $data['elective1']));
        $newStudent->appendChild($xml->createElement('elective2', $data['elective2']));
        
        $xml->documentElement->appendChild($newStudent);
        return $xml->save($this->studentsFile);
    }
    
    public function updateStudent($id, $data) {
        $xml = new DOMDocument();
        $xml->load($this->studentsFile);
        
        $students = $xml->getElementsByTagName('student');
        foreach ($students as $student) {
            if ($student->getElementsByTagName('id')->item(0)->nodeValue == $id) {
                $student->getElementsByTagName('name')->item(0)->nodeValue = htmlspecialchars($data['name']);
                $student->getElementsByTagName('roll_no')->item(0)->nodeValue = htmlspecialchars($data['roll_no']);
                $student->getElementsByTagName('ml')->item(0)->nodeValue = $data['ml'];
                $student->getElementsByTagName('sed')->item(0)->nodeValue = $data['sed'];
                $student->getElementsByTagName('dt2')->item(0)->nodeValue = $data['dt2'];
                $student->getElementsByTagName('wt')->item(0)->nodeValue = $data['wt'];
                $student->getElementsByTagName('elective1')->item(0)->nodeValue = $data['elective1'];
                $student->getElementsByTagName('elective2')->item(0)->nodeValue = $data['elective2'];
                
                return $xml->save($this->studentsFile);
            }
        }
        return false;
    }
    
    public function deleteStudent($id) {
        $xml = new DOMDocument();
        $xml->load($this->studentsFile);
        
        $students = $xml->getElementsByTagName('student');
        foreach ($students as $student) {
            if ($student->getElementsByTagName('id')->item(0)->nodeValue == $id) {
                $xml->documentElement->removeChild($student);
                return $xml->save($this->studentsFile);
            }
        }
        return false;
    }
    
    private function nodeToArray($node) {
        $array = [];
        foreach ($node->childNodes as $child) {
            if ($child->nodeType === XML_ELEMENT_NODE) {
                $array[$child->nodeName] = $child->nodeValue;
            }
        }
        return $array;
    }
}

// Create global instance
$db = new XMLDatabase();
?>