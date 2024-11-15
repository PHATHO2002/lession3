<?php
class Person
{
    private $firstName;
    private $lastName;
    private $dateOfBirth;
    private $address;
    public function __construct($firstName, $lastName, $dateOfBirth, $address)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->dateOfBirth = $dateOfBirth;
        $this->address = $address;
    }
    public function getFirstName()
    {
        return $this->firstName;
    }
    public function getLastName()
    {
        return $this->lastName;
    }
    public function getAddress()
    {
        return $this->address;
    }
    public function getDateOfBirth()
    {
        return $this->dateOfBirth;
    }
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }
    public function setDateOfBirth($dateOfBirth)
    {
        $this->dateOfBirth = $dateOfBirth;
    }
    public function setAddress($address)
    {
        $this->address = $address;
    }
}
class Employee  extends Person
{
    protected $jobPosition;
    protected $salary;
    public function __construct($firstName, $lastName, $dateOfBirth, $address, $jobPosition, $salary)
    {
        parent::__construct($firstName, $lastName, $dateOfBirth, $address);
        $this->jobPosition = $jobPosition;
        $this->salary = $salary;
    }

    public function getJobPosition()
    {
        return $this->jobPosition;
    }
    public function getSalary()
    {
        return $this->salary;
    }
    public function __toString()
    {
        return "Nhân viên: " . $this->getFirstName() . " " . $this->getLastName() . "<br>";
    }
}
class EmployeeManager
{
    public  $Employes = [];

    public function toArray($employe)
    {
        return [
            'firstName' => $employe->getFirstName(),
            'lastName' => $employe->getLastName(),
            'dateOfBirth' => $employe->getDateOfBirth(),
            'address' => $employe->getAddress(),
            'jobPosition' => $employe->getJobPosition(),
            'salary' => $employe->getSalary()
        ];
    }
    public function fromArray($arr)
    {
        return new Employee($arr["firstName"], $arr["lastName"], $arr["dateOfBirth"], $arr["address"], $arr["jobPosition"], $arr["salary"]);
    }
    public function loadFromFile()
    {
        $listEmloyeeJs = file_get_contents("nhansu.json");
        $this->Employes = json_decode($listEmloyeeJs, true);
    }
    public function addEmployee($employe)
    {
        $this->loadFromFile();
        $this->Employes[] = $this->toArray($employe);
        $this->saveToFile($this->Employes);
    }
    public function saveToFile($listEmploye)
    {
        file_put_contents("nhansu.json", json_encode($listEmploye));
    }

    public function displayEmployeeList()
    {
        $this->loadFromFile();

        if (empty($this->Employes)) {
            echo "danh sách nhân sự trống";
        } else {

            foreach ($this->Employes as $employe) {

                $employeObj = $this->fromArray($employe);
                echo $employeObj;
            }
        }
    }
    public function getEmployeeDetails()
    {
        $this->loadFromFile();

        if (empty($this->Employes)) {
            echo "danh sách nhân sự trống";
        } else {
            foreach ($this->Employes as $employeArr) {
                $employeObj = $this->fromArray($employeArr);
                printf(
                    "first name: %s, last name: %s, ngày sinh: %s , địa chỉ : %s , ví trị : %s , lương : %s<br>",
                    $employeObj->getFirstName(),
                    $employeObj->getLastName(),
                    $employeObj->getDateOfBirth(),
                    $employeObj->getAddress(),
                    $employeObj->getJobPosition(),
                    $employeObj->getSalary(),
                );
            }
        }
    }
}

$nhansu = new EmployeeManager();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <form action="" method="POST">
        <div class="">
            <label for="name">first name</label>
            <input type="text" name="firstName">
        </div>
        <div class="">
            <label for="email">last name</label>
            <input type="text" name="lastName">
        </div>
        <div class="">
            <label for="phone">ngay sinh</label>
            <input type="date" name="dateOfBirth">
        </div>
        <div class="">
            <label for="email">địa chỉ</label>
            <input type="text" name="address">
        </div>
        <div class="">
            <label for="email">vị trí</label>
            <input type="text" name="jobPosition">
        </div>
        <div class="">
            <label for="email">lương</label>
            <input type="number" name="salary">
        </div>
        <input type="submit" name="action" value="add">
        <input type="submit" name="action" value="display">
        <input type="submit" name="action" value="getEmployeeDetails">


    </form>
    <?php
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        switch ($_POST["action"]) {
            case "add":
                if (empty(trim($_POST["firstName"])) || empty(trim($_POST["lastName"])) || empty(trim($_POST["dateOfBirth"])) || empty(trim($_POST["address"])) || empty(trim($_POST["jobPosition"])) || empty(trim($_POST["salary"]))) {
                    echo "<p>Du liệu không được để trống</p>";
                } else {

                    $employee = new Employee(
                        trim($_POST["firstName"]),
                        trim($_POST["lastName"]),
                        trim($_POST["dateOfBirth"]),
                        trim($_POST["address"]),
                        trim($_POST["jobPosition"]),
                        trim($_POST["salary"])
                    );

                    // Thêm nhân viên vào danh sách
                    $nhansu->addEmployee($employee);
                    echo "add thành công";
                }
                break;
            case "display":
                $nhansu->displayEmployeeList();
                break;
            case "getEmployeeDetails":
                $nhansu->getEmployeeDetails();
                break;
        };
    }

    ?>
</body>

</html>