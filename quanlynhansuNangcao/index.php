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
    private $team;
    public function __construct($firstName, $lastName, $dateOfBirth, $address, $jobPosition, $salary, $team = null)
    {
        parent::__construct($firstName, $lastName, $dateOfBirth, $address);
        $this->jobPosition = $jobPosition;
        $this->salary = $salary;
        $this->team = $team;
    }

    public function getJobPosition()
    {
        return $this->jobPosition;
    }
    public function getSalary()
    {
        return $this->salary;
    }
    public function getTeam()
    {
        return $this->team;
    }

    public function __toString()
    {
        return "Nhân viên: " . $this->getFirstName() . " " . $this->getLastName();
    }
}


class Manager extends Employee
{
    public $teams = [];
    public function __construct($firstName = null, $lastName = null, $dateOfBirth = null, $address = null, $jobPosition = null, $salary = null, $team = null)
    {
        parent::__construct($firstName, $lastName, $dateOfBirth, $address, $jobPosition, $salary, $team);
    }
    public function addTeamMember($employe)
    {
        $employeeJs = file_get_contents("nhansu.json");
        $employees = json_decode($employeeJs, true);
        $employees[] = $this->toArrayTeam($employe);
        file_put_contents("nhansu.json", json_encode($employees));
    }
    public function removeTeamMember($team, $lastName)
    {

        $employeeJs = file_get_contents("nhansu.json");
        $employees = json_decode($employeeJs, true);


        $updatedEmployees = array_filter($employees, function ($employee) use ($team, $lastName) {
            return !(isset($employee["lastName"]) && $employee["lastName"] === $lastName && isset($employee["team"]) && $employee["team"] === $team);
        });

        file_put_contents("nhansu.json", json_encode($updatedEmployees));

        echo "Đã xóa thành viên có họ là '$lastName' khỏi team '$team'.<br>";
    }
    public function displayTeam()
    {
        $employeeJs = file_get_contents("nhansu.json");
        $employees = json_decode($employeeJs, true);
        foreach ($employees as $employee) {
            if (!empty($employee["team"])) {
                echo $this->fromArrayTeam($employee) . ' team :' . $employee["team"] . "<br>";
            }
        }
    }
    public function toArrayTeam($employe)
    {
        return [
            'firstName' => $employe->getFirstName(),
            'lastName' => $employe->getLastName(),
            'dateOfBirth' => $employe->getDateOfBirth(),
            'address' => $employe->getAddress(),
            'jobPosition' => $employe->getJobPosition(),
            'salary' => $employe->getSalary(),
            'team' => $employe->getTeam(),

        ];
    }
    public function fromArrayTeam($arr)
    {
        return new Employee($arr["firstName"], $arr["lastName"], $arr["dateOfBirth"], $arr["address"], $arr["jobPosition"], $arr["salary"], $arr["team"]);
    }
}
class Contractor extends Person
{
    public $contractPeriod;
    private $hourlyRate;

    public function __construct($firstName = null, $lastName = null, $dateOfBirth = null, $address = null, $contractPeriod = null, $hourlyRate = null,)
    {
        parent::__construct($firstName, $lastName, $dateOfBirth, $address);
        $this->contractPeriod = $contractPeriod;
        $this->hourlyRate = $hourlyRate;
    }
    public function getHourlyRate()
    {
        return $this->hourlyRate;
    }

    public function setHourlyRate($hourlyRate)
    {
        $this->hourlyRate = $hourlyRate;
    }
    public function getContractPeriod()
    {
        return $this->contractPeriod;
    }

    public function setContractPeriod($contractPeriod)
    {
        $this->contractPeriod = $contractPeriod;
    }
    public function __toString()
    {
        return "Contractor: " . $this->getFirstName() . " " . $this->getLastName();
    }
}
class EmployeeManager extends Manager
{
    public  $Employes = [];

