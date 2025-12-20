<?php
// Подключение к базе данных
$conn = mysqli_connect("localhost", "root", "", "topgames");

if ($_POST && isset($_POST['username'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // УЯЗВИМЫЙ ЗАПРОС - SQL INJECTION
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    
    echo "<div class='sql-result'>";
    echo "<strong>🔴 SQL запрос:</strong><br>";
    echo htmlspecialchars($sql);
    echo "</div>";
    
    if ($conn) {
        $result = $conn->query($sql);
        
        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();
            echo "<div style='color: green; margin: 10px 0;'>";
            echo "✅ <strong>УСПЕШНЫЙ ВХОД!</strong><br>";
            echo "Пользователь: " . $user['username'];
            if ($user['is_admin']) {
                echo " 👑 (Администратор)";
            }
            echo "</div>";
        } else {
            echo "<div style='color: red; margin: 10px 0;'>❌ Ошибка входа</div>";
        }
    } else {
        echo "<div style='color: red; margin: 10px 0;'>❌ Ошибка подключения к БД</div>";
    }
}

if ($conn) {
    $conn->close();
}
?>