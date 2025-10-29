<?php
echo "<pre>";
$servername = getenv('DB_HOST') ?: 'db-server';
$username = getenv('DB_USER') ?: 'root';
$password = getenv('DB_PASS') ?: 'root';
$dbname = getenv('DB_NAME') ?: 'devops_lab';
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {die("Ошибка подключения к БД: " . $conn->connect_error);}
echo "Успешно подключено к базе данных.\n";
$jsonFilePath = __DIR__ . '/users.json';
if (!file_exists($jsonFilePath)) {die("Ошибка: Файл users.json не найден по пути: " . $jsonFilePath);}
$jsonContent = file_get_contents($jsonFilePath);
$users = json_decode($jsonContent, true);
if (json_last_error() !== JSON_ERROR_NONE) {die("Ошибка декодирования JSON: " . json_last_error_msg());}
echo "Файл users.json успешно прочитан.\n";
$conn->query("TRUNCATE TABLE users");
echo "Таблица 'users' очищена.\n";
$stmt = $conn->prepare("INSERT INTO users (name, email) VALUES (?, ?)");
$stmt->bind_param("ss", $name, $email);
echo "Начинаем заполнение таблицы...\n";
foreach ($users as $user) {
    $name = $user['name'];
    $email = $user['email'];
    if ($stmt->execute()) {echo " -> Добавлен пользователь: " . htmlspecialchars($name) . "\n";
    } else {echo " -> Ошибка добавления пользователя " . htmlspecialchars($name) . ": " . $stmt->error . "\n";
    }
}
echo "\nЗаполнение базы данных завершено!\n";
$stmt->close();
$conn->close();
echo "</pre>";
?>