    public function addEmployee(
        $firstName,
        $lastName,
        $dateOfBirth,
        $address,
        $jobPosition,
        $salary,
        $team,
        $contractPeriod,
        $hourlyRate
    ) {

        if ($team) {
            $newEmployee = new Employee(
                $firstName,
                $lastName,
                $dateOfBirth,
                $address,
                $jobPosition,
                $salary,
                $team
            );
            $this->addTeamMember($newEmployee);
        } else if ($contractPeriod &&  $hourlyRate) {
            $newEmployee = new Contractor(
                $firstName,
                $lastName,
                $dateOfBirth,
                $address,
                $contractPeriod,
                $hourlyRate

            );
            $employeeJs = file_get_contents("nhansu.json");
            $employees = json_decode($employeeJs, true);
            $employees[] = $this->toArray($newEmployee);
            file_put_contents("nhansu.json", json_encode($employees));
        } else {
            $newEmployee = new Employee(
                $firstName,
                $lastName,
                $dateOfBirth,
                $address,
                $jobPosition,
                $salary,

            );

            $this->loadFromFile();
            $this->Employes[] = $newEmployee;

            $this->saveToFile($this->Employes);
        }
    }
    public function toArray($employe)
    {

        $team = method_exists($employe, 'getTeam');
        $contractor = method_exists($employe, 'getHourlyRate');

        if ($team) {
            return $this->toArrayTeam($employe);
        } else if ($contractor) {
            return [
                'firstName' => $employe->getFirstName(),
                'lastName' => $employe->getLastName(),
                'dateOfBirth' => $employe->getDateOfBirth(),
                'address' => $employe->getAddress(),
                'contractPeriod' => $employe->getContractPeriod(),
                'hourlyRate' => $employe->getHourlyRate()
            ];
        } else {
            return [
                'firstName' => $employe->getFirstName(),
                'lastName' => $employe->getLastName(),
                'dateOfBirth' => $employe->getDateOfBirth(),
                'address' => $employe->getAddress(),
                'jobPosition' => $employe->getJobPosition(),
                'salary' => $employe->getSalary()
            ];
        }
    }
    public function fromArray($arr)
    {

        if (isset($arr['team'])) {
            return   $this->fromArrayTeam($arr);
        } else if (
            isset($arr['contractPeriod'])
        ) {
            return new Contractor(
                $arr["firstName"],
                $arr["lastName"],
                $arr["dateOfBirth"],
                $arr["address"],
                $arr["contractPeriod"],
                $arr["hourlyRate"]
            );
        } else {
            return new Employee(
                $arr["firstName"],
                $arr["lastName"],
                $arr["dateOfBirth"],
                $arr["address"],
                $arr["jobPosition"],
                $arr["salary"]
            );
        }
    }
    public function loadFromFile()
    {
        $listEmloyeeJs = file_get_contents("nhansu.json");
        $Employes = json_decode($listEmloyeeJs, true);
        foreach ($Employes as $employe) {
            $this->Employes[] = $this->fromArray($employe);
        }
    }
    public function saveToFile($listEmploye)
    {

        $listEmployeArr = [];
        foreach ($listEmploye as $employye) {
            $listEmployeArr[] = $this->toArray($employye);
        }
        file_put_contents("nhansu.json", json_encode($listEmployeArr));
    }
    public function displayEmployeeList()
    {
        $this->loadFromFile();

        if (empty($this->Employes)) {
            echo "danh sách nhân sự trống";
        } else {

            foreach ($this->Employes as $employe) {

                echo $employe . "<br>";
            }
        }
    }
    public function getEmployeeDetails()
    {
        $this->loadFromFile();

        if (empty($this->Employes)) {
            echo "danh sách nhân sự trống";
        } else {
            foreach ($this->Employes as $employeObj) {

                printf(
                    "first name: %s, last name: %s, ngày sinh: %s , địa chỉ : %s ",
                    $employeObj->getFirstName(),
                    $employeObj->getLastName(),
                    $employeObj->getDateOfBirth(),
                    $employeObj->getAddress()
                );

                if (method_exists($employeObj, 'getJobPosition')) {
                    printf(", vị trí: %s", $employeObj->getJobPosition());
                }

                if (method_exists($employeObj, 'getSalary')) {
                    printf(", lương: %s", $employeObj->getSalary());
                }

                if (method_exists($employeObj, 'getTeam')) {
                    printf(", team: %s", $employeObj->getTeam());
                }

                if (method_exists($employeObj, 'getContractPeriod')) {
                    printf(", contractPeriod: %s", $employeObj->getContractPeriod());
                }

                if (method_exists($employeObj, 'getHourlyRate')) {
                    printf(", hourlyRate: %s", $employeObj->getHourlyRate());
                }

                echo "<br>";
            }
        }
    }
    public  function displayTeamMember()
    {
        $this->displayTeam();
    }
}
$quanly = new EmployeeManager();
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
        <div class="">
            <label for="email">Team</label>
            <input type="number" name="team">
        </div>
        <div class="">
            <label for="email">contractPeriod</label>
            <input type="text" name="contractPeriod">
        </div>
        <div class="">
            <label for="email">hourlyRate</label>
            <input type="number" name="hourlyRate">
        </div>


        <div class="">
            <input type="submit" name="action" value="add">
            <input type="submit" name="action" value="display">
            <input type="submit" name="action" value="getEmployeeDetails">
            <input type="submit" name="action" value="displayTeam">

        </div>
        <input type="text" placeholder="team cần xóa mem " name="teamDelte">
        <input type="text" placeholder=" last name of mem cần xóa " name="nameDelete">
        <input type="submit" name="action" value="removeInTeam">




    </form>
    <?php
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $nhansu = new EmployeeManager();

        switch ($_POST["action"]) {
            case "add":
                if (
                    empty(trim($_POST["firstName"])) ||
                    empty(trim($_POST["lastName"])) ||
                    empty(trim($_POST["dateOfBirth"])) ||
                    empty(trim($_POST["address"]))
                ) {
                    echo "<p>Du liệu không được để trống</p>";
                } else {
                    $firstName =   trim($_POST["firstName"]);
                    $lastName = trim($_POST["lastName"]);
                    $dateOfBirth = trim($_POST["dateOfBirth"]);
                    $address = trim($_POST["address"]);
                    $jobPosition = trim($_POST["jobPosition"]);
                    $salary = trim($_POST["salary"]);
                    $team = trim($_POST["team"]);
                    $contractPeriod = trim($_POST["contractPeriod"]);
                    $hourlyRate = trim($_POST["hourlyRate"]);
                    if ($team) {

                        $quanly->addEmployee($firstName, $lastName, $dateOfBirth, $address, $jobPosition, $salary, $team, null, null);
                        echo "add team thanh cong";
                    } else if ($contractPeriod) {

                        $quanly->addEmployee($firstName, $lastName, $dateOfBirth, $address, null, null, null, $contractPeriod, $hourlyRate);
                        echo "add contractor thanh cong";
                    } else {
                        $quanly->addEmployee($firstName, $lastName, $dateOfBirth, $address, $jobPosition, $salary, null, null, null);
                        echo "add employee thanh cong";
                    }
                }
                break;
            case "display":
                $nhansu->displayEmployeeList();

                break;
            case "getEmployeeDetails":
                $nhansu->getEmployeeDetails();
                break;
            case "displayTeam":
                $nhansu->displayTeamMember();
                break;
            case "removeInTeam":
                $teamDelte = trim($_POST["teamDelte"]);
                $nameDelete = trim($_POST["nameDelete"]);


                if (empty($teamDelte) || empty($nameDelete)) {
                    echo "<p>Vui lòng nhập đầy đủ team và last name để xóa thành viên.</p>";
                } else {
                    $nhansu->removeTeamMember($teamDelte, $nameDelete);
                }
                break;
        };
    }

    ?>
</body>

</html>