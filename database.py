import sqlite3
import os

def init_database():
    # Удаляем старую БД если есть
    if os.path.exists('sqli_lab.db'):
        os.remove('sqli_lab.db')
    
    conn = sqlite3.connect('sqli_lab.db')
    cursor = conn.cursor()
    
    # Таблица пользователей
    cursor.execute('''
        CREATE TABLE users (
            id INTEGER PRIMARY KEY,
            username TEXT UNIQUE,
            password TEXT,
            email TEXT,
            is_admin BOOLEAN
        )
    ''')
    
    # Таблица продуктов
    cursor.execute('''
        CREATE TABLE products (
            id INTEGER PRIMARY KEY,
            name TEXT,
            price DECIMAL,
            description TEXT
        )
    ''')
    
    # Секретная таблица для демонстрации краж
    cursor.execute('''
        CREATE TABLE secret_data (
            id INTEGER PRIMARY KEY,
            secret_text TEXT,
            importance_level INTEGER
        )
    ''')
    
    # Тестовые пользователи
    users = [
        (1, 'admin', 'admin123', 'admin@company.com', 1),
        (2, 'alice', 'password123', 'alice@company.com', 0),
        (3, 'bob', 'bobpass', 'bob@company.com', 0),
        (4, 'eve', 'eve2024', 'eve@company.com', 0)
    ]
    
    # Тестовые продукты
    products = [
        (1, 'Gaming Laptop', 1299.99, 'High-end gaming laptop'),
        (2, 'Smartphone', 799.99, 'Latest smartphone'),
        (3, 'Tablet', 449.99, 'Android tablet'),
        (4, 'Headphones', 199.99, 'Wireless headphones')
    ]
    
    # Секретные данные
    secrets = [
        (1, 'Secret project: Aurora. Launch date: 2025', 9),
        (2, 'CEO password: SuperSecret123!', 10),
        (3, 'Database master key: XyZ789!@#', 10),
        (4, 'Company revenue target: $10M', 7)
    ]
    
    cursor.executemany('INSERT INTO users VALUES (?,?,?,?,?)', users)
    cursor.executemany('INSERT INTO products VALUES (?,?,?,?)', products)
    cursor.executemany('INSERT INTO secret_data VALUES (?,?,?)', secrets)
    
    conn.commit()
    conn.close()
    print("✅ База данных создана с тестовыми данными!")

def test_connection():
    """Проверка что БД работает"""
    conn = sqlite3.connect('sqli_lab.db')
    cursor = conn.cursor()
    
    print("📊 ТЕСТОВЫЕ ДАННЫЕ:")
    
    cursor.execute("SELECT * FROM users")
    users = cursor.fetchall()
    print("👥 ПОЛЬЗОВАТЕЛИ:")
    for user in users:
        print(f"  {user}")
    
    cursor.execute("SELECT * FROM products")
    products = cursor.fetchall()
    print("🛍️ ПРОДУКТЫ:")
    for product in products:
        print(f"  {product}")
    
    cursor.execute("SELECT * FROM secret_data")
    secrets = cursor.fetchall()
    print("🔐 СЕКРЕТНЫЕ ДАННЫЕ:")
    for secret in secrets:
        print(f"  {secret}")
    
    conn.close()
    return users

# Уязвимые функции для демонстрации SQL инъекций
def vulnerable_login(username, password):
    """УЯЗВИМАЯ функция входа - для демонстрации SQLi"""
    conn = sqlite3.connect('sqli_lab.db')
    cursor = conn.cursor()
    
    # УЯЗВИМЫЙ ЗАПРОС - цель для атак
    query = f"SELECT * FROM users WHERE username = '{username}' AND password = '{password}'"
    print(f"🔴 ВЫПОЛНЯЕМ УЯЗВИМЫЙ ЗАПРОС: {query}")
    
    cursor.execute(query)
    result = cursor.fetchone()
    conn.close()
    
    return result

def vulnerable_search(product_name):
    """УЯЗВИМАЯ функция поиска - для демонстрации SQLi"""
    conn = sqlite3.connect('sqli_lab.db')
    cursor = conn.cursor()
    
    # УЯЗВИМЫЙ ЗАПРОС
    query = f"SELECT * FROM products WHERE name LIKE '%{product_name}%'"
    print(f"🔴 ВЫПОЛНЯЕМ УЯЗВИМЫЙ ПОИСК: {query}")
    
    cursor.execute(query)
    result = cursor.fetchall()
    conn.close()
    
    return result

if __name__ == "__main__":
    init_database()
    test_connection()
    
    print("\n🎯 ДЕМОНСТРАЦИЯ SQL ИНЪЕКЦИЙ:")
    
    # Нормальный вход
    print("\n1. НОРМАЛЬНЫЙ ВХОД:")
    result = vulnerable_login('admin', 'admin123')
    print(f"   Результат: {result}")
    
    # SQL инъекция - обход пароля
    print("\n2. SQL ИНЪЕКЦИЯ - ОБХОД ПАРОЛЯ:")
    result = vulnerable_login("admin' --", "anything")
    print(f"   Результат: {result}")
    
    # SQL инъекция - вход без пароля
    print("\n3. SQL ИНЪЕКЦИЯ - ВХОД БЕЗ ПАРОЛЯ:")
    result = vulnerable_login("' OR '1'='1", "anything")
    print(f"   Результат: {result}")
