<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap');
    /* Font Awesome for Icons */
    @import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');


    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Inter', sans-serif;
    }

    body {
        background-color: #f4f7f6;
        display: flex;
        min-height: 100vh;
        margin: 0;
    }

    /* Sidebar Styles */
    aside {
        width: 260px;
        background: linear-gradient(180deg, #2d3436 0%, #000000 100%);
        color: #fff;
        min-height: 100vh;
        position: fixed;
        transition: all 0.3s;
        z-index: 1000;
        top: 0;
        left: 0;
    }

    aside ul {
        list-style: none;
        padding-top: 80px;
    }

    aside ul li {
        padding: 5px 20px;
    }

    aside ul li a {
        color: #b2bec3;
        text-decoration: none;
        display: block;
        padding: 12px 15px;
        border-radius: 10px;
        transition: all 0.3s;
        font-weight: 500;
    }

    aside ul li a:hover {
        background: rgba(255, 255, 255, 0.1);
        color: #fff;
        transform: translateX(5px);
    }

    aside ul li a.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
    }

    /* Header Styles */
    .header {
        background-color: #fff !important;
        height: 70px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0 30px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        position: fixed;
        top: 0;
        left: 260px;
        right: 0;
        z-index: 999;
    }

    .header a {
        text-decoration: none !important;
        color: #2d3436 !important;
        font-weight: 700;
        font-size: 1.2rem;
    }

    .logout .btn {
        background: linear-gradient(135deg, #ff7675 0%, #d63031 100%) !important;
        color: #fff !important;
        padding: 8px 20px !important;
        border-radius: 50px !important;
        text-decoration: none !important;
        font-size: 0.9rem !important;
        font-weight: 600;
        transition: all 0.3s;
        border: none !important;
    }

    /* Main Content Styles */
    .content {
        margin-left: 260px;
        padding: 100px 40px 40px;
        width: calc(100% - 260px);
        min-height: 100vh;
    }

    .content h1 {
        font-size: 1.8rem;
        color: #2d3436;
        margin-bottom: 30px;
    }

    /* Dashboard Cards */
    .card-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 25px;
        margin-bottom: 40px;
    }

    .card {
        background: #fff;
        padding: 25px;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        transition: all 0.3s;
        border: 1px solid #eee;
        text-align: center;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(0,0,0,0.1);
    }

    .card h3 {
        font-size: 1rem;
        color: #636e72;
        margin-bottom: 10px;
    }

    .card .value {
        font-size: 2.5rem;
        font-weight: 700;
        color: #2d3436;
    }

    .card.blue { border-top: 5px solid #6c5ce7; }
    .card.green { border-top: 5px solid #00b894; }
    .card.orange { border-top: 5px solid #fdcb6e; }
    .card.red { border-top: 5px solid #ff7675; }

    /* Table Styles */
    table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 10px;
        margin-top: 20px;
    }

    th {
        padding: 15px;
        background: #fff;
        color: #636e72;
        font-weight: 600;
        text-align: left;
    }

    td {
        padding: 15px;
        background: #fff;
        color: #2d3436;
    }

    tr td:first-child { border-top-left-radius: 15px; border-bottom-left-radius: 15px; }
    tr td:last-child { border-top-right-radius: 15px; border-bottom-right-radius: 15px; }

    .table-btn {
        padding: 6px 15px;
        border-radius: 50px;
        text-decoration: none;
        font-size: 0.85rem;
        font-weight: 600;
        transition: all 0.3s;
        display: inline-block;
    }

    .table-btn-delete { background: #ffeaa7; color: #d63031; }
    .table-btn-update { background: #dff9fb; color: #0984e3; }

    /* Form Elements */
    .form-container {
        background: #fff;
        padding: 40px;
        border-radius: 20px;
        max-width: 800px;
        margin: 0 auto;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    }

    label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #555;
    }

    input, select, textarea {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #eee;
        border-radius: 12px;
        margin-bottom: 20px;
        outline: none;
        transition: all 0.3s;
    }

    input:focus { border-color: #6c5ce7; }

    /* Password Toggle Styles */
    .password-container {
        position: relative;
        width: 100%;
    }

    .password-container input {
        padding-right: 45px;
    }

    .toggle-password {
        position: absolute;
        right: 15px;
        top: 42%;
        transform: translateY(-50%);
        cursor: pointer;
        color: #636e72;
        font-size: 1.1rem;
        transition: all 0.3s;
        z-index: 10;
        padding: 5px;
    }

    .toggle-password:hover {
        color: #6c5ce7;
        transform: translateY(-50%) scale(1.1);
    }

    /* Back Navigation Styles */
    .back-nav {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 35px;
        height: 35px;
        background: #f1f2f6;
        border-radius: 50%;
        color: #2d3436;
        text-decoration: none;
        margin-right: 15px;
        transition: all 0.3s;
        cursor: pointer;
        border: none;
        outline: none;
    }

    .back-nav:hover {
        background: #6c5ce7;
        color: #fff;
        transform: translateX(-3px);
    }

    .back-nav i {
        font-size: 0.9rem;
    }



    .submit-btn {
        background: linear-gradient(135deg, #6c5ce7 0%, #a29bfe 100%);
        color: #fff;
        padding: 15px 30px;
        border-radius: 12px;
        font-weight: 700;
        width: 100%;
        border: none;
        cursor: pointer;
        transition: all 0.3s;
    }

    .submit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(108, 92, 231, 0.3);
    }

    /* Responsive */
    @media (max-width: 991px) {
        aside { transform: translateX(-100%); }
        .header, .content { left: 0; margin-left: 0; width: 100%; }
    }
</style>

<script>
function togglePassword(inputId, iconId) {
    const passwordInput = document.getElementById(inputId);
    const toggleIcon = document.getElementById(iconId);
    
    if (passwordInput.type === "password") {
        passwordInput.type = "text";
        toggleIcon.classList.remove("fa-eye");
        toggleIcon.classList.add("fa-eye-slash");
    } else {
        passwordInput.type = "password";
        toggleIcon.classList.remove("fa-eye-slash");
        toggleIcon.classList.add("fa-eye");
    }
}

function goBack() {
    if (document.referrer && document.referrer.indexOf(window.location.host) !== -1) {
        window.history.back();
    } else {
        window.location.href = 'index.php';
    }
}
</script>


