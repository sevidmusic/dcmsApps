<div id="PDOPlayground" class="pdo-playground-container">
    <div id="PDOPlaygroundAjaxOutput" class="pdo-playground-ajax-container">
        <h1>PDO Playground</h1>
        <?php
        /**
         * Create a new Data Base the PDO way.
         * Source for boilerplate @see https://stackoverflow.com/questions/2583707/can-i-create-a-database-using-pdo-in-php
         * WARNING: THE FOLLOWING CODE IS FOR LOCAL DEV ONLY! DO NOT USE IN ANY PRODUCTION SITE!!!
         */
        $host = "localhost";
        $root = "root";
        $root_password = "root";
        $user = 'sevidmusic';
        $pass = 'iLoveDorianEternally';
        $db = "PDOPlaygroundDev1";
        $charset = 'utf8mb4';
        // CREATE DB IF NOT EXISTS
        try {
            $dbh = new PDO("mysql:host=$host", $root, $root_password);
            $dbh->exec("CREATE DATABASE IF NOT EXISTS `$db`;
                CREATE USER '$user'@'localhost' IDENTIFIED BY '$pass';
                GRANT ALL ON `$db`.* TO '$user'@'localhost';
                FLUSH PRIVILEGES;")
            or die(print_r($dbh->errorInfo(), true));
        } catch (PDOException $e) {
            die("DB ERROR: " . $e->getMessage());
        }
        // TEST CONNECTION
        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        try {
            $pdo = new PDO($dsn, $user, $pass, $options);
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
        ?>
        <p>Output:</p>
        <?php
        // Test Query using Prepared Statment | Read all user ids from users | NOTE users TABLE MUST EXIST
        $stmt = $pdo->prepare('SELECT * FROM users WHERE userName = ? OR  userName = ?');
        $stmt->execute(['sevidmusic', 'dorianDarling']);

        while ($row = $stmt->fetch()) {
            echo '<p>User Name: ' . $row['userName'] . "</p>";
            echo '<p>User Id: ' . $row['userId'] . "</p>";
            // Test sub Query using Prepared Statment | Read all user ids from users | NOTE users TABLE MUST EXIST
            $Sstmt = $pdo->prepare('SELECT * FROM passwords WHERE userId = ?');
            $Sstmt->execute([$row['userId']]);
            while ($Srow = $Sstmt->fetch()) {
                echo '<p>Password: ' . $Srow['password'] . "</p>";
            }
        }
        ?>
    </div>
</div>
