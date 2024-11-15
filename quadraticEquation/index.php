<?php
class QuadraticEquation
{
    private $a;
    private $b;
    private $c;
    public function __construct($a, $b, $c)
    {
        $this->a = $a;
        $this->b = $b;
        $this->c = $c;
    }
    public function getterA()
    {
        return $this->a;
    }
    public function getterB()
    {
        return $this->b;
    }
    public function getterC()
    {
        return $this->c;
    }
    public function getDiscriminant()
    {

        return $this->b * $this->b - (4 * $this->a * $this->c);
    }
    public function getRoot1()
    {
        $a = $this->a;
        $b = $this->b;
        $c = $this->c;
        return (-$b + pow($b * $b - (4 * $a * $c), 0.5)) / (2 * $a);
    }
    public function getRoot2()
    {
        $a = $this->a;
        $b = $this->b;
        $c = $this->c;
        return (-$b - pow($b * $b - (4 * $a * $c), 0.5)) / (2 * $a);
    }
}
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
            <label for="">nhập vào a:</label>
            <input type="text" name="a" id="">
        </div>
        <div class="">
            <label for="">nhập vào b:</label>
            <input type="text" name="b" id="">
        </div>
        <div class="">
            <label for="">nhập vào c:</label>
            <input type="text" name="c" id="">
        </div>
        <input type="submit" value="tìm nghiệm">

    </form>
    <?php
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        if (empty($_POST["a"]) && empty($_POST["b"]) && empty($_POST["c"])) {
            echo "dữ liệu ko được để trống";
        } else {
            $timNg = new QuadraticEquation($_POST["a"], $_POST["b"], $_POST["c"]);
            if ($timNg->getDiscriminant() > 0) {
                $root1 = $timNg->getRoot1();
                $root2 = $timNg->getRoot2();
                echo " <p> The equation has two  roots : $root1 and   $root2  </p>";
            } else if ($timNg->getDiscriminant() == 0) {
                $root1 = $timNg->getRoot1();
                echo " <p> The equation has one roots : $root1  </p>";
            } else {
                echo " <p> The equation has no roots</p>";
            }
        }
    }
    ?>
</body>

</html>