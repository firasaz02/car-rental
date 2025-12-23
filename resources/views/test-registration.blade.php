<!DOCTYPE html>
<html>
<head>
    <title>Registration Test</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <h1>Registration Test</h1>
    <form id="test-form">
        <div>
            <label>Name:</label>
            <input type="text" name="name" value="Test User" required>
        </div>
        <div>
            <label>Email:</label>
            <input type="email" name="email" value="testuser@example.com" required>
        </div>
        <div>
            <label>Password:</label>
            <input type="password" name="password" value="password123" required>
        </div>
        <div>
            <label>Confirm Password:</label>
            <input type="password" name="password_confirmation" value="password123" required>
        </div>
        <div>
            <label>Role:</label>
            <select name="role" required>
                <option value="user">User</option>
                <option value="admin">Admin</option>
                <option value="chauffeur">Chauffeur</option>
            </select>
        </div>
        <button type="submit">Test Registration</button>
    </form>
    
    <div id="result"></div>
    
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        document.getElementById('test-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const data = Object.fromEntries(formData);
            
            fetch('/role-selection/register', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('result').innerHTML = '<pre>' + JSON.stringify(data, null, 2) + '</pre>';
            })
            .catch(error => {
                document.getElementById('result').innerHTML = '<p style="color: red;">Error: ' + error.message + '</p>';
            });
        });
    </script>
</body>
</html>

