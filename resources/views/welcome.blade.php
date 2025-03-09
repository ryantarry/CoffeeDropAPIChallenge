<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CoffeeDrop - Coffee Pod Recycling</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5e6d3;
        }

        .header {
            background-color: #6b4226;
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            margin-bottom: 20px;
        }

        .section {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        button {
            width: 100%;
            padding: 12px;
            margin: 8px 0;
            background-color: #6b4226;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .form-group {
            margin: 15px 0;
        }

        input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .btn {
            background-color: #6b4226;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin: 10px 5px;
        }

        .error-message {
            color: #dc3545;
            background-color: #f8d7da;
            padding: 10px;
            border-radius: 5px;
            margin: 10px 0;
            display: none;
        }

        .hidden {
            display: none;
        }

        .user-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #fff;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .results {
            margin-top: 15px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }

        .opening-hour-day {
            margin: 10px 0;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }

        .time-inputs {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>☕ CoffeeDrop</h1>
        <p>Recycle Coffee Pods & Earn Cashback</p>
    </div>

    <div class="section auth-section" id="authButtonsSection">
        <button onclick="showRegisterForm()">Create Account</button>
        <button onclick="showLoginForm()">Login</button>
    </div>

    <div class="section hidden reg-section" id="registerSection">
        <h2>Create Account</h2>
        <div id="registerError" class="error-message"></div>
        <div class="form-group">
            <input type="text" id="regName" placeholder="Full Name">
        </div>
        <div class="form-group">
            <input type="email" id="regEmail" placeholder="Email Address">
        </div>
        <div class="form-group">
            <input type="password" id="regPassword" placeholder="Password (min 8 characters)">
        </div>
        <div class="form-group">
            <input type="password" id="regPasswordConfirmation" placeholder="Confirm Password">
        </div>
        <button class="btn" onclick="register()">Register</button>
        <button class="btn" onclick="backToAuthOptions()">Cancel</button>
    </div>

    <div class="section hidden" id="loginSection">
        <h2>Welcome Back</h2>
        <div id="loginError" class="error-message"></div>
        <div class="form-group">
            <input type="email" id="loginEmail" placeholder="Email Address">
        </div>
        <div class="form-group">
            <input type="password" id="loginPassword" placeholder="Password">
        </div>
        <button class="btn" onclick="login()">Login</button>
        <button class="btn" onclick="backToAuthOptions()">Cancel</button>
    </div>

    <div class="hidden" id="appContent">
        <div class="user-info">
            <span>Welcome, <strong id="loggedInUser"></strong>!</span>
            <button class="btn" onclick="logout()">Logout</button>
        </div>

        <div class="section">
            <h2>📍 Find Nearest Drop-off</h2>
            <div class="form-group">
                <input type="text" id="postcode" placeholder="Enter UK Postcode">
            </div>
            <button class="btn" onclick="findNearestLocation()">Search</button>
            <div id="locationResults" class="results hidden"></div>
        </div>

        <div class="section">
            <h2>➕ Create New Location</h2>
            <div id="locationError" class="error-message"></div>
            <div class="form-group">
                <input type="text" id="locationPostcode" placeholder="Enter UK Postcode">
            </div>
            
            <h3>Opening Hours</h3>
            <div id="openingHours"></div>
            
            <button class="btn" onclick="createLocation()">Submit Location</button>
        </div>

        <div class="section">
            <h2>💰 Calculate Cashback</h2>
            <div class="form-group">
                <input type="number" id="ristretto" placeholder="Ristretto pods" min="0">
            </div>
            <div class="form-group">
                <input type="number" id="espresso" placeholder="Espresso pods" min="0">
            </div>
            <div class="form-group">
                <input type="number" id="lungo" placeholder="Lungo pods" min="0">
            </div>
            <button class="btn" onclick="calculateCashback()">Calculate</button>
            <div id="cashbackResult" class="results hidden"></div>
        </div>


        <div class="section">
            <h2>📋 Recent Calculations</h2>
            <button class="btn" onclick="loadHistory()">Refresh History</button>
            <div id="calculationHistory" class="results"></div>
        </div>
    </div>

    <script>
        let authToken = null;

        function showRegisterForm() {
            document.getElementById('authButtonsSection').classList.add('hidden');
            document.getElementById('registerSection').classList.remove('hidden');
            document.getElementById('loginSection').classList.add('hidden');
            clearErrors();
        }

        function showLoginForm() {
            document.getElementById('authButtonsSection').classList.add('hidden');
            document.getElementById('loginSection').classList.remove('hidden');
            document.getElementById('registerSection').classList.add('hidden');
            clearErrors();
        }

        function backToAuthOptions() {
            document.getElementById('authButtonsSection').classList.remove('hidden');
            document.getElementById('registerSection').classList.add('hidden');
            document.getElementById('loginSection').classList.add('hidden');
            clearErrors();
        }

        function clearErrors() {
            document.getElementById('registerError').style.display = 'none';
            document.getElementById('loginError').style.display = 'none';
        }

        // Auth Functions
        async function register() {
            const userData = {
                name: document.getElementById('regName').value.trim(),
                email: document.getElementById('regEmail').value.trim(),
                password: document.getElementById('regPassword').value.trim(),
                password_confirmation: document.getElementById('regPasswordConfirmation').value.trim()
            };

            if (!validateRegistration(userData)) return;

            try {
                const response = await fetch('/api/register', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(userData)
                });
                handleAuthResponse(response, 'register');
            } catch (error) {
                showError('register', 'Connection failed. Please try again.');
            }
        }

        async function login() {
            const credentials = {
                email: document.getElementById('loginEmail').value.trim(),
                password: document.getElementById('loginPassword').value.trim()
            };

            if (!validateLogin(credentials)) return;

            try {
                const response = await fetch('/api/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(credentials)
                });
                handleAuthResponse(response, 'login');
            } catch (error) {
                showError('login', 'Connection failed. Please try again.');
            }
        }

        // Validation
        function validateRegistration(data) {
            let valid = true;
            const error = document.getElementById('registerError');

            if (!data.name || !data.email || !data.password || !data.password_confirmation) {
                showError('register', 'All fields are required');
                valid = false;
            } else if (data.password !== data.password_confirmation) {
                showError('register', 'Passwords do not match');
                valid = false;
            } else if (data.password.length < 8) {
                showError('register', 'Password must be at least 8 characters');
                valid = false;
            }

            return valid;
        }

        function validateLogin(data) {
            if (!data.email || !data.password) {
                showError('login', 'Email and password are required');
                return false;
            }
            return true;
        }

        // Response Handling
        async function handleAuthResponse(response, type) {
            const errorElement = document.getElementById(`${type}Error`);
            
            try {
                const data = await response.json();
                
                if (response.ok) {
                    authToken = data.token;
                    localStorage.setItem('authToken', data.token);
                    document.getElementById('appContent').classList.remove('hidden');
                    document.querySelectorAll('.auth-section').forEach(el => el.classList.add('hidden'));
                    document.querySelectorAll('.reg-section').forEach(el => el.classList.add('hidden'));
                    document.getElementById('loggedInUser').textContent = data.user.name;
                    clearForms();
                    loadHistory();
                } else {
                    const errorMsg = data.errors ? Object.values(data.errors).join(', ') : data.message;
                    showError(type, errorMsg || 'Authentication failed');
                }
            } catch (error) {
                showError(type, 'An unexpected error occurred');
            }
        }

        function showError(type, message) {
            const errorElement = document.getElementById(`${type}Error`);
            errorElement.textContent = message;
            errorElement.style.display = 'block';
        }

        function clearForms() {
            document.getElementById('regName').value = '';
            document.getElementById('regEmail').value = '';
            document.getElementById('regPassword').value = '';
            document.getElementById('regPasswordConfirmation').value = '';
            document.getElementById('loginEmail').value = '';
            document.getElementById('loginPassword').value = '';
        }

        // Location Features
        function initOpeningHours() {
            const days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
            const container = document.getElementById('openingHours');
            
            days.forEach(day => {
                const div = document.createElement('div');
                div.className = 'opening-hour-day';
                div.innerHTML = `
                    <label>${day}</label>
                    <div class="time-inputs">
                        <input type="time" data-day="${day.toLowerCase()}" class="opening-time">
                        <input type="time" data-day="${day.toLowerCase()}" class="closing-time">
                    </div>
                `;
                container.appendChild(div);
            });
        }

        async function createLocation() {
            const postcode = document.getElementById('locationPostcode').value.trim();
            const errorDiv = document.getElementById('locationError');
            
            if (!postcode) {
                errorDiv.textContent = 'Please enter a valid UK postcode';
                errorDiv.style.display = 'block';
                return;
            }

            const daysOfWeek = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
            const openingTimes = {};
            const closingTimes = {};
            let hasError = false;

            daysOfWeek.forEach(day => {
                const opening = document.querySelector(`input[data-day="${day}"].opening-time`).value;
                const closing = document.querySelector(`input[data-day="${day}"].closing-time`).value;

                if ((opening && !closing) || (!opening && closing)) {
                    hasError = true;
                    document.querySelector(`input[data-day="${day}"].opening-time`).style.borderColor = '#dc3545';
                    document.querySelector(`input[data-day="${day}"].closing-time`).style.borderColor = '#dc3545';
                } else {
                    openingTimes[day] = opening || null;
                    closingTimes[day] = closing || null;
                }
            });

            if (hasError) {
                errorDiv.textContent = 'Each day must have both opening and closing times or both be empty.';
                errorDiv.style.display = 'block';
                return;
            }

            try {
                const response = await fetch('api/new-location', {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${authToken}`,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        postcode: postcode,
                        opening_times: JSON.stringify(openingTimes),
                        closing_times: JSON.stringify(closingTimes)
                    })
                });

                const data = await response.json();
                
                if (response.ok) {
                    alert('Location created successfully!');
                    document.getElementById('locationPostcode').value = '';
                    document.querySelectorAll('.time-inputs input').forEach(input => input.value = '');
                } else {
                    errorDiv.textContent = data.error || 'Error creating location';
                    errorDiv.style.display = 'block';
                }
            } catch (error) {
                errorDiv.textContent = 'Failed to connect to server';
                errorDiv.style.display = 'block';
            }
        }

        // App Features
        async function findNearestLocation() {
            const postcode = document.getElementById('postcode').value.trim();
            const resultsDiv = document.getElementById('locationResults');
            resultsDiv.innerHTML = 'Searching...';
            resultsDiv.classList.remove('hidden');

            if (!postcode) {
                resultsDiv.innerHTML = 'Please enter a valid UK postcode';
                return;
            }

            try {
                const response = await fetch(`/api/nearest-location?postcode=${encodeURIComponent(postcode)}`, {
                    headers: {
                        'Authorization': `Bearer ${authToken}`,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                });
                
                const data = await response.json();
                
                if (response.ok && data.data) {
                    resultsDiv.innerHTML = `
                        <h3>Nearest Location</h3>
                        <p>📌 ${data.data.address.ward}, ${data.data.address.district}</p>
                        <p>📮 Postcode: ${data.data.address.postcode}</p>
                        <p>📍 County: ${data.data.address.county}</p>
                        <div class="opening-hours">
                            <h4>Opening Hours:</h4>
                            ${Object.entries(data.data.opening_times)
                            .map(([day, time]) => `
                                <div class="hours">
                                    <span class="day">${day.charAt(0).toUpperCase() + day.slice(1)}:</span>
                                    <span class="time">${time} - ${data.data.closing_times[day]}</span>
                                </div>
                            `).join('')}
                        </div>
                    `;
                } else {
                    resultsDiv.innerHTML = data.error || 'No locations found near this postcode';
                }
            } catch (error) {
                resultsDiv.innerHTML = 'Error connecting to server';
            }
        }

        async function calculateCashback() {
            const pods = {
                Ristretto: parseInt(document.getElementById('ristretto').value) || 0,
                Espresso: parseInt(document.getElementById('espresso').value) || 0,
                Lungo: parseInt(document.getElementById('lungo').value) || 0
            };

            if (pods.Ristretto + pods.Espresso + pods.Lungo === 0) {
                return alert('Please enter at least one pod type');
            }

            try {
                const response = await fetch('/api/calculate-cashback', {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${authToken}`,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(pods)
                });
                
                const data = await response.json();
                if (response.ok) {
                    document.getElementById('cashbackResult').textContent = 
                        `Cashback: ${data.data.cashback}`;
                    document.getElementById('cashbackResult').classList.remove('hidden');
                    loadHistory();
                } else {
                    alert(data.message || 'Calculation failed');
                }
            } catch (error) {
                alert('Error calculating cashback');
            }
        }

        async function loadHistory() {
            try {
                const response = await fetch('/api/calculation-history', {
                    headers: {
                        'Authorization': `Bearer ${authToken}`,
                        'Accept': 'application/json'
                    }
                });
                const data = await response.json();
                
                document.getElementById('calculationHistory').innerHTML = data.data.length > 0 
                    ? data.data.map(entry => `
                        <div class="history-item">
                            ${entry.Ristretto} Ristretto • ${entry.Espresso} Espresso • ${entry.Lungo} Lungo  → ${entry.cashback}
                        </div>
                    `).join('')
                    : '<p>No calculations yet</p>';
            } catch (error) {
                alert('Failed to load history');
            }
        }

        function logout() {
            localStorage.removeItem('authToken');
            location.reload();
        }

        // Initialize
        window.onload = () => {
            initOpeningHours();
            const token = localStorage.getItem('authToken');
            if (token) {
                authToken = token;
                document.getElementById('appContent').classList.remove('hidden');
                document.getElementById('authButtonsSection').classList.add('hidden');
                fetch('/api/user', {
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                }).then(response => response.json())
                  .then(data => {
                      document.getElementById('loggedInUser').textContent = data.name;
                      loadHistory();
                  });
            }
        };
    </script>
</body>
</html>
