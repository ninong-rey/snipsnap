<?php

class B {
    public $name;
    public $age;
    public $gender;

    public function __construct($name, $age, $gender) {
        $this->name = $name;
        $this->age = $age;
        $this->gender = $gender;
    }

    public function displayB() {
        echo "<b>Class B</b><br>";
        echo "Name: $this->name<br>";
        echo "Age: $this->age<br>";
        echo "Gender: $this->gender<br><br>";
    }

    public function greetB() {
        echo "Hello from Class B!<br>";
    }

    public function workB() {
        echo "Class B is working...<br><br>";
    }
}


class A extends B {
    public $address;
    public $course;
    public $school;

    public function __construct($name, $age, $gender, $address, $course, $school) {
        parent::__construct($name, $age, $gender);
        $this->address = $address;
        $this->course = $course;
        $this->school = $school;
    }

    public function displayA() {
        echo "<b>Class A</b><br>";
        echo "Address: $this->address<br>";
        echo "Course: $this->course<br>";
        echo "School: $this->school<br><br>";
    }

    public function greetA() {
        echo "Welcome from Class A!<br>";
    }

    public function studyA() {
        echo "Class A is studying...<br><br>";
    }
}


class C extends A {
    public $job;
    public $company;
    public $skills;

    public function __construct($name, $age, $gender, $address, $course, $school, $job, $company, $skills) {
        parent::__construct($name, $age, $gender, $address, $course, $school);
        $this->job = $job;
        $this->company = $company;
        $this->skills = $skills;
    }

    public function displayC() {
        echo "<b>Class C</b><br>";
        echo "Job: $this->job<br>";
        echo "Company: $this->company<br>";
        echo "Skills: $this->skills<br><br>";
    }

    public function greetC() {
        echo "Greetings from Class C!<br>";
    }

    public function workC() {
        echo "Class C is developing a project...<br><br>";
    }
}


$obj = new C(
    "Rey", 23, "Male",
    "Loreto, Agusan del Sur", "BSIT", "Mindanao Polytechnic College",
    "Web Developer", "Freelance", "PHP, HTML, CSS, MySQL"
);


$obj->displayB();
$obj->greetB();
$obj->workB();

$obj->displayA();
$obj->greetA();
$obj->studyA();

$obj->displayC();
$obj->greetC();
$obj->workC();
?>
