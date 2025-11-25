<?php
// Подключение к базе данных
$conn = mysqli_connect("localhost", "root", "", "topgames");
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TopGames - Рейтинг</title>
    <link rel="stylesheet" href="assets/css/owl.carousel.min.css">
    <link rel="stylesheet" href="assets/css/owl.theme.default.min.css">
    <link rel="stylesheet" href="assets/css/style.css"/>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css"/>
    <style>
        .sql-lab {
            background: #ffeaea;
            padding: 20px;
            margin: 20px 0;
            border-radius: 10px;
            border-left: 5px solid #e74c3c;
        }
        .sql-query {
            background: #2c3e50;
            color: white;
            padding: 10px;
            border-radius: 5px;
            margin: 10px 0;
            font-family: monospace;
            font-size: 14px;
            word-break: break-all;
        }
        .warning {
            background: #fff3cd;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
        .error-box {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
        }
        .success-box {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <!-- Шапка -->
    <header class="header-section">
        <div class="container">
            <a class="site-logo" href="index.html">
                <img src="assets/img/logo.png" alt="logo">
            </a>
            <div class="nav-switch">
                <div class="menu-icon">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
            <nav class="main-menu">
                <ul>
                    <li><a href="index.html">Главная</a></li>
                    <li><a href="Games.html">Игры</a></li>
                    <li><a href="news.html">Новости</a></li>
                    <li><a href="raiting.php">Рейтинг</a></li>
                    <li><a href="contact.html">Контакты</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <section class="top-section">
        <div class="wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-lg-9 col-lg-push-3">

                        <!-- 🔓 SQL INJECTION LAB -->
                        <div class="sql-lab">
                            <h2>🔓 SQL Injection Laboratory</h2>
                            
                            <div class="warning">
                                <strong>⚠️ Учебный проект!</strong> Демонстрация уязвимостей для обучения.
                            </div>

                            <h3>🎮 Поиск игр (уязвимая версия)</h3>
                            <form method="POST">
                                <input type="text" name="search" placeholder="Название игры" 
                                       style="width: 300px; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                                <button type="submit" 
                                        style="padding: 10px 20px; background: #e74c3c; color: white; border: none; border-radius: 5px;">
                                        🔍 Найти игру
                                </button>
                            </form>

                            <?php
                            if ($_POST && isset($_POST['search'])) {
                                $search = $_POST['search'];
                                
                                // УЯЗВИМЫЙ ЗАПРОС - SQL INJECTION POINT
                                $sql = "SELECT * FROM top WHERE name LIKE '%$search%'";
                                
                                echo "<div class='sql-query'>";
                                echo "<strong>🔴 ВЫПОЛНЯЕМЫЙ SQL ЗАПРОС:</strong><br>";
                                echo htmlspecialchars($sql);
                                echo "</div>";
                                
                                if ($conn) {
                                    // ОБРАБОТКА ОШИБОК
                                    try {
                                        $result = $conn->query($sql);
                                        
                                        if ($result && $result->num_rows > 0) {
                                            echo "<div class='success-box'>";
                                            echo "<h4>🎯 Найдено игр: " . $result->num_rows . "</h4>";
                                            while($row = $result->fetch_assoc()) {
                                                echo "<div style='border: 1px solid #ddd; padding: 10px; margin: 5px 0; border-radius: 5px;'>";
                                                echo "<strong>🎮 " . $row["name"] . "</strong><br>";
                                                echo "⭐ Рейтинг: " . $row["raiting"] . " | ";
                                                echo "👥 Кол-во: " . $row["quantity"] . " | ";
                                                echo "📅 Год: " . $row["year"];
                                                echo "</div>";
                                            }
                                            echo "</div>";
                                        } else {
                                            echo "<div class='error-box'>❌ Игры не найдены</div>";
                                        }
                                    } catch (Exception $e) {
                                        echo "<div class='error-box'>";
                                        echo "❌ <strong>ОШИБКА SQL:</strong> " . $e->getMessage();
                                        echo "<br><strong>💡 Совет:</strong> Проверьте синтаксис SQL инъекции";
                                        echo "</div>";
                                    }
                                } else {
                                    echo "<div class='error-box'>❌ Ошибка подключения к базе данных</div>";
                                }
                            }
                            ?>

                            <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin-top: 20px;">
                                <h4>💡 Примеры SQL инъекций для тестирования:</h4>
                                
                                <div style="background: #e74c3c; color: white; padding: 10px; border-radius: 5px; margin: 10px 0;">
                                    <strong>🎯 1. ПОКАЗАТЬ ВСЕ ИГРЫ (работает всегда)</strong><br>
                                    <code>' OR '1'='1</code><br>
                                    <code>test' OR '1'='1</code>
                                </div>
                                
                                <div style="background: #e67e22; color: white; padding: 10px; border-radius: 5px; margin: 10px 0;">
                                    <strong>🎯 2. UNION АТАКА - ИНФОРМАЦИЯ О БАЗЕ</strong><br>
                                    <code>' UNION SELECT 1,version(),3,4,5 -- </code><br>
                                    <code>' UNION SELECT 1,database(),user(),4,5 -- </code>
                                </div>
                                
                                <div style="background: #f39c12; color: white; padding: 10px; border-radius: 5px; margin: 10px 0;">
                                    <strong>🎯 3. ПРОСТЫЕ ИНЪЕКЦИИ</strong><br>
                                    <code>' OR 1=1 -- </code><br>
                                    <code>anything' OR id=1 -- </code>
                                </div>

                                <p><strong>📊 Используется локальная БД:</strong> topgames (таблица top)</p>
                            </div>
                        </div>
                        <!-- 🔓 КОНЕЦ SQL INJECTION LAB -->

                        <h1>Топ 10 игр</h1>
                        <hr>
                        
                        <div style="overflow-x:auto;">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th class="th-sm">№</th>
                                        <th class="th-sm">Название</th>
                                        <th class="th-sm">Оценка</th>
                                        <th class="th-sm">Количество</th>
                                        <th class="th-sm">Дата выхода</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Проверяем подключение
                                    if (!$conn) {
                                        echo "<tr><td colspan='5'>Ошибка подключения к базе данных</td></tr>";
                                    } else {
                                        // Безопасный запрос для отображения топа
                                        $sql_top = "SELECT id, name, raiting, quantity, year FROM top ORDER BY raiting DESC LIMIT 10";
                                        $result_top = $conn->query($sql_top);
                                        
                                        if ($result_top && $result_top->num_rows > 0) {
                                            while($row = $result_top->fetch_assoc()) {
                                                echo "<tr>";
                                                echo "<td>" . $row["id"] . "</td>";
                                                echo "<td><strong>" . $row["name"] . "</strong></td>";
                                                echo "<td>" . $row["raiting"] . "</td>";
                                                echo "<td>" . $row["quantity"] . "</td>";
                                                echo "<td>" . $row["year"] . "</td>";
                                                echo "</tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='5'>Нет данных в базе</td></tr>";
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="margin-8 clear"></div>
                    </div>
                </div>
            </div>
            <div class="clear"></div>
        </div>
    </section>

    <!-- Рассылка -->
    <section class="newsletter-section">
        <div class="container">
            <h2>Подписаться на рассылку</h2>
            <form class="newsletter-form">
                <input type="text" placeholder="Введите ваш email">
                <button class="site-btn">Подписаться</button>
            </form>
        </div>
    </section>

    <!-- Подвал -->
    <footer class="footer-section">
        <div class="container">
            <ul class="footer-menu">
                <li><a href="index.html">Главная</a></li>
                <li><a href="Games.html">Игры</a></li>
                <li><a href="news.html">Новости</a></li>
                <li><a href="raiting.php">Рейтинг</a></li>
                <li><a href="contact.html">Контакты</a></li>
            </ul>
            <p class="copyright">
                Copyright &copy;<script>document.write(new Date().getFullYear());</script> Все права защищены | by Зайцев Иван
            </p>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="assets/js/owl.carousel.min.js"></script>
    <script src="assets/js/myjavascript.js"></script>

    <?php
    // Закрываем соединение с БД
    if ($conn) {
        $conn->close();
    }
    ?>
</body>
</html>