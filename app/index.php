<?php
// Подключаем автозагрузчик Composer, который дает доступ к библиотекам
require __DIR__ . '/vendor/autoload.php';

// Используем класс Yaml из библиотеки Symfony
use Symfony\Component\Yaml\Yaml;

try {
    // Читаем и парсим YAML-файл
    $config = Yaml::parseFile('config.yaml');

    // Берем данные для подключения к БД из загруженной конфигурации
    $dbConfig = $config['database'];
    $servername = $dbConfig['host'];
    $username = $dbConfig['user'];
    $password = $dbConfig['password'];
    $dbname = $dbConfig['name'];

    // Подключаемся к БД
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        throw new Exception("DB connection error: " . $conn->connect_error);
    }

    $result = $conn->query("SELECT id, name, email FROM users");

    echo "<h1>List of Users (from YAML config)</h1>";
    if ($result && $result->num_rows > 0) {
        echo "<table border='1' cellpadding='6'><tr><th>ID</th><th>Name</th><th>Email</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>".htmlspecialchars($row['id'])."</td>
                <td>".htmlspecialchars($row['name'])."</td>
                <td>".htmlspecialchars($row['email'])."</td>
                </tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No users found.</p>";
    }
    $conn->close();

} catch (Exception $e) {
    echo "<h2>An error occurred:</h2>";
    echo "<pre>" . htmlspecialchars($e->getMessage()) . "</pre>";
    exit;
}
?>